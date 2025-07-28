<?php
/**
 * Monitor de Rendimiento para Carbon Fields - Tarokina Pro
 * 
 * Este archivo ayuda a monitorear el impacto en rendimiento de Carbon Fields
 */

// Prevenir acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Clase para monitorear rendimiento
 */
class Tarokina_Pro_Performance_Monitor {
    private static $start_time = null;
    private static $memory_start = null;
    private static $enabled = false;
    
    /**
     * Inicializar monitoreo (solo si WP_DEBUG está activo)
     */
    public static function init() {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
            self::$enabled = true;
            self::$start_time = microtime( true );
            self::$memory_start = memory_get_usage();
            
            // Hook para medir al final
            add_action( 'wp_footer', array( __CLASS__, 'log_performance' ), 999 );
            add_action( 'admin_footer', array( __CLASS__, 'log_performance' ), 999 );
        }
    }
    
    /**
     * Registrar punto de medición
     */
    public static function mark( $label ) {
        if ( ! self::$enabled ) {
            return;
        }
        
        $current_time = microtime( true );
        $current_memory = memory_get_usage();
        
        $time_diff = $current_time - self::$start_time;
        $memory_diff = $current_memory - self::$memory_start;
        
        error_log( sprintf(
            'Tarokina Pro Performance [%s]: %.4fs, %s memory',
            $label,
            $time_diff,
            size_format( $memory_diff )
        ) );
    }
    
    /**
     * Log final de rendimiento
     */
    public static function log_performance() {
        if ( ! self::$enabled ) {
            return;
        }
        
        $end_time = microtime( true );
        $end_memory = memory_get_usage();
        
        $total_time = $end_time - self::$start_time;
        $total_memory = $end_memory - self::$memory_start;
        
        $peak_memory = memory_get_peak_usage();
        
        error_log( sprintf(
            'Tarokina Pro Performance FINAL: %.4fs total time, %s memory used, %s peak memory',
            $total_time,
            size_format( $total_memory ),
            size_format( $peak_memory )
        ) );
    }
    
    /**
     * Verificar si Carbon Fields está impactando el rendimiento
     */
    public static function check_carbon_fields_impact() {
        if ( ! self::$enabled ) {
            return;
        }
        
        $carbon_fields_loaded = defined( 'CARBON_FIELDS_TAROKINA_PRO_LOADED' ) || class_exists( '\\Carbon_Fields\\Carbon_Fields' );
        
        if ( $carbon_fields_loaded ) {
            self::mark( 'Carbon Fields Loaded' );
        } else {
            self::mark( 'Carbon Fields NOT Loaded' );
        }
    }
}

// Inicializar monitoreo solo en desarrollo
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    Tarokina_Pro_Performance_Monitor::init();
    
    // Marcar puntos clave
    add_action( 'init', array( 'Tarokina_Pro_Performance_Monitor', 'check_carbon_fields_impact' ), 999 );
}

/**
 * Función helper para desactivar temporalmente Carbon Fields si hay problemas de rendimiento
 */
function tarokina_pro_disable_carbon_fields_temporarily() {
    // Solo en casos extremos, crear un archivo .disable
    $disable_file = __DIR__ . '/.carbon-fields-disabled';
    
    if ( file_exists( $disable_file ) ) {
        return true; // Carbon Fields desactivado
    }
    
    return false;
}

/**
 * Función para crear archivo de desactivación temporal
 */
function tarokina_pro_emergency_disable_carbon_fields() {
    $disable_file = __DIR__ . '/.carbon-fields-disabled';
    
    file_put_contents( $disable_file, date( 'Y-m-d H:i:s' ) . ' - Carbon Fields desactivado por problemas de rendimiento' );
    
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log( 'Tarokina Pro: Carbon Fields desactivado temporalmente por problemas de rendimiento' );
    }
}

/**
 * Función para reactivar Carbon Fields
 */
function tarokina_pro_reenable_carbon_fields() {
    $disable_file = __DIR__ . '/.carbon-fields-disabled';
    
    if ( file_exists( $disable_file ) ) {
        unlink( $disable_file );
        
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'Tarokina Pro: Carbon Fields reactivado' );
        }
    }
}
