<?php
/**
 * Verificador ligero de auto-updates para producción
 * 
 * Esta clase proporciona verificación básica del estado de auto-updates
 * sin las herramientas de desarrollo pesadas.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Tarokina_Auto_Update_Status {
    
    private static $tarokina_plugins = array(
        'tarokina-pro/tarokina-pro.php',
        'tarokki-classic_spreads/tarokki-classic_spreads.php',
        'tarokki-custom_spreads/tarokki-custom_spreads.php',
        'tarokki-edd_restriction_tarokina/tarokki-edd_restriction_tarokina.php'
    );
    
    /**
     * Verificar si el sistema de control está funcionando
     */
    public static function is_system_working() {
        // 1. ¿Existe el controlador?
        if ( ! class_exists( 'Tarokina_Auto_Update_Controller' ) ) {
            return false;
        }
        
        // 2. ¿Están deshabilitadas las auto-updates?
        if ( ! Tarokina_Auto_Update_Controller::DISABLE_AUTO_UPDATES ) {
            return false;
        }
        
        // 3. ¿Están nuestros plugins protegidos?
        $auto_update_plugins = (array) get_site_option( 'auto_update_plugins', array() );
        foreach ( self::$tarokina_plugins as $plugin ) {
            if ( in_array( $plugin, $auto_update_plugins ) ) {
                return false; // Encontramos un plugin en la lista de auto-updates
            }
        }
        
        return true;
    }
    
    /**
     * Obtener estado resumido del sistema
     */
    public static function get_status_summary() {
        $status = array(
            'system_working' => self::is_system_working(),
            'controller_loaded' => class_exists( 'Tarokina_Auto_Update_Controller' ),
            'auto_updates_disabled' => false,
            'protected_plugins' => 0,
            'total_plugins' => count( self::$tarokina_plugins )
        );
        
        if ( $status['controller_loaded'] ) {
            $status['auto_updates_disabled'] = Tarokina_Auto_Update_Controller::DISABLE_AUTO_UPDATES;
        }
        
        // Contar plugins protegidos
        $auto_update_plugins = (array) get_site_option( 'auto_update_plugins', array() );
        $all_plugins = get_plugins();
        
        foreach ( self::$tarokina_plugins as $plugin ) {
            if ( isset( $all_plugins[$plugin] ) && ! in_array( $plugin, $auto_update_plugins ) ) {
                $status['protected_plugins']++;
            }
        }
        
        return $status;
    }
    
    /**
     * Mostrar notice en admin si hay problemas
     * Solo funciona en modo desarrollo
     */
    public static function admin_notice() {
        // Solo mostrar avisos en modo desarrollo
        if ( defined( 'TAROKINA_PRODUCTION_MODE' ) && TAROKINA_PRODUCTION_MODE ) {
            return;
        }
        
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        $status = self::get_status_summary();
        
        if ( ! $status['system_working'] ) {
            echo '<div class="notice notice-error">';
            echo '<p><strong>Tarokina Auto-Updates:</strong> ';
            echo __( 'The auto-update control system is not working correctly.', 'tarokina-pro' );
            
            if ( ! TAROKINA_PRODUCTION_MODE ) {
                echo ' <a href="' . admin_url( 'tools.php?page=tarokina-verify-auto-updates' ) . '">' . __( 'Diagnose', 'tarokina-pro' ) . '</a>';
            }
            
            echo '</p>';
            echo '</div>';
        } elseif ( $status['protected_plugins'] < $status['total_plugins'] ) {
            echo '<div class="notice notice-warning">';
            echo '<p><strong>Tarokina Auto-Updates:</strong> ';
            echo sprintf( __( 'Only %d of %d plugins are protected against auto-updates.', 'tarokina-pro' ), 
                         $status['protected_plugins'], $status['total_plugins'] );
            echo '</p>';
            echo '</div>';
        }
    }
    
    /**
     * Inicializar verificaciones de producción
     */
    public static function init() {
        // Solo mostrar notices críticos en producción
        if ( is_admin() ) {
            add_action( 'admin_notices', array( __CLASS__, 'admin_notice' ) );
        }
    }
}

// Inicializar siempre (producción y desarrollo)
Tarokina_Auto_Update_Status::init();
