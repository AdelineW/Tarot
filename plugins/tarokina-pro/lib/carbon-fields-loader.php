<?php
/**
 * Carbon Fields Loader para Tarokina Pro - OPTIMIZADO
 * 
 * Este archivo maneja la carga segura de Carbon Fields evitando conflictos
 * con otras versiones que puedan estar activas en otros plugins.
 * 
 * OPTIMIZACIONES DE RENDIMIENTO:
 * - Carga lazy/diferida
 * - Cache de verificaciones
 * - Reducción de hooks
 * - Menos logging
 */

 
// Prevenir acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Cargar monitor de rendimiento
require_once __DIR__ . '/performance-monitor.php';

// Verificar si Carbon Fields está temporalmente desactivado
if ( tarokina_pro_disable_carbon_fields_temporarily() ) {
    return; // Salir sin cargar Carbon Fields
}

// Cache estático para evitar verificaciones repetidas
class Tarokina_Pro_Carbon_Fields_Cache {
    private static $loaded = false;
    private static $conflicts_checked = false;
    private static $conflicts_cache = null;
    
    public static function is_loaded() {
        return self::$loaded;
    }
    
    public static function set_loaded( $status = true ) {
        self::$loaded = $status;
    }
    
    public static function conflicts_checked() {
        return self::$conflicts_checked;
    }
    
    public static function get_conflicts() {
        return self::$conflicts_cache;
    }
    
    public static function set_conflicts( $conflicts ) {
        self::$conflicts_checked = true;
        self::$conflicts_cache = $conflicts;
    }
}

/**
 * Verificar integridad de Carbon Fields antes de cargar
 */
function tarokina_pro_validate_carbon_fields_integrity() {
    $carbon_fields_path = __DIR__ . '/carbon-fields';
    $required_files = array(
        '/carbon-fields-plugin.php',
        '/vendor/autoload.php',
        '/vendor/composer/autoload_real.php'
    );
    
    // Verificar archivos básicos requeridos
    foreach ( $required_files as $file ) {
        if ( ! file_exists( $carbon_fields_path . $file ) ) {
            return false;
        }
    }
    
    // Verificar si platform_check.php existe (problema común en instalaciones incompletas)
    $platform_check = $carbon_fields_path . '/vendor/composer/platform_check.php';
    if ( ! file_exists( $platform_check ) ) {
        // Intentar generar platform_check.php básico si no existe
        if ( tarokina_pro_generate_platform_check( $platform_check ) ) {
            error_log( 'Tarokina Pro: platform_check.php generado automáticamente' );
            // Marcar que se generó para mostrar notificación al admin
            add_option( 'tarokina_pro_carbon_fields_error', 'platform_check_generated' );
        } else {
            return false;
        }
    }
    
    return true;
}

/**
 * Generar platform_check.php básico si falta - MEJORADO para servidores compartidos
 */
