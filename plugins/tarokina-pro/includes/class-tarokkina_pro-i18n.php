<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       tarokkina_pro/fer
 * @since      1.0.0
 *
 * @package    Tarokkina_pro
 * @subpackage Tarokkina_pro/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tarokkina_pro
 * @subpackage Tarokkina_pro/includes
 * @author     tarokkina_pro <tarokkina_pro@gmailcom>
 */
class Tarokkina_pro_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain_pro() {

		load_plugin_textdomain(
			'tarokina-pro',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


	public function tkna_language_addons(){
		$lan_addons = array(
			__( 'On hold', 'tarokina-pro' ),
			__( 'Expired', 'tarokina-pro' ),
			esc_html__('Tarokina Pro not found!','tarokina-pro'),
			esc_html__( 'License', 'tarokina-pro' ),
			__( 'Reactivate tarot', 'tarokina-pro' ),
			__( 'Activate', 'tarokina-pro' ),
			__( 'Expires', 'tarokina-pro' ),
			__( 'View progress →', 'tarokina-pro' ),
			__( 'Enabled', 'tarokina-pro' ),
			__( 'Change status to completed', 'tarokina-pro' ),
			esc_html__('Access','tarokina-pro')

		);
		return $lan_addons;
	}

	



}
