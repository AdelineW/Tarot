<?php
/**
 * Controlador de actualizaciones automáticas para plugins Tarokina
 * 
 * Este archivo proporciona control granular sobre las actualizaciones automáticas,
 * permitiendo deshabilitarlas por completo si es necesario para compatibilidad
 * con servidores que tienen políticas restrictivas.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Tarokina_Auto_Update_Controller {
    
    // Configuración: establecer en true para DESHABILITAR completamente las auto-actualizaciones
    const DISABLE_AUTO_UPDATES = true;
    
    // Lista de plugins Tarokina que deben ser controlados
    private static $tarokina_plugins = array(
        'tarokina-pro/tarokina-pro.php',
        'tarokki-classic_spreads/tarokki-classic_spreads.php',
        'tarokki-custom_spreads/tarokki-custom_spreads.php',
        'tarokki-edd_restriction_tarokina/tarokki-edd_restriction_tarokina.php'
    );
    
    /**
     * Inicializar el controlador
     */
    public static function init() {
        if ( self::DISABLE_AUTO_UPDATES ) {
            self::disable_auto_updates();
        }
        
        // Agregar página de configuración en admin (independiente de dev-tools)
        add_action( 'admin_menu', array( __CLASS__, 'add_admin_page' ) );
    }
    
    /**
     * Deshabilitar actualizaciones automáticas para plugins Tarokina
     */
    private static function disable_auto_updates() {
        // 1. Prevenir que WordPress añada nuestros plugins a la lista de auto-updates
        add_filter( 'auto_update_plugin', array( __CLASS__, 'disable_plugin_auto_update' ), 10, 2 );
        
        // 2. Remover la opción de auto-update de la UI
        add_filter( 'plugin_auto_update_setting_html', array( __CLASS__, 'remove_auto_update_ui' ), 20, 3 );
        
        // 3. Bloquear intentos de habilitar auto-updates vía admin
        add_action( 'wp_ajax_toggle-auto-update', array( __CLASS__, 'block_auto_update_toggle' ), 1 );
        
        // 4. Remover de la lista de plugins con auto-update habilitado
        add_action( 'admin_init', array( __CLASS__, 'ensure_auto_updates_disabled' ) );
        
        // 5. Mostrar mensaje explicativo en la página de plugins
        add_action( 'admin_notices', array( __CLASS__, 'show_auto_update_disabled_notice' ) );
    }
    
    /**
     * Prevenir auto-actualizaciones para plugins específicos
     */
    public static function disable_plugin_auto_update( $update, $item ) {
        if ( isset( $item->plugin ) && in_array( $item->plugin, self::$tarokina_plugins ) ) {
            return false; // Nunca auto-actualizar
        }
        return $update;
    }
    
    /**
     * Remover la UI de auto-update para nuestros plugins
     * No mostrar ningún mensaje - simplemente eliminar el texto de auto-update
     */
    public static function remove_auto_update_ui( $html, $plugin_file, $plugin_data ) {
        if ( in_array( $plugin_file, self::$tarokina_plugins ) ) {
            // Devolver cadena vacía para eliminar completamente cualquier mensaje
            return '';
        }
        return $html;
    }
    
    /**
     * Bloquear intentos de toggle de auto-update
     */
    public static function block_auto_update_toggle() {
        if ( isset( $_GET['plugin'] ) && in_array( $_GET['plugin'], self::$tarokina_plugins ) ) {
            wp_die( 
                __( 'Automatic updates are disabled for Tarokina plugins. Please update manually.', 'tarokina-pro' ),
                __( 'Auto-updates Disabled', 'tarokina-pro' ),
                array( 'back_link' => true )
            );
        }
    }
    
    /**
     * Asegurar que nuestros plugins no estén en la lista de auto-updates
     */
    public static function ensure_auto_updates_disabled() {
        $auto_update_plugins = (array) get_site_option( 'auto_update_plugins', array() );
        $modified = false;
        
        foreach ( self::$tarokina_plugins as $plugin ) {
            if ( in_array( $plugin, $auto_update_plugins ) ) {
                $auto_update_plugins = array_diff( $auto_update_plugins, array( $plugin ) );
                $modified = true;
            }
        }
        
        if ( $modified ) {
            update_site_option( 'auto_update_plugins', $auto_update_plugins );
        }
    }
    
    /**
     * Mostrar notice sobre auto-updates deshabilitadas
     * Solo funciona en modo desarrollo
     */
    public static function show_auto_update_disabled_notice() {
        // Solo mostrar avisos en modo desarrollo
        if ( defined( 'TAROKINA_PRODUCTION_MODE' ) && TAROKINA_PRODUCTION_MODE ) {
            return;
        }
        
        $screen = get_current_screen();
        
        if ( $screen && $screen->id === 'plugins' ) {
            echo '<div class="notice notice-info">';
            echo '<p><strong>' . __( 'Tarokina Plugins:', 'tarokina-pro' ) . '</strong> ';
            echo __( 'Automatic updates are disabled for security and compatibility. Updates must be performed manually.', 'tarokina-pro' );
            echo ' <a href="' . admin_url( 'admin.php?page=tarokina-auto-update-settings' ) . '">' . __( 'Settings', 'tarokina-pro' ) . '</a>';
            echo '</p>';
            echo '</div>';
        }
    }
    
    /**
     * Agregar página de configuración
     */
    public static function add_admin_page() {
        // Solo agregar la página si estamos en el contexto correcto
        if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        // Usar un parent válido en lugar de null para evitar problemas con plugin_basename()
        add_submenu_page(
            'options-general.php', // Parent válido (Settings)
            __( 'Tarokina Auto-Update Settings', 'tarokina-pro' ),
            __( 'Auto-Update Settings', 'tarokina-pro' ),
            'manage_options',
            'tarokina-auto-update-settings',
            array( __CLASS__, 'admin_page' )
        );
    }
    
    /**
     * Página de administración
     */
    public static function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'Tarokina Auto-Update Settings', 'tarokina-pro' ); ?></h1>
            
            <div class="notice notice-warning">
                <p><strong><?php _e( 'Important:', 'tarokina-pro' ); ?></strong> 
                <?php _e( 'Auto-updates are currently DISABLED for all Tarokina plugins.', 'tarokina-pro' ); ?></p>
            </div>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e( 'Current Status', 'tarokina-pro' ); ?></th>
                    <td>
                        <span class="dashicons dashicons-dismiss" style="color: #d63638;"></span>
                        <?php _e( 'Automatic Updates: DISABLED', 'tarokina-pro' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Reason', 'tarokina-pro' ); ?></th>
                    <td><?php _e( 'Disabled for compatibility with servers that restrict automatic updates.', 'tarokina-pro' ); ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Update Method', 'tarokina-pro' ); ?></th>
                    <td><?php _e( 'Manual updates only via WordPress admin panel.', 'tarokina-pro' ); ?></td>
                </tr>
            </table>
            
            <h2><?php _e( 'Controlled Plugins', 'tarokina-pro' ); ?></h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e( 'Plugin', 'tarokina-pro' ); ?></th>
                        <th><?php _e( 'Status', 'tarokina-pro' ); ?></th>
                        <th><?php _e( 'Auto-Update', 'tarokina-pro' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $all_plugins = get_plugins();
                    foreach ( self::$tarokina_plugins as $plugin_file ) {
                        if ( isset( $all_plugins[$plugin_file] ) ) {
                            $plugin = $all_plugins[$plugin_file];
                            $is_active = is_plugin_active( $plugin_file );
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html( $plugin['Name'] ); ?></strong><br>
                                    <small><?php echo esc_html( $plugin_file ); ?></small>
                                </td>
                                <td>
                                    <?php if ( $is_active ) : ?>
                                        <span class="dashicons dashicons-yes-alt" style="color: #00a32a;"></span>
                                        <?php _e( 'Active', 'tarokina-pro' ); ?>
                                    <?php else : ?>
                                        <span class="dashicons dashicons-minus" style="color: #dba617;"></span>
                                        <?php _e( 'Inactive', 'tarokina-pro' ); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="dashicons dashicons-dismiss" style="color: #d63638;"></span>
                                    <?php _e( 'Disabled', 'tarokina-pro' ); ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            
            <h2><?php _e( 'How to Update', 'tarokina-pro' ); ?></h2>
            <ol>
                <li><?php _e( 'Go to Plugins → Installed Plugins', 'tarokina-pro' ); ?></li>
                <li><?php _e( 'Look for update notifications', 'tarokina-pro' ); ?></li>
                <li><?php _e( 'Click "Update now" for each plugin individually', 'tarokina-pro' ); ?></li>
                <li><?php _e( 'Or use the bulk update feature for multiple plugins', 'tarokina-pro' ); ?></li>
            </ol>
            
            <div class="notice notice-info">
                <p><strong><?php _e( 'Note:', 'tarokina-pro' ); ?></strong> 
                <?php _e( 'This setting is configured in the code and cannot be changed from this interface for security reasons.', 'tarokina-pro' ); ?></p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Obtener el estado de auto-updates para un plugin específico
     */
    public static function is_auto_update_enabled_for_plugin( $plugin_file ) {
        if ( self::DISABLE_AUTO_UPDATES && in_array( $plugin_file, self::$tarokina_plugins ) ) {
            return false;
        }
        
        $auto_update_plugins = (array) get_site_option( 'auto_update_plugins', array() );
        return in_array( $plugin_file, $auto_update_plugins );
    }
}