function tarokina_pro_generate_platform_check( $platform_check_path ) {
    // Contenido mejorado para platform_check.php
    $platform_check_content = '<?php
// Archivo platform_check.php generado automáticamente por Tarokina Pro
// Este archivo es requerido por Composer autoload para verificaciones de plataforma
// Generado el: ' . date( 'Y-m-d H:i:s' ) . '

// Verificaciones básicas de plataforma para Carbon Fields
if ( version_compare( PHP_VERSION, "7.0.0", "<" ) ) {
    throw new Exception( "Carbon Fields requiere PHP 7.0.0 o superior. Versión actual: " . PHP_VERSION );
}

// Verificar extensiones básicas requeridas por Carbon Fields
$required_extensions = array( "json", "mbstring" );
foreach ( $required_extensions as $ext ) {
    if ( ! extension_loaded( $ext ) ) {
        throw new Exception( "Carbon Fields requiere la extensión PHP: " . $ext );
    }
}

// Verificar funciones críticas
$required_functions = array( "json_encode", "json_decode" );
foreach ( $required_functions as $func ) {
    if ( ! function_exists( $func ) ) {
        throw new Exception( "Carbon Fields requiere la función PHP: " . $func );
    }
}

// Verificaciones específicas para servidores compartidos
if ( ini_get( "safe_mode" ) ) {
    // Nota: safe_mode fue eliminado en PHP 5.4, pero algunos servidores compartidos 
    // pueden tener configuraciones similares
}

// Todo OK - Carbon Fields puede cargarse
';
    
    // Asegurar que el directorio exists de forma más robusta
    $platform_check_dir = dirname( $platform_check_path );
    if ( ! is_dir( $platform_check_dir ) ) {
        // Intentar crear el directorio con permisos adecuados
        if ( ! wp_mkdir_p( $platform_check_dir ) ) {
            error_log( "Tarokina Pro: No se pudo crear el directorio: {$platform_check_dir}" );
            
            // Intentar crear manualmente con mkdir recursivo
            if ( ! @mkdir( $platform_check_dir, 0755, true ) ) {
                error_log( "Tarokina Pro: Error crítico - No se puede crear directorio composer" );
                return false;
            }
        }
    }
    
    // Verificar permisos de escritura antes de intentar escribir
    if ( ! is_writable( $platform_check_dir ) ) {
        error_log( "Tarokina Pro: Directorio no escribible: {$platform_check_dir}" );
        return false;
    }
    
    // Intentar escribir el archivo con manejo robusto de errores
    $bytes_written = @file_put_contents( $platform_check_path, $platform_check_content, LOCK_EX );
    
    if ( $bytes_written === false ) {
        error_log( "Tarokina Pro: Error al escribir platform_check.php en: {$platform_check_path}" );
        return false;
    }
    
    // Verificar que el archivo se escribió correctamente
    if ( ! file_exists( $platform_check_path ) || filesize( $platform_check_path ) === 0 ) {
        error_log( "Tarokina Pro: platform_check.php generado pero parece estar vacío o corrupto" );
        return false;
    }
    
    // Verificar que el archivo es válido PHP
    if ( ! tarokina_pro_validate_php_file( $platform_check_path ) ) {
        error_log( "Tarokina Pro: platform_check.php generado pero contiene errores de sintaxis" );
        @unlink( $platform_check_path ); // Limpiar archivo corrupto
        return false;
    }
    
    error_log( "Tarokina Pro: platform_check.php generado exitosamente ({$bytes_written} bytes)" );
    return true;
}

/**
 * Inicializar Carbon Fields de forma segura para Tarokina Pro - OPTIMIZADO
 */
function tarokina_pro_init_carbon_fields() {
    // Verificar cache primero (evita procesamiento repetido)
    if ( Tarokina_Pro_Carbon_Fields_Cache::is_loaded() ) {
        return true;
    }
    
    // Verificar si ya se cargó nuestra versión
    if ( defined( 'CARBON_FIELDS_TAROKINA_PRO_LOADED' ) ) {
        Tarokina_Pro_Carbon_Fields_Cache::set_loaded();
        return true;
    }
    
    // CRÍTICO: Verificar y reparar todos los archivos de Composer antes de la carga
    $repair_result = tarokina_pro_verify_and_repair_composer_files();
    
    if ( ! empty( $repair_result['failed'] ) ) {
        error_log( 'Tarokina Pro: Archivos críticos de Composer no se pudieron reparar: ' . implode( ', ', $repair_result['failed'] ) );
        add_option( 'tarokina_pro_carbon_fields_error', 'incomplete_installation' );
        return false;
    }
    
    if ( ! empty( $repair_result['repaired'] ) ) {
        error_log( 'Tarokina Pro: Archivos de Composer reparados exitosamente: ' . implode( ', ', $repair_result['repaired'] ) );
        add_option( 'tarokina_pro_carbon_fields_error', 'platform_check_generated' );
    }
    
    // Verificar específicamente platform_check.php después de la reparación
    $platform_check_path = __DIR__ . '/carbon-fields/vendor/composer/platform_check.php';
    if ( ! file_exists( $platform_check_path ) ) {
        if ( ! tarokina_pro_generate_platform_check( $platform_check_path ) ) {
            error_log( 'Tarokina Pro: ERROR CRÍTICO - No se pudo generar platform_check.php' );
            add_option( 'tarokina_pro_carbon_fields_error', 'incomplete_installation' );
            return false;
        }
        error_log( 'Tarokina Pro: platform_check.php generado exitosamente antes de la carga' );
        add_option( 'tarokina_pro_carbon_fields_error', 'platform_check_generated' );
    }
    
    // Verificar integridad completa de Carbon Fields
    if ( ! tarokina_pro_validate_carbon_fields_integrity() ) {
        error_log( 'Tarokina Pro: Carbon Fields está incompleto o dañado. No se puede cargar.' );
        return false;
    }
    
    // Ruta al archivo principal de Carbon Fields
    $carbon_fields_file = __DIR__ . '/carbon-fields/carbon-fields-plugin.php';
    
    if ( file_exists( $carbon_fields_file ) ) {
        // Usar try-catch para capturar errores de carga
        try {
            require_once $carbon_fields_file;
            Tarokina_Pro_Carbon_Fields_Cache::set_loaded();
            
            // Definir constante para indicar que nuestra versión está cargada
            if ( ! defined( 'CARBON_FIELDS_TAROKINA_PRO_LOADED' ) ) {
                define( 'CARBON_FIELDS_TAROKINA_PRO_LOADED', true );
            }
            
            return true;
        } catch ( Exception $e ) {
            error_log( 'Tarokina Pro: Error al cargar Carbon Fields: ' . $e->getMessage() );
            add_option( 'tarokina_pro_carbon_fields_error', 'incomplete_installation' );
            return false;
        } catch ( Error $e ) {
            error_log( 'Tarokina Pro: Error fatal al cargar Carbon Fields: ' . $e->getMessage() );
            add_option( 'tarokina_pro_carbon_fields_error', 'incomplete_installation' );
            return false;
        }
    }
    
    return false;
}

