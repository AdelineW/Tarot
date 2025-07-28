<?php

/**
 * Fired during plugin activation
 *
 * @link       tarokkina_pro/fer
 * @since      1.0.0
 *
 * @package    Tarokkina_pro
 * @subpackage Tarokkina_pro/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Tarokkina_pro
 * @subpackage Tarokkina_pro/includes
 * @author     tarokkina_pro <tarokkina_pro@gmailcom>
 */
class Tarokkina_pro_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function TkNaP_activate() {
		flush_rewrite_rules();

		// Copy the mu-plugins in the correct folder
		$mu_folder = plugin_dir_path(dirname(__DIR__, 2 ) ).'mu-plugins';
		if (!is_dir($mu_folder))
		{
			mkdir($mu_folder, 0755, true);
		}
		@copy(plugin_dir_path( dirname( __FILE__ ) ) . 'lib/tarokina.php',$mu_folder.'/tarokina.php');


        // archivo vista vacio
		$del_vista = fopen( TAROKINA_ADMIN_PATH.'tarots/vista.php', 'w');
		fwrite($del_vista, ''); 
		fclose($del_vista);

		// archivo vista carta vacio
		$del_vistaCarta = fopen( TAROKINA_ADMIN_PATH.'cartas/carta_vista.php', 'w');
		fwrite($del_vistaCarta, '');
		fclose($del_vistaCarta);

		add_option( 'tkna_restrict_post_id', array() );

		update_option('tkna_addon_inactive',array(),'yes');
	

	}

}
