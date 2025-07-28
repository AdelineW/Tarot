<?php

/**
 * Fired during plugin deactivation
 *
 * @link       tarokkina_pro/fer
 * @since      1.0.0
 *
 * @package    Tarokkina_pro
 * @subpackage Tarokkina_pro/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Tarokkina_pro
 * @subpackage Tarokkina_pro/includes
 * @author     tarokkina_pro <tarokkina_pro@gmailcom>
 */
class Tarokkina_pro_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function TkNaP_deactivate() {
		flush_rewrite_rules();

		$del_vista = fopen( TAROKINA_ADMIN_PATH.'tarots/vista.php', 'w');
		fwrite($del_vista, ''); 
		fclose($del_vista);

		$del_vistaCarta = fopen( TAROKINA_ADMIN_PATH.'cartas/carta_vista.php', 'w');
		fwrite($del_vistaCarta, '');
		fclose($del_vistaCarta);

	}
	

}
