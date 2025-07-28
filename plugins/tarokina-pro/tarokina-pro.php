<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Tarokina Pro
 * Plugin URI:        https://arnelio.com/downloads/tarokina-pro/
 * Description:       New Tarot plugin. Intuitive and easy to use. Provides accurate tarot readings on WordPress.
 * Version:           2.15.2
 * Author:            Arnelio
 * Author URI:        https://arnelio.com/
 * License:           GNU General Public License v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tarokina-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Cargar Carbon Fields de forma segura (evitando conflictos)
// VERIFICACIÓN PREVIA: Asegurar que platform_check.php existe antes de cargar Carbon Fields
$carbon_platform_check = plugin_dir_path( __FILE__ ) . 'lib/carbon-fields/vendor/composer/platform_check.php';
if ( ! file_exists( $carbon_platform_check ) ) {
    // Intentar crear el directorio si no existe
    $platform_dir = dirname( $carbon_platform_check );
    if ( ! is_dir( $platform_dir ) ) {
        wp_mkdir_p( $platform_dir );
    }
    
    // Generar platform_check.php básico de emergencia
    $emergency_content = '<?php
// Archivo platform_check.php de emergencia - Tarokina Pro
if ( version_compare( PHP_VERSION, "7.0.0", "<" ) ) {
    throw new Exception( "PHP 7.0+ requerido para Tarokina Pro" );
}
if ( ! extension_loaded( "json" ) ) {
    throw new Exception( "Extensión JSON requerida para Carbon Fields" );
}
';
    
    if ( @file_put_contents( $carbon_platform_check, $emergency_content ) ) {
        error_log( 'Tarokina Pro: platform_check.php generado en inicialización del plugin' );
    } else {
        error_log( 'Tarokina Pro: ADVERTENCIA - No se pudo generar platform_check.php' );
    }
}

require_once plugin_dir_path( __FILE__ ) . 'lib/carbon-fields-loader.php';



// Version of the plugin
define( 'TAROKKINA_PRO_VERSION', '2.15.2' );

// bails if PHP version is lower than required
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
	echo '<div class="notice notice-error is-dismissible">
	<img style="width:57px;height:27px;margin-right:8px;float:left;margin-top:4px;" src="'.esc_url(plugin_dir_url( __FILE__ )).'img/icon_double.svg">
	<p><strong>Tarokina Pro - </strong>Does Not Work with your version of PHP. Minimum required php version is 7.0 &nbsp;&nbsp;<a href="https://wordpress.org/support/update-php/">learn more about updating PHP</a></p></div>';
    return;
}

// Cambiar a true para PRODUCTION, false para DEV
define('TAROKINA_PRODUCTION_MODE', true);
define('TAROKINA_URL_DEV', 'https://stg-arnelio-tarots.kinsta.cloud');

// Nueva lógica de dominios:
// - _change_license_domain: alterna entre dominios de producción
// - TAROKINA_PRODUCTION_MODE: controla si usar producción o desarrollo
$tkina_change_domain_production = get_option('_change_license_domain', '');

// Dominio: Producción dinámica (alterna según _change_license_domain)
$dominio_produccion = ($tkina_change_domain_production == 'yes') 
    ? 'https://arnelio.com' 
    : 'https://edd-license-proxy.service-dd4.workers.dev';

// Dominio2: Desarrollo fijo
$dominio_desarrollo = TAROKINA_URL_DEV;

if (!defined('TKINA_TAROKINA_LICENSES')) {
    define('TKINA_TAROKINA_LICENSES', [
        'lic_tarokina_con' => [
            'license_name' => 'tarokina_mycard_l',
            'name_option' => 'tarokina_pro_license',
            'dominio' => $dominio_produccion,  // Producción dinámica
            'dominio2' => $dominio_desarrollo, // Desarrollo fijo
            'id_product' => 16,
            'product_name' => 'Tarokina Pro',
            'version' => TAROKKINA_PRO_VERSION
        ],
    ]);
}

define( 'TAROKINA__FILE__', __FILE__ );
define( 'TAROKINA_PLUGIN_BASE', plugin_basename( TAROKINA__FILE__ ) );
define( 'TAROKINA_PATH', plugin_dir_path( TAROKINA__FILE__ ) );
define( 'TAROKINA_URL', plugins_url( '/', TAROKINA__FILE__ ) );

define( 'TAROKINA_ADMIN_PATH', plugin_dir_path( TAROKINA__FILE__ ) . 'admin/' );
define( 'TAROKINA_ADMIN_URL', TAROKINA_URL . 'admin/' );
define( 'TAROKINA_LIB_PATH', TAROKINA_PATH . 'lib/' );
define( 'TAROKINA_LIB_URL', TAROKINA_URL . 'lib/' );
define( 'TAROKINA_PUBLIC_PATH', TAROKINA_PATH . 'public/' );
define( 'TAROKINA_PUBLIC_URL', TAROKINA_URL . 'public/' );
define( 'TAROKINA_TAROTS_PATH', TAROKINA_PATH . 'tarots/' );
define( 'TAROKINA_TAROTS_URL', TAROKINA_URL . 'tarots/' );

// Commented out to fix early translation loading issue
// esc_html__('Change the order of cards.','tarokina-pro');
// esc_html__('order','tarokina-pro');
// esc_html__('Invalid license','tarokina-pro');
// esc_html__('Deactivated plugin','tarokina-pro');

define( 'TKNA_LIC_PAGE', 'tarokina_pro_license' );




/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tarokkina_pro-activator.php
 */
function TkNaP_activate_tarokkina_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tarokkina_pro-activator.php';
	Tarokkina_pro_Activator::TkNaP_activate();
	
	// Verificar integridad de Carbon Fields al activar
	if ( function_exists( 'tarokina_pro_activation_carbon_fields_check' ) ) {
		tarokina_pro_activation_carbon_fields_check();
	}
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tarokkina_pro-deactivator.php
 */
function TkNaP_deactivate_tarokkina_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tarokkina_pro-deactivator.php';
	Tarokkina_pro_Deactivator::TkNaP_deactivate();
}

register_activation_hook( __FILE__, 'TkNaP_activate_tarokkina_pro' );
register_deactivation_hook( __FILE__, 'TkNaP_deactivate_tarokkina_pro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tarokkina_pro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function TkNaP_run_tarokkina_pro() {

	$plugin = new Tarokkina_pro();
	$plugin->run();

}
TkNaP_run_tarokkina_pro();

// Cargar controlador de auto-updates (crítico para seguridad - siempre activo)
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lib/auto-updates/auto-update-controller.php' ) ) {
	include_once plugin_dir_path( __FILE__ ) . 'lib/auto-updates/auto-update-controller.php';
	if ( class_exists( 'Tarokina_Auto_Update_Controller' ) ) {
		// Inicializar cuando WordPress esté listo para evitar errores deprecated
		add_action( 'init', array( 'Tarokina_Auto_Update_Controller', 'init' ), 20 );
	}
}

// Cargar verificador de estado de auto-updates (siempre activo)
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lib/auto-updates/auto-update-status.php' ) ) {
	include_once plugin_dir_path( __FILE__ ) . 'lib/auto-updates/auto-update-status.php';
}

// Cargar dev-tools loader para activar el menú admin (opcional)
if ( is_admin() && file_exists( plugin_dir_path( __FILE__ ) . 'dev-tools/loader.php' ) ) {
	include_once plugin_dir_path( __FILE__ ) . 'dev-tools/loader.php';
}
