<?php
/**
 * Plugin Name: Carbon Fields (Tarokina Pro Version)
 * Description: WordPress developer-friendly custom fields for post types, taxonomy terms, users, comments, widgets, options, navigation menus and more.
 * Version: 3.6.5
 * Author: htmlburger
 * Author URI: https://htmlburger.com/
 * Plugin URI: http://carbonfields.net/
 * License: GPL2
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Text Domain: carbon-fields-tarokina-pro
 * Domain Path: /languages
 */

// Evitar carga si Carbon Fields ya está cargado globalmente
if ( defined( 'CARBON_FIELDS_TAROKINA_PRO_LOADED' ) ) {
	return;
}

define( 'CARBON_FIELDS_TAROKINA_PRO_LOADED', true );
define( 'Carbon_Fields_Tarokina_Pro\PLUGIN_FILE', __FILE__ );
define( 'Carbon_Fields_Tarokina_Pro\RELATIVE_PLUGIN_FILE', basename( dirname( \Carbon_Fields_Tarokina_Pro\PLUGIN_FILE ) ) . '/' . basename( \Carbon_Fields_Tarokina_Pro\PLUGIN_FILE ) );

// Hook único para evitar conflictos con otras versiones - OPTIMIZADO
add_action( 'after_setup_theme', 'carbon_fields_boot_tarokina_pro', 15 ); // Prioridad más baja
function carbon_fields_boot_tarokina_pro() {
	// Cache estático para evitar múltiples ejecuciones
	static $already_booted = false;
	if ( $already_booted ) {
		return;
	}
	$already_booted = true;
	
	// Verificar que no haya otra instancia ya cargada
	if ( class_exists( '\\Carbon_Fields\\Carbon_Fields' ) ) {
		// Si existe otra versión, marcar como cargado y salir
		if ( ! defined( 'CARBON_FIELDS_TAROKINA_PRO_LOADED' ) ) {
			define( 'CARBON_FIELDS_TAROKINA_PRO_LOADED', true );
		}
		return;
	}
	
	// Verificar que el autoloader existe antes de intentar cargarlo
	$autoload_file = __DIR__ . '/vendor/autoload.php';
	if ( ! file_exists( $autoload_file ) ) {
		error_log( 'Tarokina Pro: vendor/autoload.php no existe en Carbon Fields' );
		return;
	}
	
	// Cargar versión estándar solo si no hay conflictos
	try {
		require( $autoload_file );
		
		if ( class_exists( '\\Carbon_Fields\\Carbon_Fields' ) ) {
			\Carbon_Fields\Carbon_Fields::boot();
		}
	} catch ( Exception $e ) {
		error_log( 'Tarokina Pro: Error al cargar Carbon Fields autoloader: ' . $e->getMessage() );
		return;
	} catch ( Error $e ) {
		error_log( 'Tarokina Pro: Error fatal al cargar Carbon Fields autoloader: ' . $e->getMessage() );
		return;
	}

	// Solo cargar el sistema de warnings en admin y si es necesario
	if ( is_admin() && class_exists( '\\Carbon_Fields_Tarokina_Pro\\Libraries\\Plugin_Update_Warning\\Plugin_Update_Warning' ) ) {
		// Lazy loading del sistema de warnings
		add_action( 'admin_init', function() {
			\Carbon_Fields_Tarokina_Pro\Libraries\Plugin_Update_Warning\Plugin_Update_Warning::boot();
		}, 20 );
	}
}

/**
 * Cargar versión aislada de Carbon Fields para evitar conflictos - SIMPLIFICADO
 */
function carbon_fields_load_isolated_version_tarokina_pro() {
	// Marcar como cargado para evitar conflictos
	if ( ! defined( 'CARBON_FIELDS_TAROKINA_PRO_LOADED' ) ) {
		define( 'CARBON_FIELDS_TAROKINA_PRO_LOADED', true );
	}
}