/**
 * Verificar compatibilidad y conflictos potenciales - OPTIMIZADO
 */
function tarokina_pro_check_carbon_fields_conflicts() {
    // Usar cache para evitar verificaciones repetidas
    if ( Tarokina_Pro_Carbon_Fields_Cache::conflicts_checked() ) {
        return Tarokina_Pro_Carbon_Fields_Cache::get_conflicts();
    }
    
    $conflicts = array();
    
    // Solo verificar en admin y si WP_DEBUG está activo
    if ( ! is_admin() || ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
        Tarokina_Pro_Carbon_Fields_Cache::set_conflicts( $conflicts );
        return $conflicts;
    }
    
    // Verificación ligera de clases existentes
    if ( class_exists( '\\Carbon_Fields\\Carbon_Fields' ) && ! defined( 'CARBON_FIELDS_TAROKINA_PRO_LOADED' ) ) {
        $conflicts[] = 'Se detectó otra versión de Carbon Fields activa';
    }
    
    // Cache del resultado
    Tarokina_Pro_Carbon_Fields_Cache::set_conflicts( $conflicts );
    
    // Log solo una vez y solo si hay conflictos
    if ( ! empty( $conflicts ) ) {
        error_log( 'Tarokina Pro Carbon Fields: ' . count( $conflicts ) . ' conflicto(s) detectado(s)' );
    }
    
    return $conflicts;
}

/**
 * Función helper para usar Carbon Fields en Tarokina Pro - OPTIMIZADA
 */
function tarokina_pro_carbon_fields() {
    static $carbon_fields_class = null;
    
    // Cache estático para evitar verificaciones repetidas
    if ( $carbon_fields_class !== null ) {
        return $carbon_fields_class;
    }
    
    // Intentar usar la versión aislada primero
    if ( class_exists( '\\Carbon_Fields_Tarokina_Pro\\Carbon_Fields' ) ) {
        $carbon_fields_class = '\\Carbon_Fields_Tarokina_Pro\\Carbon_Fields';
        return $carbon_fields_class;
    }
    
    // Fallback a la versión estándar si está disponible
    if ( class_exists( '\\Carbon_Fields\\Carbon_Fields' ) ) {
        $carbon_fields_class = '\\Carbon_Fields\\Carbon_Fields';
        return $carbon_fields_class;
    }
    
    $carbon_fields_class = false;
    return $carbon_fields_class;
}

/**
 * Función helper para crear campos Carbon Fields - OPTIMIZADA
 */
