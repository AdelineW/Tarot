<?php

/**
 * The file that defines the core plugin class
 *
 * @link       tarokkina_pro/fer
 * @since      1.0.0
 *
 * @package    Tarokkina_pro
 * @subpackage Tarokkina_pro/includes
 */
class Tarokkina_pro
{

	/**
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Tarokkina_pro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;


	protected $plugin_name;
	protected $version;

	public function __construct()
	{
		if (defined('TAROKKINA_PRO_VERSION')) {
			$this->version = TAROKKINA_PRO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		define('MYCARD', base64_decode('dmFsaWQ='));
		define('MYDECK', base64_decode('aW52YWxpZA=='));
		$this->plugin_name = 'tarokina-pro';


		// Campos _con
		require_once plugin_dir_path(dirname(__FILE__)) . 'lib/tarokina_con/tarokina_con.php';


		// Preview Elementor
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor-styles_pro.php';

		if (is_admin()) {
			require_once TAROKINA_ADMIN_PATH . 'fields/fields_content.php';

			// CARBON FIEDS - Cuidado si movemos este código de aquí. Puede que no se pueda guardar los textos de las cartas.
			$getElementor = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : null;
			$getDivi = (isset($_GET['et_fb'])) ? sanitize_text_field($_GET['et_fb']) : null;
			if ($getElementor == 'elementor' || $getDivi == 1) {
			} else {
				require_once plugin_dir_path(dirname(__FILE__)) . 'lib/carbon-fields/carbon-fields-plugin.php';
			}
		}




		// wp_content_
		function TkNaP_wp_content_()
		{
			$mycard_l = get_transient('tarokina_mycard_l');
			if (false === $mycard_l) {
				$license = trim(get_option('content_id'));
				$api_params = array(
					'edd_action'  => 'check_license',
					'license'     => $license,
					'item_id'     => TKINA_TAROKINA_LICENSES['lic_tarokina_con']['id_product'],
					'item_name'   => rawurlencode(TKINA_TAROKINA_LICENSES['lic_tarokina_con']['product_name']),
					'url'         => home_url(),
					'environment' => TAROKINA_PRODUCTION_MODE ? 'production' : 'development',
				);

				// Call the custom API.
				$force_domain_tarokina = get_option('_change_license_domain', '');
				$change_domain_tarokina = (defined('TAROKINA_PRODUCTION_MODE') && TAROKINA_PRODUCTION_MODE) ? 'dominio' : 'dominio2';
				$request_url = add_query_arg($api_params, rtrim(TKINA_TAROKINA_LICENSES['lic_tarokina_con'][$change_domain_tarokina], '/'));
				$response = wp_remote_get($request_url, array('timeout' => 15, 'sslverify' => false));

				// Registrar la respuesta para depuración
				error_log('Tarokina License Check Response: ' . wp_remote_retrieve_body($response));

				// Valor por defecto en caso de error
				$mycard_l = 'invalid';

				if (!is_wp_error($response)) {
					$response_body = wp_remote_retrieve_body($response);
					if (!empty($response_body)) {
						$wp_content_data = json_decode($response_body);
						if (json_last_error() === JSON_ERROR_NONE && isset($wp_content_data->license)) {
							$mycard_l = $wp_content_data->license;
						}
					}
				}

				// Siempre establecer el transient con el valor final (válido o inválido)
				set_transient('tarokina_mycard_l', $mycard_l, DAY_IN_SECONDS);

				// Actualizar el array mycard_arr con el estado actual de la licencia
				$mycard_arr = (array) get_option('mycard_arr', []);
				$mycard_arr = array_diff($mycard_arr, array("", 0, null));
				$mycard_arr['tarokina-pro'] = $mycard_l;
				update_option('mycard_arr', $mycard_arr);

				// Actualizar la opción específica de estado
				update_option('content_id_status', $mycard_l);
			}

			// Devolver el valor del transient (recién creado o existente)
			return $mycard_l;
		}


		if (is_admin()) {
			TkNaP_wp_content_();
		}


		// set_transient('tarokina_mycard_l', 'valid', YEAR_IN_SECONDS );
		// $mycard_arr = (array) get_option('mycard_arr');
		// $mycard_arr = array_diff($mycard_arr, array("",0,null));
		// $mycard_arr2 = ['tarokina-pro' => 'valid']; 
		// $mycard_arr3 = array_merge($mycard_arr, $mycard_arr2);
		// update_option('mycard_arr', $mycard_arr3);
		// update_option( 'content_id_status', 'valid' );




		$this->TkNaP_load_dependencies();
		$this->TkNaP_set_locale();
		$this->TkNaP_define_admin_hooks();
		$this->TkNaP_define_public_hooks();
		$this->TkNaP_addons_hooks();
	}


	private function TkNaP_load_dependencies()
	{
		$urlPostype = (isset($_GET['post_type'])) ? sanitize_text_field($_GET['post_type']) : '';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/functions.php';

		if (is_admin()) {

			// Custom Post Type
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/tarokkina_pro_custom_type.php';

			if ($urlPostype == 'tarokkina_pro') {
				require_once plugin_dir_path(dirname(__FILE__)) . 'admin/sidebars/sidebar_all.php';
			}

			require_once TAROKINA_ADMIN_PATH . 'cartas/fields_cartas.php';
		}


		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tarokkina_pro-loader.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tarokkina_pro-i18n.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-tarokkina_pro-admin.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-tarokkina_pro-public.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-addons.php';


		$this->loader = new Tarokkina_pro_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Tarokkina_pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function TkNaP_set_locale()
	{

		$plugin_i18n = new Tarokkina_pro_i18n();
		$this->loader->add_action('init', $plugin_i18n, 'load_plugin_textdomain_pro', 1);
		$this->loader->add_action('init', $plugin_i18n, 'tkna_language_addons', 1);
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function TkNaP_define_admin_hooks()
	{
		$urlPostype = (isset($_GET['post_type'])) ? sanitize_text_field($_GET['post_type']) : '';
		$urlPage = (isset($_GET['page'])) ? sanitize_text_field($_GET['page']) : '';
		$plugin_admin = new Tarokkina_pro_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('init', $plugin_admin, 'TkNaP_add_tarot_names');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'TkNaP_enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'TkNaP_enqueue_scripts');
		$this->loader->add_action('after_setup_theme', $plugin_admin, 'TkNaP_img_size');

		if (is_admin() && $urlPostype == 'tarokkina_pro') {

			// Css inline in cards
			$this->loader->add_action('admin_head', $plugin_admin, 'TkNaP_enqueue_styles_inline_css');

			// default license
			add_option('mycard_arr', ['tarokina-pro' => 'invalid', 'classic_spreads' => 'invalid', 'custom_spreads' => 'invalid', 'edd_restriction_tarokina' => 'invalid']);

			// Info Cartas
			$this->loader->add_action('edit_form_after_title', $plugin_admin, 'TkNaP_info_cartas');


			// NOTICE - Aviso llamada api easy digital download
			$this->loader->add_action('admin_notices', $plugin_admin, 'TkNaP_notice_api_edd');


			// LINK Review in web for Tarokina Pro
			$this->loader->add_filter('admin_footer_text', $plugin_admin, 'TkNaP_pro_admin_footer');

			// Per Page Tarokina Free
			$this->loader->add_filter('edit_tarokkina_pro_per_page', $plugin_admin, 'TkNa_per_page', 10, 1);

			// Logo
			$this->loader->add_action('in_admin_header', $plugin_admin, 'TkNaP_cabecera', 15);


			// Insertar Tarots en submenu de custom post type
			$this->loader->add_action('admin_menu', $plugin_admin, 'TkNaP_link_submenu_tarots');

			// Menu 100 tarots en tarots
			if (get_option('_tkna_more_tarots')) {
				$this->loader->add_action('carbon_fields_container_tarots_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
				$this->loader->add_action('carbon_fields_container_tarots2_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
				$this->loader->add_action('carbon_fields_container_tarots3_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
				$this->loader->add_action('carbon_fields_container_tarots4_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
				$this->loader->add_action('carbon_fields_container_tarots5_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
				$this->loader->add_action('carbon_fields_container_tarots6_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
				$this->loader->add_action('carbon_fields_container_tarots7_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
				$this->loader->add_action('carbon_fields_container_tarots8_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
				$this->loader->add_action('carbon_fields_container_tarots9_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
				$this->loader->add_action('carbon_fields_container_tarots10_before_fields', $plugin_admin, 'TkNaP_menu_top_tarots');
			}

			// Lenguaje - Traduccion - translate - Desactivar para salvar las cadenas de Traducción
			// Borrar Todos los archivos vista.php. 
			$this->loader->add_action('init', $plugin_admin, 'TkNaP_clear_cache');
		}

		// Links in plugins.php
		add_filter('plugin_action_links_tarokina-pro/tarokina-pro.php',  array($plugin_admin, 'TkNaP_links_plugin'));


		/////////////////////////////////////////////////////////////////////////////////////////////////

		// Se borra el option '_tarokki_checkCartas' en 4 sitios: botón clear cache, al salvar los tarots, al salvar las cartas y al editar en la tabla de cartas, Bulk actions.

		// 1.- Creando el option de los tarots y generando las cartas al salvar los Tarots
		$this->loader->add_action('carbon_fields_theme_options_container_saved', $plugin_admin, 'TkNaP_pro_option', 1);

		// 2.- Creando el option de los tarots y generando las cartas al salvar las cartas
		$this->loader->add_action('save_post_tarokkina_pro', $plugin_admin, 'TkNaP_pro_option', 2);

		// 3.- Creando el option de los tarots y generando las cartas al salvar en la tabla de cartas, Bulk actions
		$checkCartas = get_option('_tarokki_checkCartas');
		if (!$checkCartas) {
			$this->loader->add_action('admin_head', $plugin_admin, 'TkNaP_pro_option');
			update_option('_tarokki_checkCartas', 'yes');
		}

		/////////////////////////////////////////////////////////////////////////////////////////////////



		// creando el option de imagenes
		$this->loader->add_action('admin_head', $plugin_admin, 'TkNaP_array_cartas');



		if (is_admin()) {

			//Actualizando los datos cuando hay una actualización 
			$modal_update = get_option('tkna_pro_modal_update');
			$button_clear_cache = get_option('tkna_notice_updatePlugin_clear_cache');
			$cids = (isset($_GET['cids'])) ? sanitize_text_field($_GET['cids']) : '';

			if ($modal_update == 'yes') {

				// Convertir basic en expert
				$numVersion = substr(TAROKKINA_PRO_VERSION, 0, 3);
				$numVersion = floatval($numVersion);
				if ($numVersion <= 1.8) {
					$this->loader->add_action('admin_init', $plugin_admin, 'TkNaP_basic_to_expert', 2);
					$this->loader->add_action('admin_init', $plugin_admin, 'TkNaP_cards_ids');
				}

				$this->loader->add_action('carbon_fields_container_activated', $plugin_admin, 'TkNaP_pro_option', 1);
			}


			if ($cids == 1) {
				$this->loader->add_action('admin_head', $plugin_admin, 'TkNaP_pro_option', 1);
				delete_option('_tarokki_checkCartas');
				$this->loader->add_action('admin_notices', $plugin_admin, 'TkNaP_notice_clear_cache');
			}

			if ($button_clear_cache == 'yes' && $urlPage !== 'crb_carbon_fields_container_tarots.php') {
				$this->loader->add_action('admin_notices', $plugin_admin, 'TkNaP_notice_updatePlugin_clear_cache');
			}
		}



		//  NOTICE - Aviso licencia inactiva
		$this->loader->add_action('admin_notices', $plugin_admin, 'TkNaP_license_notice');

		//  NOTICE - Cache cleared notice
		$this->loader->add_action('admin_notices', $plugin_admin, 'TkNaP_cache_cleared_notice');


		// Change types
		$this->loader->add_action('carbon_fields_theme_options_container_saved', $plugin_admin, 'TkNaP_pro_change_type', 1);


		// Elimina la imagen de la carta de la antigua baraja
		$this->loader->add_filter('pre_post_update', $plugin_admin, 'TkNaP_delete_Image_old', 1);


		// Instalador Create Deck Demo
		$this->loader->add_action('carbon_fields_theme_options_container_saved', $plugin_admin, 'TkNaP_install_deck_demo', 9);


		// Tarokina Install Demo
		$this->loader->add_action('carbon_fields_theme_options_container_saved', $plugin_admin, 'TkNaP_install_demo', 11);



		// Update plugin desde el exterior
		$this->loader->add_action('upgrader_process_complete', $plugin_admin, 'tkina_upgrader_process_complete', 10, 2);

		// Update plugin desde un archivo ZIP
		$this->loader->add_action('upgrader_post_install', $plugin_admin, 'tkina_upgrader_post_install', 10, 3);


		// no-cache
		$this->loader->add_action('send_headers', $plugin_admin, 'TkNaP_noCache', 1);

		// TkNaP_restriction_ids save_post   
		$this->loader->add_action('wp', $plugin_admin, 'TkNaP_restriction_ids');

		// Uploads Decks tkina_upload_decks
		$this->loader->add_action('admin_init', $plugin_admin, 'tkina_upload_decks');
		$this->loader->add_action('carbon_fields_container_options_after_fields', $plugin_admin, 'display_upload_form');
		$this->loader->add_action('before_delete_post', $plugin_admin, 'remove_featured_image');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function TkNaP_define_public_hooks()
	{

		$plugin_public = new Tarokkina_pro_Public($this->get_plugin_name(), $this->get_version());

		if (!is_admin()) {
			$this->loader->add_action('init', $plugin_public, 'TkNaP_pro_shortcode');
		};


		// Ajax Tarot2
		$this->loader->add_action('wp_ajax_nopriv_e-tarot', $plugin_public, 'TkNaP_pro_x8z_tarotAjax2');

		// Ajax Tarot2-2
		$this->loader->add_action('wp_ajax_e-tarot', $plugin_public, 'TkNaP_pro_x8z_tarotAjax2');

		// Remove Shortcodes from excerpt and home
		$this->loader->add_action('wp', $plugin_public, 'TkNaP_pro_remove_shortcode');
	}



	////////////////////  CLASS ADDONS HOOKS ////////////////////////
	////////////////////////////////////////////////////////////////

	private function TkNaP_addons_hooks()
	{
		$urlPostype = (isset($_GET['post_type'])) ? sanitize_text_field($_GET['post_type']) : '';
		$urlPage = (isset($_GET['page'])) ? sanitize_text_field($_GET['page']) : '';
		$Class_addons = new tarokki_addons($this->get_plugin_name(), $this->get_version());
		if (is_admin() && $urlPostype == 'tarokkina_pro') {

			if (get_option('_tkna_more_tarots')) {

				if ($urlPage == 'crb_carbon_fields_container_tarots.php') {
					//  Tarot 1-10
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista', 10);
					$this->loader->add_action('carbon_fields_container_tarots_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots2.php') {
					//  Tarot 11-20
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor2');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista2', 10);
					$this->loader->add_action('carbon_fields_container_tarots2_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots3.php') {
					//  Tarot 21-30
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor3');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista3', 10);
					$this->loader->add_action('carbon_fields_container_tarots3_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots4.php') {
					//  Tarot 31-40
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor4');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista4', 10);
					$this->loader->add_action('carbon_fields_container_tarots4_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots5.php') {
					//  Tarot 41-50
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor5');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista5', 10);
					$this->loader->add_action('carbon_fields_container_tarots5_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots6.php') {
					//  Tarot 51-60
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor6');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista6', 10);
					$this->loader->add_action('carbon_fields_container_tarots6_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots7.php') {
					//  Tarot 61-70
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor7');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista7', 10);
					$this->loader->add_action('carbon_fields_container_tarots7_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots8.php') {
					//  Tarot 71-80
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor8');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista8', 10);
					$this->loader->add_action('carbon_fields_container_tarots8_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots9.php') {
					//  Tarot 81-90
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor9');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista9', 10);
					$this->loader->add_action('carbon_fields_container_tarots9_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots10.php') {
					//  Tarot 91-100
					$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor10');
					$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista10', 10);
					$this->loader->add_action('carbon_fields_container_tarots10_after_fields', $Class_addons, 'TkNaP_Grid', 1);
				}
			} else {
				//  Tarot 1-10
				$this->loader->add_action('after_setup_theme', $Class_addons, 'TkNaP_Escritor');
				$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Vista', 10);
				$this->loader->add_action('carbon_fields_container_tarots_after_fields', $Class_addons, 'TkNaP_Grid', 1);
			}


			// Option page
			$this->loader->add_action('carbon_fields_register_fields', $Class_addons, 'TkNaP_Options', 11);
		}
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Tarokkina_pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