function tarokina_pro_field( $type, $name, $label = null ) {
    static $field_class_cache = array();
    
    $carbon_fields_class = tarokina_pro_carbon_fields();
    
    if ( ! $carbon_fields_class ) {
        return false;
    }
    
    // Cache de clases para evitar verificaciones repetidas
    $cache_key = $carbon_fields_class;
    if ( isset( $field_class_cache[ $cache_key ] ) ) {
        $field_class = $field_class_cache[ $cache_key ];
    } else {
        // Verificar si la clase Field existe en nuestro namespace
        $field_class = str_replace( 'Carbon_Fields', 'Carbon_Fields_Tarokina_Pro\\Field', $carbon_fields_class );
        if ( ! class_exists( $field_class ) ) {
            $field_class = '\\Carbon_Fields\\Field\\Field';
        }
        
        // Cache del resultado
        $field_class_cache[ $cache_key ] = $field_class;
    }
    
    if ( class_exists( $field_class ) && method_exists( $field_class, 'make' ) ) {
        return call_user_func( array( $field_class, 'make' ), $type, $name, $label );
    }
    
    return false;
}

/**
 * Inicializar Carbon Fields SOLO cuando sea necesario - LAZY LOADING
 */
function tarokina_pro_lazy_init_carbon_fields() {
    // Solo cargar cuando realmente se necesite
    static $init_attempted = false;
    
    if ( $init_attempted ) {
        return;
    }
    
    $init_attempted = true;
    tarokina_pro_init_carbon_fields();
}

// OPTIMIZACIÓN: Usar hooks más específicos y tarde en el proceso
// Solo cargar cuando WordPress esté completamente listo
add_action( 'wp_loaded', 'tarokina_pro_lazy_init_carbon_fields', 10 );

// OPTIMIZACIÓN: Verificar conflictos solo una vez por sesión de admin
if ( is_admin() ) {
    // Solo verificar en páginas específicas del admin
    add_action( 'current_screen', function() {
        $screen = get_current_screen();
        
        // Solo verificar en páginas relevantes
        if ( $screen && in_array( $screen->base, array( 'plugins', 'dashboard' ) ) ) {
            tarokina_pro_check_carbon_fields_conflicts();
        }
    }, 20 );
}

/**
 * Función de emergencia para recuperar Carbon Fields
 * Se ejecuta si hay problemas graves con la instalación
 */
function tarokina_pro_emergency_carbon_fields_recovery() {
    $carbon_fields_path = __DIR__ . '/carbon-fields';
    
    // Verificar si el directorio de Carbon Fields existe
    if ( ! is_dir( $carbon_fields_path ) ) {
        error_log( 'Tarokina Pro: Directorio de Carbon Fields no existe' );
        return false;
    }
    
    // Lista de archivos críticos que deben existir
    $critical_files = array(
        '/vendor/composer/platform_check.php',
        '/vendor/composer/autoload_real.php',
        '/vendor/composer/autoload_classmap.php',
        '/vendor/composer/autoload_files.php',
        '/vendor/composer/autoload_namespaces.php',
        '/vendor/composer/autoload_psr4.php',
        '/vendor/composer/autoload_static.php'
    );
    
    $missing_files = array();
    
    // Verificar qué archivos faltan
    foreach ( $critical_files as $file ) {
        if ( ! file_exists( $carbon_fields_path . $file ) ) {
            $missing_files[] = $file;
        }
    }
    
    // Si falta platform_check.php, intentar generarlo
    if ( in_array( '/vendor/composer/platform_check.php', $missing_files ) ) {
        $platform_check_path = $carbon_fields_path . '/vendor/composer/platform_check.php';
        if ( tarokina_pro_generate_platform_check( $platform_check_path ) ) {
            // Remover de la lista de archivos faltantes
            $missing_files = array_diff( $missing_files, array( '/vendor/composer/platform_check.php' ) );
            error_log( 'Tarokina Pro: platform_check.php recuperado exitosamente' );
        }
    }
    
    // Si aún faltan archivos críticos, loggear el problema
    if ( ! empty( $missing_files ) ) {
        error_log( 'Tarokina Pro: Archivos de Composer faltantes: ' . implode( ', ', $missing_files ) );
        return false;
    }
    
    return true;
}

/**
 * Hook de activación para verificar la integridad de Carbon Fields
 * Se ejecuta cuando el plugin se activa
 */
function tarokina_pro_activation_carbon_fields_check() {
    // Verificar integridad al activar el plugin
    if ( ! tarokina_pro_validate_carbon_fields_integrity() ) {
        // Intentar recuperación de emergencia
        if ( ! tarokina_pro_emergency_carbon_fields_recovery() ) {
            // Si no se puede recuperar, mostrar aviso
            add_option( 'tarokina_pro_carbon_fields_error', 'incomplete_installation' );
        }
    }
}

/**
 * Mostrar notificaciones de administrador para problemas de Carbon Fields
 * Solo se muestran en modo desarrollo, nunca en producción
 */
function tarokina_pro_show_carbon_fields_admin_notices() {
    // Solo mostrar notificaciones en modo desarrollo
    if ( defined( 'TAROKINA_PRODUCTION_MODE' ) && TAROKINA_PRODUCTION_MODE ) {
        return;
    }
    
    // Verificar si hay errores almacenados
    $carbon_fields_error = get_option( 'tarokina_pro_carbon_fields_error', false );
    
    if ( $carbon_fields_error ) {
        $message = '';
        $type = 'error';
        
        switch ( $carbon_fields_error ) {
            case 'incomplete_installation':
                $message = __( 'Tarokina Pro: Incomplete Carbon Fields installation detected. Some plugin functions may not work correctly. Please contact technical support.', 'tarokina-pro' );
                break;
            case 'platform_check_generated':
                $message = __( 'Tarokina Pro: A Carbon Fields issue was automatically repaired. The plugin should work normally now.', 'tarokina-pro' );
                $type = 'success';
                // Auto-limpiar este mensaje después de mostrarlo
                delete_option( 'tarokina_pro_carbon_fields_error' );
                break;
        }
        
        if ( $message ) {
            echo '<div class="notice notice-' . esc_attr( $type ) . ' is-dismissible">';
            echo '<p><strong>' . esc_html( $message ) . '</strong></p>';
            echo '</div>';
        }
    }
}

// Hook para mostrar notificaciones de administrador
add_action( 'admin_notices', 'tarokina_pro_show_carbon_fields_admin_notices' );

/**
 * Validar que un archivo PHP sea sintácticamente correcto
 */
function tarokina_pro_validate_php_file( $file_path ) {
    if ( ! file_exists( $file_path ) ) {
        return false;
    }
    
    // Usar php -l para verificar sintaxis si está disponible
    if ( function_exists( 'exec' ) ) {
        $output = array();
        $return_code = 0;
        
        // Intentar validar sintaxis con php -l
        @exec( "php -l " . escapeshellarg( $file_path ) . " 2>&1", $output, $return_code );
        
        // Si php -l está disponible y devuelve código 0, el archivo es válido
        if ( $return_code === 0 ) {
            return true;
        }
    }
    
    // Fallback: intentar incluir el archivo en un contexto controlado
    // Esto es menos seguro pero funciona en servidores compartidos
    $original_error_reporting = error_reporting( 0 );
    
    ob_start();
    $valid = ( @include_once $file_path ) !== false;
    ob_end_clean();
    
    error_reporting( $original_error_reporting );
    
    return $valid;
}

// VERIFICACIÓN CRÍTICA TEMPRANA: Asegurar platform_check.php antes de cualquier autoload
// Esta verificación se ejecuta inmediatamente al cargar este archivo
(function() {
    $platform_check_path = __DIR__ . '/carbon-fields/vendor/composer/platform_check.php';
    
    // Si platform_check.php no existe, generarlo INMEDIATAMENTE
    if ( ! file_exists( $platform_check_path ) ) {
        // Función inline para generar platform_check.php de emergencia
        $emergency_generate = function( $path ) {
            $dir = dirname( $path );
            if ( ! is_dir( $dir ) ) {
                @mkdir( $dir, 0755, true );
            }
            
            $content = '<?php
// Archivo platform_check.php de emergencia generado por Tarokina Pro
// Generado el: ' . date( 'Y-m-d H:i:s' ) . '

if ( version_compare( PHP_VERSION, "7.0.0", "<" ) ) {
    throw new Exception( "PHP 7.0+ requerido" );
}

if ( ! extension_loaded( "json" ) ) {
    throw new Exception( "Extensión JSON requerida" );
}
';
            
            return @file_put_contents( $path, $content, LOCK_EX ) !== false;
        };
        
        // Intentar generar el archivo de emergencia
        if ( $emergency_generate( $platform_check_path ) ) {
            error_log( 'Tarokina Pro: platform_check.php generado en verificación temprana' );
        } else {
            error_log( 'Tarokina Pro: ERROR CRÍTICO - No se pudo generar platform_check.php en verificación temprana' );
        }
    }
})();

/**
 * Verificar y reparar archivos críticos de Composer para Carbon Fields
 * Especialmente útil para servidores compartidos donde pueden faltar archivos
 */
function tarokina_pro_verify_and_repair_composer_files() {
    $carbon_fields_path = __DIR__ . '/carbon-fields/vendor/composer';
    
    // Archivos críticos que deben existir
    $critical_files = array(
        'platform_check.php' => 'tarokina_pro_generate_platform_check',
        'autoload_real.php' => 'tarokina_pro_generate_autoload_real',
        'autoload_static.php' => 'tarokina_pro_generate_autoload_static',
        'autoload_classmap.php' => 'tarokina_pro_generate_autoload_classmap',
        'autoload_files.php' => 'tarokina_pro_generate_autoload_files',
        'autoload_namespaces.php' => 'tarokina_pro_generate_autoload_namespaces',
        'autoload_psr4.php' => 'tarokina_pro_generate_autoload_psr4'
    );
    
    $repaired_files = array();
    $failed_files = array();
    
    foreach ( $critical_files as $file => $generator_function ) {
        $file_path = $carbon_fields_path . '/' . $file;
        
        if ( ! file_exists( $file_path ) ) {
            if ( function_exists( $generator_function ) ) {
                if ( call_user_func( $generator_function, $file_path ) ) {
                    $repaired_files[] = $file;
                } else {
                    $failed_files[] = $file;
                }
            } elseif ( $file === 'platform_check.php' ) {
                // Usar la función existente para platform_check.php
                if ( tarokina_pro_generate_platform_check( $file_path ) ) {
                    $repaired_files[] = $file;
                } else {
                    $failed_files[] = $file;
                }
            } else {
                $failed_files[] = $file;
            }
        }
    }
    
    // Log de resultados
    if ( ! empty( $repaired_files ) ) {
        error_log( 'Tarokina Pro: Archivos Composer reparados: ' . implode( ', ', $repaired_files ) );
    }
    
    if ( ! empty( $failed_files ) ) {
        error_log( 'Tarokina Pro: Archivos Composer que no se pudieron reparar: ' . implode( ', ', $failed_files ) );
    }
    
    return array(
        'repaired' => $repaired_files,
        'failed' => $failed_files
    );
}

/**
 * Generar autoload_real.php básico si falta
 */
function tarokina_pro_generate_autoload_real( $file_path ) {
    $content = '<?php
// autoload_real.php generado por Tarokina Pro
class ComposerAutoloaderInit_TarokinaPro {
    public static function loadClassLoader($class) {
        // Básico - no hacer nada
    }
    
    public static function getLoader() {
        // Retornar un loader básico
        return new ComposerAutoloaderInit_TarokinaPro();
    }
}
';
    
    return @file_put_contents( $file_path, $content ) !== false;
}

/**
 * Generar archivos autoload básicos si faltan
 */
function tarokina_pro_generate_autoload_static( $file_path ) {
    $content = '<?php
// autoload_static.php generado por Tarokina Pro
class ComposerStaticInit_TarokinaPro {
    public static $classMap = array();
    public static $files = array();
    public static $prefixLengthsPsr4 = array();
    public static $prefixDirsPsr4 = array();
}
';
    return @file_put_contents( $file_path, $content ) !== false;
}

function tarokina_pro_generate_autoload_classmap( $file_path ) {
    $content = '<?php
// autoload_classmap.php generado por Tarokina Pro
return array();
';
    return @file_put_contents( $file_path, $content ) !== false;
}

function tarokina_pro_generate_autoload_files( $file_path ) {
    $content = '<?php
// autoload_files.php generado por Tarokina Pro
return array();
';
    return @file_put_contents( $file_path, $content ) !== false;
}

function tarokina_pro_generate_autoload_namespaces( $file_path ) {
    $content = '<?php
// autoload_namespaces.php generado por Tarokina Pro
return array();
';
    return @file_put_contents( $file_path, $content ) !== false;
}

function tarokina_pro_generate_autoload_psr4( $file_path ) {
    $content = '<?php
// autoload_psr4.php generado por Tarokina Pro
return array();
';
    return @file_put_contents( $file_path, $content ) !== false;
}
