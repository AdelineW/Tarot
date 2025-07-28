<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       tarokkina_pro/fer
 * @since      1.0.0
 *
 * @package    Tarokkina_pro
 * @subpackage Tarokkina_pro/admin
 */

class Tarokkina_pro_Admin
{


	private $plugin_name;
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function TkNaP_enqueue_styles()
	{
		if (
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots' || get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots2' ||
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots3' ||
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots4' ||
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots5' ||
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots6' ||
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots7' ||
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots8' ||
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots9' ||
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots10' ||
			get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_options'
		) {
			$css_back = 'back_tarots';
			wp_enqueue_style('tarokkina_pro_' . $css_back, plugin_dir_url(__DIR__) . 'css/' . $css_back . '.css', array(), $this->version, 'all');
		} elseif (get_current_screen()->id == 'edit-tarokkina_pro-cat' || get_current_screen()->id == 'tarokkina_pro_page_tarokina_pro_license') {
			$css_back = 'back_barajas';
			wp_enqueue_style('tarokkina_pro_' . $css_back, plugin_dir_url(__DIR__) . 'css/' . $css_back . '.css', array(), $this->version, 'all');
		} elseif (get_current_screen()->id == 'edit-tarokkina_pro') {
			$css_back = 'back_cartas';
			wp_enqueue_style('tarokkina_pro_' . $css_back, plugin_dir_url(__DIR__) . 'css/' . $css_back . '.css', array(), $this->version, 'all');
		} elseif (get_current_screen()->id == 'tarokkina_pro') {
			$css_back = 'back_carta';
			wp_enqueue_style('tarokkina_pro_' . $css_back, plugin_dir_url(__DIR__) . 'css/' . $css_back . '.css', array(), $this->version, 'all');
		} else {
		}
	}

	public function TkNaP_enqueue_styles_inline_css()
	{
		echo '<style>';
		if (isset($_GET['t_id'])) {
			echo '#postbox-container-2{display: block !important}';
		}
		echo ' .post-php.post-type-tarokkina_pro #postbox-container-2 .meta-box-sortables #carbon_fields_container_span_classt_modespan_classspretarotspanspantarot_free.carbon-box{ display: none !important;}';
		echo '</style>';
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function TkNaP_enqueue_scripts()
	{
		if (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots') {
			$js_back = 'js_back_tarots';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/js/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots',
				'back_tarots',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_options') {
			$js_back = 'js_back_opciones';
			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/js/' . $js_back . '.js', array('jquery'), $this->version, false);
		} elseif (get_current_screen()->id == 'edit-tarokkina_pro-cat') {
			$js_back = 'js_back_barajas';
			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/js/' . $js_back . '.js', array('jquery'), $this->version, true);
		} elseif (get_current_screen()->id == 'edit-tarokkina_pro') {
			$js_back = 'js_back_cartas';
			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/js/' . $js_back . '.js', array('jquery'), $this->version, true);
		} elseif (get_current_screen()->id == 'tarokkina_pro') {
			$js_back = 'js_back_carta';
			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/js/' . $js_back . '.js', array('jquery'), $this->version, true);

			// llamada a todos los tarots
			require plugin_dir_path(dirname(__FILE__)) . 'includes/tarokkina_pro_all_tarots.php';
			wp_localize_script(
				'tarokkina_pro_js_back_carta',
				'back_carta',
				array(
					'tarot_names'  => $tarots
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots2') {
			$js_back = 'js_back_tarots2';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/tarots2/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots2',
				'back_tarots2',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots3') {
			$js_back = 'js_back_tarots3';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/tarots3/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots3',
				'back_tarots3',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots4') {
			$js_back = 'js_back_tarots4';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/tarots4/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots4',
				'back_tarots4',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots5') {
			$js_back = 'js_back_tarots5';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/tarots5/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots5',
				'back_tarots5',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots6') {
			$js_back = 'js_back_tarots6';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/tarots6/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots6',
				'back_tarots6',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots7') {
			$js_back = 'js_back_tarots7';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/tarots7/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots7',
				'back_tarots7',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots8') {
			$js_back = 'js_back_tarots8';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/tarots8/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots8',
				'back_tarots8',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots9') {
			$js_back = 'js_back_tarots9';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/tarots9/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots9',
				'back_tarots9',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} elseif (get_current_screen()->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots10') {
			$js_back = 'js_back_tarots10';
			$addons_inactive = get_option('tkna_addon_inactive');
			$mycard_arr = (array) get_option('mycard_arr');

			wp_enqueue_script('tarokkina_pro_' . $js_back, plugin_dir_url(__DIR__) . 'admin/tarots10/' . $js_back . '.js', array('jquery'), $this->version, true);
			wp_localize_script(
				'tarokkina_pro_js_back_tarots10',
				'back_tarots10',
				array(
					'wp_content'    => $mycard_arr,
					'addons_inactive' => $addons_inactive,
					'textDelete' => __('Delete', 'tarokina-pro'),
					'save_text' => __('Save Changes')
				)
			);
		} else {
			wp_enqueue_script('tarokkina_pro_js_admin', plugin_dir_url(__DIR__) . 'admin/js/js_admin.js', array('jquery'), $this->version, true);
		}
	}



	// Logo
	public function TkNaP_cabecera()
	{
		$cabecera = '
			<div class="cabecera_head">
				<div class="cabecera_logo">
					<img src="' . TAROKINA_URL . 'img/logo.svg" alt="logo">
					<div class="name">arnelio</div>
					<div class="bloq_title">
					<span class="logoTitle">TAROKINA <span class="textPro">PRO</span></span>
					<span class="plugV">' . TAROKKINA_PRO_VERSION . '</span>
					</div>
				</div>
				<div class="cabecera_info">

					<span>
					<a data-text="' .
			esc_html__('Regenerate images and clear the tarot cache.', 'tarokina-pro') . '" class="btnCardIds clear_tooltip" href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=0&page=crb_carbon_fields_container_tarots.php&cids=1') . '"><svg class="svg-icon" style="display:inline;width:25px; height:25px; vertical-align:bottom;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M837.818182 915.549091h-131.956364c-12.8 0-23.272727-10.472727-23.272727-23.272727v-58.181819c0-4.189091-8.843636-11.636364-23.272727-11.636363s-23.272727 7.447273-23.272728 11.636363v58.181819c0 12.8-10.472727 23.272727-23.272727 23.272727H465.454545c-12.8 0-23.272727-10.472727-23.272727-23.272727s10.472727-23.272727 23.272727-23.272728h124.043637v-34.909091c0-32.581818 30.72-58.181818 69.818182-58.181818s69.818182 25.6 69.818181 58.181818v34.909091H814.545455v-232.727272H209.454545v232.727272h93.789091c12.8 0 23.272727 10.472727 23.272728 23.272728s-10.472727 23.272727-23.272728 23.272727H186.181818c-12.8 0-23.272727-10.472727-23.272727-23.272727v-279.272728c0-12.8 10.472727-23.272727 23.272727-23.272727h651.636364c12.8 0 23.272727 10.472727 23.272727 23.272727v279.272728c0 12.8-10.472727 23.272727-23.272727 23.272727z" /><path d="M837.818182 636.276364H186.181818c-12.8 0-23.272727-10.472727-23.272727-23.272728v-69.818181c0-11.403636 8.145455-20.945455 19.316364-22.807273l244.596363-43.054546V124.276364c0-26.065455 23.970909-46.545455 54.225455-46.545455h62.138182c30.487273 0 54.225455 20.48 54.225454 46.545455V477.090909l244.596364 43.054546c11.170909 1.861818 19.316364 11.636364 19.316363 22.807272v69.818182A23.738182 23.738182 0 0 1 837.818182 636.276364z m-628.363637-46.545455h605.09091v-26.996364l-244.596364-43.054545a23.202909 23.202909 0 0 1-19.316364-22.807273V126.138182a13.172364 13.172364 0 0 0-7.68-2.094546h-62.138182c-3.723636 0-6.516364 1.163636-7.68 2.094546v370.269091c0 11.403636-8.145455 20.945455-19.316363 22.807272L209.454545 562.734545v26.996364z" /></svg>&nbsp;' .
			esc_html__('Clear cache', 'tarokina-pro') . '</a>
					</span>
					<span><a href="https://arnelio.com/" target="_blank">Addons</a></span>
					<span><a href="https://arnelio.com/support/" target="_blank">Support</a></span>
				</div>
			</div>
			';
		echo $cabecera;
	}


	// Tamaño imagen tarot
	public function TkNaP_img_size()
	{
		// Imagenes
		add_image_size('tarokkina_pro-mini', 170);
	}



	// Info Cartas
	public function TkNaP_info_cartas()
	{
		global $post;

		if (get_option('_tkna_more_tarots')) {
			$all_tarots = (get_option('tarokki_tarotsAll_array') !== false) ? get_option('tarokki_tarotsAll_array') : array();
		} else {
			$all_tarots =  get_option('tarokki_tarotsAll');
			$all_tarots = ($all_tarots) ? $all_tarots : array();
		}

		$tarots_decks = [];
		$tarots_names = [];
		$tarots_ids = [];
		$title = $post->post_title;

		$cat = get_the_terms(get_the_ID(), 'tarokkina_pro-cat');
		$cat_name = ($cat !== false) ? $cat[0]->name : '';
		$cat_name = ($cat_name !== null) ? $cat_name : '';
		$catID = ($cat !== false) ? $cat[0]->term_id : '';
		$catID = ($catID !== null) ? $catID : '';


		foreach ($all_tarots as  $tarot) {
			$term_name = get_term($tarot['tkta_barajas']);
			$term_name = $term_name->name ?? '';
			$tarots_decks[] = $term_name;

			if ($tarot['tkta_barajas'] == $catID) {
				$tarots_names[] = $tarot['tkta_name'];
				$tarots_ids[] = $tarot['tkta_id'];
			}
		}

		echo '<div class="bloq_info_Cartas">
			      <div class="subtitleInfCart">' . esc_html__('Write down your readings or interpretations for this card.', 'tarokina-pro') .
			'<br><span class="flechaCard">&#8595;</span></div>';

		if ($title == '' || $cat_name == '') {
			// Nueva Carta

			echo '</div><div id="emptyTextsCards2">';
			echo '<div class="deckCard">' .
				esc_html__('Select a deck and set an image.', 'tarokina-pro');
			echo '</div></div>';
		} elseif (in_array($cat_name, $tarots_decks) !== false) {


			echo '</div><br><span id="cat_name" style="display:none">' . esc_html($cat_name) . '</span>';


			// Menu Tarots
			$url = admin_url('post.php?post_type=tarokkina_pro&post=' . $post->ID . '&action=edit');
			$pg = (isset($_GET['tarot'])) ? sanitize_text_field($_GET['tarot']) : 0;
			$pg_num = 0;



			echo '<div class="containermenuT">';
			// Con Tarots vinculados

			foreach ($tarots_names as $name) {

				if (isset($_GET['t_id']) && trim($_GET['t_id']) == trim($tarots_ids[$pg_num])) {
					$active = 'active';
					$btnType = 'primary';
				} else {
					$active = '';
					$btnType = 'secondary';
				}

				echo '<a class="button button-'.$btnType.' ' . $active . '" href="' . $url . '&tarot=' . $pg_num . '&t_id=' . $tarots_ids[$pg_num] . '">' . $name . '</a>';
				$pg_num++;
			}
			echo '<div class="animation start-home"></div>
				</div><br>
				';
		} else {
			// Sin Tarots vinculados
			echo '</div><br><div id="emptyTextsCards">';
			printf(esc_html__('There is no tarot with the %s deck selected. Please go to the Tarots section and assign the %s deck in any tarot', 'tarokina-pro'), $cat_name, $cat_name);
			echo '.&nbsp;<a href="' . admin_url('edit.php?post_type=tarokkina_pro&page=crb_carbon_fields_container_tarots.php') . '">' . esc_html__('Tarots', 'tarokina-pro') . '</a>';
			echo '</div>';
		}
	}


	// Links en plugins.php
	public function TkNaP_links_plugin(array $actions)
	{
		if (is_admin()) {
			return array_merge(array(
				'tarot' => '<img style="width:57px;height:27px" src="' . esc_url(TAROKINA_URL) . 'img/icon_double.svg"><a href="edit.php?post_type=tarokkina_pro&page=tarokina_pro_license">' . esc_html__('License', 'tarokina-pro') . '</a>',
				'addons'  => '<a href="https://arnelio.com/">' . esc_html__('Add-ons', 'tarokina-pro') . '</a>',
			), $actions);
		}
	}


	// Per page tarokina Pro
	public function TkNa_per_page($per_page)
	{
		if ($per_page < 50) {
			$num = 50;
		} else {
			$num = $per_page;
		}
		return $num;
	}



	// Creando el option de los 100 tarots
	public function TkNaP_pro_option()
	{
		require plugin_dir_path(dirname(__FILE__)) . 'includes/include_option_tarot.php';
	}


	// Borrando el check de la tabla de las cartas en bulk actions 
	public function TkNaP_array_cartas()
	{
		global $current_screen;

		if ($current_screen->id == 'edit-tarokkina_pro' && isset($_GET['trashed']) || $current_screen->id == 'edit-tarokkina_pro' && isset($_GET['untrashed']) || $current_screen->id == 'edit-tarokkina_pro' && isset($_GET['updated']) || $current_screen->id == 'tarokkina_pro_page_crb_carbon_fields_container_tarots' && isset($_GET['settings-updated']) || isset($_GET['tarots']) && isset($_GET['settings-updated']) || $current_screen->id == 'tarokkina_pro' && isset($_GET['message'])) {

			require plugin_dir_path(dirname(__FILE__)) . 'includes/cards_ids.php';
			delete_option('_tarokki_checkCartas');
		}
	}





	// Add Tarot names
	public function TkNaP_add_tarot_names()
	{
		if (!get_option('tarokki_add_tarot_names')):

			add_option('tarokki_tarot_names', array());
			add_option('tarokki_tarot_names2', array());
			add_option('tarokki_tarot_names3', array());
			add_option('tarokki_tarot_names4', array());
			add_option('tarokki_tarot_names5', array());
			add_option('tarokki_tarot_names6', array());
			add_option('tarokki_tarot_names7', array());
			add_option('tarokki_tarot_names8', array());
			add_option('tarokki_tarot_names9', array());
			add_option('tarokki_tarot_names10', array());

			add_option('tarokki_tarotsAll', array());
			add_option('tarokki_tarotsAll2', array());
			add_option('tarokki_tarotsAll3', array());
			add_option('tarokki_tarotsAll4', array());
			add_option('tarokki_tarotsAll5', array());
			add_option('tarokki_tarotsAll6', array());
			add_option('tarokki_tarotsAll7', array());
			add_option('tarokki_tarotsAll8', array());
			add_option('tarokki_tarotsAll9', array());
			add_option('tarokki_tarotsAll10', array());

			add_option('tarokki_EDD_products', array());
			add_option('tarokki_EDD_products2', array());
			add_option('tarokki_EDD_products3', array());
			add_option('tarokki_EDD_products4', array());
			add_option('tarokki_EDD_products5', array());
			add_option('tarokki_EDD_products6', array());
			add_option('tarokki_EDD_products7', array());
			add_option('tarokki_EDD_products8', array());
			add_option('tarokki_EDD_products9', array());
			add_option('tarokki_EDD_products10', array());

			add_option('tarokki_add_tarot_names', 1);
		endif;
	}



	// Elimina la imagen de la carta de la antigua baraja
	function TkNaP_delete_Image_old()
	{
		global $current_screen;

		if (isset($current_screen->id) && $current_screen->id == 'tarokkina_pro' || isset($current_screen->id) && $current_screen->id == 'edit-tarokkina_pro') {
			global $post;
			$postID = (isset($post->ID)) ? $post->ID : '';
			$cat = get_the_terms(get_the_ID(), 'tarokkina_pro-cat');
			$cat = ($cat !== false) ? $cat[0]->term_id : '';
			$cat_id = ($cat !== null) ? $cat : '';
			$Arr_images = (get_option($cat_id . '_deck_tarokki') !== '') ? get_option($cat_id . '_deck_tarokki') : array();
			$Arr_imagesID = (isset($Arr_images[$postID])) ? $Arr_images[$postID] : '';

			if ($Arr_imagesID !== array() || $Arr_imagesID !== null) {
				unset($Arr_images[$postID]);
			}

			update_option($cat_id . '_deck_tarokki', $Arr_images);
		}
	}





	// change type tarot for save
	public function TkNaP_pro_change_type()
	{

		if (get_option('_tkna_more_tarots')) {
			$tarot_names = get_option('tarokki_tarot_names_array');
		} else {
			$tarot_names = get_option('tarokki_tarot_names');
		}

		$urlPage = (isset($_GET['page'])) ? sanitize_text_field($_GET['page']) : '';

		if ($tarot_names !== false && count($tarot_names) >= 1) {
			$tarot_order = count($tarot_names);

			for ($i = 0; $i < $tarot_order; $i++) {
				$tkta_type = get_option('_tarokki_tarot_complex|tkta_type|' . $i . '|0|value');
				usleep(10000);
				if ($tkta_type !== 'Select') {
					update_option('_tarokki_tarot_complex|||' . $i . '|value', $tkta_type);
				}
			}

			// 100 Tarots 
			if (get_option('_tkna_more_tarots')) {

				if ($urlPage == 'crb_carbon_fields_container_tarots2.php') {
					for ($i = 0; $i < $tarot_order; $i++) {
						$tkta_type = get_option('_tarokki_tarot_complex2|tkta_type|' . $i . '|0|value');
						usleep(10000);
						if ($tkta_type !== 'Select') {
							update_option('_tarokki_tarot_complex2|||' . $i . '|value', $tkta_type);
						}
					}
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots3.php') {
					for ($i = 0; $i < $tarot_order; $i++) {
						$tkta_type = get_option('_tarokki_tarot_complex3|tkta_type|' . $i . '|0|value');
						usleep(10000);
						if ($tkta_type !== 'Select') {
							update_option('_tarokki_tarot_complex3|||' . $i . '|value', $tkta_type);
						}
					}
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots4.php') {
					for ($i = 0; $i < $tarot_order; $i++) {
						$tkta_type = get_option('_tarokki_tarot_complex4|tkta_type|' . $i . '|0|value');
						usleep(10000);
						if ($tkta_type !== 'Select') {
							update_option('_tarokki_tarot_complex4|||' . $i . '|value', $tkta_type);
						}
					}
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots5.php') {
					for ($i = 0; $i < $tarot_order; $i++) {
						$tkta_type = get_option('_tarokki_tarot_complex5|tkta_type|' . $i . '|0|value');
						usleep(10000);
						if ($tkta_type !== 'Select') {
							update_option('_tarokki_tarot_complex5|||' . $i . '|value', $tkta_type);
						}
					}
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots6.php') {
					for ($i = 0; $i < $tarot_order; $i++) {
						$tkta_type = get_option('_tarokki_tarot_complex6|tkta_type|' . $i . '|0|value');
						usleep(10000);
						if ($tkta_type !== 'Select') {
							update_option('_tarokki_tarot_complex6|||' . $i . '|value', $tkta_type);
						}
					}
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots7.php') {
					for ($i = 0; $i < $tarot_order; $i++) {
						$tkta_type = get_option('_tarokki_tarot_complex7|tkta_type|' . $i . '|0|value');
						usleep(10000);
						if ($tkta_type !== 'Select') {
							update_option('_tarokki_tarot_complex7|||' . $i . '|value', $tkta_type);
						}
					}
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots8.php') {
					for ($i = 0; $i < $tarot_order; $i++) {
						$tkta_type = get_option('_tarokki_tarot_complex8|tkta_type|' . $i . '|0|value');
						usleep(10000);
						if ($tkta_type !== 'Select') {
							update_option('_tarokki_tarot_complex8|||' . $i . '|value', $tkta_type);
						}
					}
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots9.php') {
					for ($i = 0; $i < $tarot_order; $i++) {
						$tkta_type = get_option('_tarokki_tarot_complex9|tkta_type|' . $i . '|0|value');
						usleep(10000);
						if ($tkta_type !== 'Select') {
							update_option('_tarokki_tarot_complex9|||' . $i . '|value', $tkta_type);
						}
					}
				} elseif ($urlPage == 'crb_carbon_fields_container_tarots10.php') {
					for ($i = 0; $i < $tarot_order; $i++) {
						$tkta_type = get_option('_tarokki_tarot_complex10|tkta_type|' . $i . '|0|value');
						usleep(10000);
						if ($tkta_type !== 'Select') {
							update_option('_tarokki_tarot_complex10|||' . $i . '|value', $tkta_type);
						}
					}
				}
			} // fin get_option more_tarots

		}
	}




	// NOTICE - Aviso llamada api easy digital download
	public function TkNaP_notice_api_edd()
	{
		$urlPage = (isset($_GET['page'])) ? sanitize_text_field($_GET['page']) : '';
		if (isset($_GET['sl_activation']) && ! empty($_GET['message']) && $urlPage == 'tarokina_pro_license') {

			switch ($_GET['sl_activation']) {

				case 'false':
					$message = urldecode($_GET['message']);
?>
					<div class="notice notice-error is-dismissible">
						<p style="padding: 8px"><?php echo esc_html($message); ?></p>
					</div>
			<?php
					break;

				case 'true':
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;
			}
		}
	}



	//  NOTICE - Aviso licencia inactiva tarokina pro y addons
	function TkNaP_license_notice()
	{
		$page = (isset($_GET['page'])) ? sanitize_text_field($_GET['page']) : false;

		// No mostrar avisos de licencia en la página de licencias
		if ($page === 'tarokina_pro_license') {
			return;
		}

		$lic_arr = get_option('mycard_arr', []);
		$tkta_ecommerce = get_option('_tkta_ecommerce');

		// Preparar array para plugins con licencia inactiva
		$inactive_plugins = [];

		// Verificar Tarokina Pro
		if (isset($lic_arr['tarokina-pro']) && $lic_arr['tarokina-pro'] !== MYCARD) {
			$inactive_plugins[] = 'Tarokina Pro';
		}

		// Verificar Classic Spreads
		if (
			is_plugin_active('tarokki-classic_spreads/tarokki-classic_spreads.php') &&
			(!isset($lic_arr['classic_spreads']) || $lic_arr['classic_spreads'] !== MYCARD)
		) {
			$inactive_plugins[] = 'Classic Spreads';
		}

		// Verificar Custom Spreads
		if (
			is_plugin_active('tarokki-custom_spreads/tarokki-custom_spreads.php') &&
			(!isset($lic_arr['custom_spreads']) || $lic_arr['custom_spreads'] !== MYCARD)
		) {
			$inactive_plugins[] = 'Custom Spreads';
		}

		// Verificar Restriction usando SOLO la clave correcta edd_restriction_tarokina
		if (
			is_plugin_active('tarokki-edd_restriction_tarokina/tarokki-edd_restriction_tarokina.php') &&
			(!isset($lic_arr['edd_restriction_tarokina']) || $lic_arr['edd_restriction_tarokina'] !== MYCARD)
		) {
			$inactive_plugins[] = 'Tarokina Restriction';
		}

		// Mostrar notificación unificada para plugins con licencia inactiva
		if (!empty($inactive_plugins)) {
			?>
			<div id="tarokina-license-notice" class="notice notice-warning is-dismissible">
				<p>
					<img style="width:57px;height:27px;vertical-align:bottom;margin-right:8px"
						src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__))) . 'img/icon_double.svg'; ?>">
					<strong><?php echo count($inactive_plugins) > 1 ? 'Plugins con licencia inactiva' : 'Plugin con licencia inactiva'; ?>: </strong>
					<?php echo esc_html(implode(', ', $inactive_plugins)); ?> -
					<?php esc_html_e('Enter a valid license key', 'tarokina-pro'); ?>
					<a class="button button-secondary"
						href="<?php echo admin_url('edit.php?post_type=tarokkina_pro&page=tarokina_pro_license'); ?>">
						<?php esc_html_e('License', 'tarokina-pro'); ?>
					</a>
				</p>
			</div>
			<?php
		}

		// Las siguientes notificaciones son específicas para requisitos de plugins y se mantienen separadas
		if (is_plugin_active('tarokki-edd_restriction_tarokina/tarokki-edd_restriction_tarokina.php')) {
			// Verificar si se necesita Easy Digital Downloads
			if ($tkta_ecommerce == 'edd' && is_plugin_inactive('easy-digital-downloads/easy-digital-downloads.php')) {
			?>
				<div class="notice notice-error is-dismissible">
					<p>
						<img style="width:47px;height:47px;vertical-align:middle;margin-right:8px"
							src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__))) . 'img/edd.svg'; ?>">
						<?php esc_html_e('Tarokina Restriction requires Easy Digital Downloads to function.', 'tarokina-pro'); ?>
						<a href="<?php echo admin_url('/plugin-install.php?s=easy%20digital%20downloads&tab=search&type=term'); ?>">
							<?php esc_html_e('Please install Easy Digital Downloads', 'tarokina-pro'); ?>&nbsp;»
						</a>
					</p>
				</div>
			<?php
			}

			// Verificar si se necesita WooCommerce
			if ($tkta_ecommerce == 'woo' && is_plugin_inactive('woocommerce/woocommerce.php')) {
			?>
				<div class="notice notice-error is-dismissible">
					<p>
						<img style="width:47px;height:47px;vertical-align:middle;margin-right:8px"
							src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__))) . 'img/woo.png'; ?>">
						<?php esc_html_e('Tarokina Restriction requires WooCommerce to function.', 'tarokina-pro'); ?>
						<a href="<?php echo admin_url('/plugin-install.php?s=woocommerce&tab=search&type=term'); ?>">
							<?php esc_html_e('Please install WooCommerce', 'tarokina-pro'); ?>&nbsp;»
						</a>
					</p>
				</div>
			<?php
			}

			// Verificar si se necesita configurar el plugin de ecommerce
			if ($tkta_ecommerce == 'none' || $tkta_ecommerce === false) {
			?>
				<div class="notice notice-error is-dismissible">
					<p>
						<img style="width:47px;height:47px;vertical-align:middle;margin-right:8px"
							src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__))) . 'img/candado.png'; ?>">
						<b>Tarokina Restriction</b>&nbsp;-&nbsp;
						<?php esc_html_e('Select which e-commerce plugin you want to work with, Woocommerce or Easy Digital Downloads.', 'tarokina-pro'); ?>
						<a class="button button-secondary"
							href="<?php echo admin_url('edit.php?post_type=tarokkina_pro&page=crb_carbon_fields_container_options.php'); ?>">
							<?php esc_html_e('Select', 'tarokina-pro'); ?>&nbsp;»
						</a>
					</p>
				</div>
		<?php
			}
		}
	}


	// Tarokina install demo
	public function TkNaP_install_demo()
	{

		if (isset($_POST['demoinstall']) && $_POST['demoinstall'] == 'yes') {
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/install.php';
			update_option('tarokki_install_demo', 'yes');
		}
	}


	// Instalador Create Deck Demo
	public function TkNaP_install_deck_demo()
	{
		if (isset($_POST['demoinstall']) && $_POST['demoinstall'] == 'yes') {
			wp_insert_term(
				'Arnelio',
				'tarokkina_pro-cat'
			);
		}
	}





	// Update plugin desde el exterior
	public function tkina_upgrader_process_complete($upgrader_object, $options)
	{
		$thisPlugin = 'tarokina-pro/tarokina-pro.php';

		if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset($options['plugins'])) {
			foreach ($options['plugins'] as $plugin) {
				if ($plugin == $thisPlugin) {
					error_log('UPDATE EXTERNAL - tarokina : ' . __FILE__ . ' en la línea ' . __LINE__);
					update_option('tkna_pro_modal_update', 'yes', 'yes');
					update_option('tkna_notice_updatePlugin_clear_cache', 'yes', 'yes');
					delete_option('_tarokki_checkCartas');
				}
			}
		}
	}


	// Update plugin desde un archivo ZIP
	public function tkina_upgrader_post_install($response, $hook_extra, $result)
	{
		$thisPlugin = 'tarokina-pro/tarokina-pro.php';

		if (isset($result['destination_name'])) {
			if ($result['destination_name'] == dirname($thisPlugin)) {
				error_log('UPDATE ZIP - tarokina : ' . __FILE__ . ' en la línea ' . __LINE__);
				update_option('tkna_pro_modal_update', 'yes', 'yes');
				update_option('tkna_notice_updatePlugin_clear_cache', 'yes', 'yes');
				delete_option('_tarokki_checkCartas');
			}
		}

		return $response;
	}


	// Notice Borrar cache despues de actualizar
	public function TkNaP_notice_updatePlugin_clear_cache()
	{

		echo '<div class="content_btnCardIds notice notice-warning is-dismissible">
			<img style="width:57px;height:27px;margin-right:8px;float:left;margin-top:4px;" src="' . esc_url(plugin_dir_url(dirname(__FILE__))) . 'img/icon_double.svg">
			<p><strong>Tarokina Pro - </strong>' . esc_html__('It is recommended to clear the cache following an update.', 'tarokina-pro') . ' &nbsp;&nbsp;<a class="btnCardIds" href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=0&page=crb_carbon_fields_container_tarots.php&cids=1') . '">' . esc_html__('Clear cache', 'tarokina-pro') . '</a></p></div>';
	}


	// no-cache
	public function TkNaP_noCache()
	{
		$ids_restriction = (get_option('tkna_restrict_post_id') !== false) ? get_option('tkna_restrict_post_id') : array();
		$post = get_posts(array(
			'name' => pathinfo($_SERVER['REQUEST_URI'])['filename']
		));
		if ($post !== array()) {
			if (in_array($post[0]->ID, $ids_restriction)) {
				header("Cache-Control: no-cache");
			}
		}
	}


	// Array restriction post ids
	public function TkNaP_restriction_ids()
	{

		$tarots = get_option('tkna_restrict_post_id', []);
		if (has_shortcode(get_the_content(), 'tarot_pro')) {
			if (!in_array(get_the_ID(), $tarots)) {
				array_push($tarots, get_the_ID());
				update_option('tkna_restrict_post_id', $tarots);
			}
		} else {
			if (($id = array_search(get_the_ID(), $tarots)) !== false) {
				unset($tarots[$id]);
			}
			update_option('tkna_restrict_post_id', $tarots);
		}
	}



	// LINK Review in web for Tarokina Pro
	public function TkNaP_pro_admin_footer($text)
	{
		global $current_screen;

		if ($current_screen->id !== 'tarokkina_pro_page_crb_carbon_fields_container_tarots') {
			$url  = 'https://arnelio.com/downloads/tarokina-pro/#respond';
			$text = sprintf(
				wp_kses(
					__('Please rate %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">arnelio.com</a> to help us spread the word. Thank you from the Arnelio team!', 'tarokina-pro'),
					[
						'a' => [
							'href'   => [],
							'target' => [],
							'rel'    => [],
						],
					]
				),
				'<strong>Tarokina Pro</strong>',
				$url,
				$url
			);

			return '<div class="footerReview">' . $text . '</div>';
		}
	}

	//////////////////////////////////////////////////////////////////////////////////



	//Menú 100 tarots en Tarots
	public function TkNaP_menu_top_tarots()
	{

		$pg = (isset($_GET['tarots'])) ? sanitize_text_field($_GET['tarots']) : 0;
		echo '
				<div class="containermenuT">
				<a class="menuT button button-' . ($pg == "0" ? 'primary' : 'secondary') . '" href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=0&page=crb_carbon_fields_container_tarots.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;1-10</a>

				<a class="menuT button button-' . ($pg == "2" ? 'primary' : 'secondary') . '"  href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=2&page=crb_carbon_fields_container_tarots2.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;11-20</a>

				<a class="menuT button button-' . ($pg == "3" ? 'primary' : 'secondary') . '"  href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=3&page=crb_carbon_fields_container_tarots3.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;21-30</a>

				<a class="menuT button button-' . ($pg == "4" ? 'primary' : 'secondary') . '"  href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=4&page=crb_carbon_fields_container_tarots4.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;31-40</a>

				<a class="menuT button button-' . ($pg == "5" ? 'primary' : 'secondary') . '"  href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=5&page=crb_carbon_fields_container_tarots5.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;41-50</a>

				<a class="menuT button button-' . ($pg == "6" ? 'primary' : 'secondary') . '"  href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=6&page=crb_carbon_fields_container_tarots6.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;51-60</a>

				<a class="menuT button button-' . ($pg == "7" ? 'primary' : 'secondary') . '"  href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=7&page=crb_carbon_fields_container_tarots7.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;61-70</a>

				<a class="menuT button button-' . ($pg == "8" ? 'primary' : 'secondary') . '"  href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=8&page=crb_carbon_fields_container_tarots8.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;71-80</a>

				<a class="menuT button button-' . ($pg == "9" ? 'primary' : 'secondary') . '"  href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=9&page=crb_carbon_fields_container_tarots9.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;81-90</a>

				<a class="menuT button button-' . ($pg == "10" ? 'primary' : 'secondary') . '"  href="' . admin_url('edit.php?post_type=tarokkina_pro&tarots=10&page=crb_carbon_fields_container_tarots10.php') . '">' . __('Tarots', 'tarokina-pro') . '&nbsp;91-100</a>
				<div class="animation start-home"></div>
				</div>
				';
	}



	// Insertar Tarots en submenu de custom post type
	public function TkNaP_link_submenu_tarots()
	{
		if (get_option('_tkna_more_tarots')) {
			global $submenu;
			$urlTarots = (isset($_GET['tarots'])) ? sanitize_text_field($_GET['tarots']) : '';
			$urlPost = (isset($_GET['post'])) ? sanitize_text_field($_GET['post']) : '';
			if ($urlTarots == '' || $urlPost !== '') {
				$link = admin_url('edit.php?post_type=tarokkina_pro&tarots=0&page=crb_carbon_fields_container_tarots.php');
				$submenu['edit.php?post_type=tarokkina_pro'][] = array(__('Tarots', 'tarokina-pro'), 'manage_options', $link);
			}
		}
	}



	// Borrar Todos los archivos vista.php
	public function TkNaP_clear_cache()
	{

		$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots/vista.php', 'w');
		fwrite($del_vista, '');
		fclose($del_vista);

		if (get_option('_tkna_more_tarots')) {

			$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots2/vista2.php', 'w');
			fwrite($del_vista, '');
			fclose($del_vista);

			$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots3/vista3.php', 'w');
			fwrite($del_vista, '');
			fclose($del_vista);

			$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots4/vista4.php', 'w');
			fwrite($del_vista, '');
			fclose($del_vista);

			$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots5/vista5.php', 'w');
			fwrite($del_vista, '');
			fclose($del_vista);

			$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots6/vista6.php', 'w');
			fwrite($del_vista, '');
			fclose($del_vista);

			$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots7/vista7.php', 'w');
			fwrite($del_vista, '');
			fclose($del_vista);

			$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots8/vista8.php', 'w');
			fwrite($del_vista, '');
			fclose($del_vista);

			$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots9/vista9.php', 'w');
			fwrite($del_vista, '');
			fclose($del_vista);

			$del_vista = fopen(TAROKINA_ADMIN_PATH . 'tarots10/vista10.php', 'w');
			fwrite($del_vista, '');
			fclose($del_vista);
		}
		// archivo vista carta vacio
		$del_vistaCarta = fopen(TAROKINA_ADMIN_PATH . 'cartas/carta_vista.php', 'w');
		fwrite($del_vistaCarta, '');
		fclose($del_vistaCarta);
	}

	//////////////////////////////////////////////////////////////////////////////////


	// Convertir basic en expert
	function TkNaP_basic_to_expert()
	{

		$args = array(
			'post_type' => 'tarokkina_pro',
			'fields' => 'ids',
			'post_status' => 'publish',
			'posts_per_page' => -1
		);

		$Tarotsids = new WP_Query($args);
		$ids = $Tarotsids->posts;


		$tarots =  get_option('tarokki_tarotsAll_array');
		$tarots = ($tarots) ? $tarots : array();

		foreach ($ids as $id) {

			foreach ($tarots as $tarot) {

				$id_unique = trim($tarot['tkta_id']);


				// Pruebas ambiar datos
				if (metadata_exists('post', $id, '_tkta_text_' . $id_unique . 'basic0')) {

					$basic0 = get_post_meta($id, '_tkta_text_' . $id_unique . 'basic0');
					update_post_meta($id, '_tkta_text_' . $id_unique . 'expert0', $basic0[0]);
					update_post_meta($id, 'tkta_text_' . $id_unique . 'expert0', $id . $id_unique . 'expert0');

					delete_post_meta($id, '_tkta_text_' . $id_unique . 'basic0');
					delete_post_meta($id, 'tkta_text_' . $id_unique . 'basic0');
				}
				if (metadata_exists('post', $id, '_tkta_text_' . $id_unique . 'basic1')) {

					$basic1 = get_post_meta($id, '_tkta_text_' . $id_unique . 'basic1');
					update_post_meta($id, '_tkta_text_' . $id_unique . 'inve_expert0', $basic1[0]);
					update_post_meta($id, 'tkta_text_' . $id_unique . 'inve_expert0', $id . $id_unique . 'inve_expert0');

					delete_post_meta($id, '_tkta_text_' . $id_unique . 'basic1');
					delete_post_meta($id, 'tkta_text_' . $id_unique . 'basic1');
				}
			}
		};
		wp_reset_postdata();
	}


	// Crear array cards ids
	public function TkNaP_cards_ids()
	{
		require plugin_dir_path(dirname(__FILE__)) . 'includes/cards_ids.php';
	}


	// Notice Clear cache button
	public function TkNaP_notice_clear_cache()
	{
		?>
		<div style="margin-left:0;margin-right:22px" class="notice notice-success settings-success is-dismissible">
			<p style="padding: 8px">
				<svg class="svg-icon" style="width:25px; height:25px; vertical-align:bottom;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
					<path d="M837.818182 915.549091h-131.956364c-12.8 0-23.272727-10.472727-23.272727-23.272727v-58.181819c0-4.189091-8.843636-11.636364-23.272727-11.636363s-23.272727 7.447273-23.272728 11.636363v58.181819c0 12.8-10.472727 23.272727-23.272727 23.272727H465.454545c-12.8 0-23.272727-10.472727-23.272727-23.272727s10.472727-23.272727 23.272727-23.272728h124.043637v-34.909091c0-32.581818 30.72-58.181818 69.818182-58.181818s69.818182 25.6 69.818181 58.181818v34.909091H814.545455v-232.727272H209.454545v232.727272h93.789091c12.8 0 23.272727 10.472727 23.272728 23.272728s-10.472727 23.272727-23.272728 23.272727H186.181818c-12.8 0-23.272727-10.472727-23.272727-23.272727v-279.272728c0-12.8 10.472727-23.272727 23.272727-23.272727h651.636364c12.8 0 23.272727 10.472727 23.272727 23.272727v279.272728c0 12.8-10.472727 23.272727-23.272727 23.272727z" fill="#040000" />
					<path d="M837.818182 636.276364H186.181818c-12.8 0-23.272727-10.472727-23.272727-23.272728v-69.818181c0-11.403636 8.145455-20.945455 19.316364-22.807273l244.596363-43.054546V124.276364c0-26.065455 23.970909-46.545455 54.225455-46.545455h62.138182c30.487273 0 54.225455 20.48 54.225454 46.545455V477.090909l244.596364 43.054546c11.170909 1.861818 19.316364 11.636364 19.316363 22.807272v69.818182A23.738182 23.738182 0 0 1 837.818182 636.276364z m-628.363637-46.545455h605.09091v-26.996364l-244.596364-43.054545a23.202909 23.202909 0 0 1-19.316364-22.807273V126.138182a13.172364 13.172364 0 0 0-7.68-2.094546h-62.138182c-3.723636 0-6.516364 1.163636-7.68 2.094546v370.269091c0 11.403636-8.145455 20.945455-19.316363 22.807272L209.454545 562.734545v26.996364z" fill="#040000" />
				</svg>
				<strong><?php echo esc_html('Cache cleared', 'tarokina-pro'); ?>.</strong>
			</p>
		</div>
	<?php
		delete_option('tkna_notice_updatePlugin_clear_cache');
	}



	// Subir Deck
	public function tkina_upload_decks()
	{

		// Variable para verificar si se ha procesado algún archivo
		$file_processed = false;

		// Verifica si se han enviado archivos
		if (isset($_FILES['files'])) {

			// Itera sobre cada archivo
			foreach ($_FILES['files']['error'] as $key => $error) {

				// Comprueba si ha ocurrido un error al subir el archivo
				if ($error !== UPLOAD_ERR_OK) {
					// Si no se ha seleccionado ningún archivo, termina la función
					continue;
				}

				// Ruta temporal del archivo subido
				$zip_file = $_FILES['files']['tmp_name'][$key];

				// Obtiene el nombre del archivo ZIP
				$zip_name = basename($_FILES['files']['name'][$key], '.zip');

				// Directorio donde se extraerá el archivo ZIP
				$extract_dir = wp_upload_dir()['basedir'] . '/tarokkina_pro/';

				// Crea el directorio si no existe
				if (!file_exists($extract_dir)) {
					mkdir($extract_dir, 0777, true);
				}

				// Extrae el archivo ZIP
				$zip = new ZipArchive;
				if ($zip->open($zip_file)) {
					for ($i = 0; $i < $zip->numFiles; $i++) {
						$filename = $zip->getNameIndex($i);
						$fileinfo = pathinfo($filename);
						copy("zip://" . $zip_file . "#" . $filename, $extract_dir . $fileinfo['basename']);
					}
					$zip->close();
				}

				// Verifica si el término existe
				if (!term_exists($zip_name, 'tarokkina_pro-cat')) {
					// El término no existe, así que lo creamos
					wp_insert_term($zip_name, 'tarokkina_pro-cat');
				}

				// Itera sobre las imágenes extraídas
				$images = glob($extract_dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
				foreach ($images as $image_file) {
					// Obtiene el nombre de la imagen sin la extensión
					$post_title = pathinfo($image_file, PATHINFO_FILENAME);

					// Agrega el nombre del término al slug del post para hacerlo único
					$post_slug = sanitize_title($zip_name . '-' . $post_title);

					// Comprueba si ya existe un post con el mismo slug
					$args = array(
						'post_type' => 'tarokkina_pro',
						'name' => $post_slug,
						'tax_query' => array(
							array(
								'taxonomy' => 'tarokkina_pro-cat',
								'field'    => 'slug',
								'terms'    => $zip_name,
							),
						),
					);

					$query = new WP_Query($args);
					if (!$query->have_posts()) {
						// Si no existe un post con el mismo slug, crea una nueva publicación
						$post_id = wp_insert_post(array(
							'post_title' => $post_title, // Nombre de la publicación
							'post_name' => $post_slug, // Slug de la publicación
							'post_type' => 'tarokkina_pro',
							'post_status' => 'publish',
						));

						// Asigna la taxonomía a la publicación
						wp_set_object_terms($post_id, $zip_name, 'tarokkina_pro-cat');

						// Establece la imagen destacada
						$filetype = wp_check_filetype(basename($image_file), null);

						$file_array = array(
							'name' => basename($image_file),
							'tmp_name' => $image_file,
						);

						// Incluye el archivo necesario para wp_handle_sideload
						require_once(ABSPATH . 'wp-admin/includes/file.php');

						// Comprueba si hay errores en la subida
						$check = wp_handle_sideload($file_array, array('test_form' => false));
						if ($check && !isset($check['error'])) {
							// Inserta el archivo en la biblioteca de medios
							$args = array(
								'guid' => $check['url'],
								'post_mime_type' => $filetype['type'],
								'post_title' => preg_replace('/\.[^.]+$/', '', basename($image_file)),
								'post_content' => '',
								'post_status' => 'inherit'
							);
							$attach_id = wp_insert_attachment($args, $check['file'], $post_id);

							require_once(ABSPATH . 'wp-admin/includes/image.php');
							$attach_data = wp_generate_attachment_metadata($attach_id, $check['file']);
							wp_update_attachment_metadata($attach_id, $attach_data);
							set_post_thumbnail($post_id, $attach_id);
						} else {
							// Informa del error y termina la ejecución del script
							die($check['error']);
						}

						// Se ha procesado un archivo
						$file_processed = true;
					}
				}
				delete_directory($extract_dir);
			}

			// Redirige a la URL especificada en caso de éxito
			if ($file_processed) {
				wp_redirect(admin_url('edit-tags.php?taxonomy=tarokkina_pro-cat&post_type=tarokkina_pro'));
				exit;
			}
		}
	}




	// Formulario de carga de archivos
	public function display_upload_form()
	{
	?>
		<div class="upload_decks_content new-option">
			<div class="upload_decks_sub_content">
				<div class="upload_decks__head">
					<?php echo __('Please upload a deck of cards in a zip file.', 'tarokina-pro') ?>
				</div>

				<form method="post" enctype="multipart/form-data">
					<div class="upload_deck__content">
						<input type="file" name="files[]" id="file" accept=".zip" multiple>
						<input class="button" type="submit" value="<?php echo __('Upload', 'tarokkina-pro') ?>">
					</div>
				</form>

				<br>
				<strong><?php _e('Please find below instructions on how to create the ZIP file', 'tarokina-pro'); ?>:</strong>
				<ul class="">
					<li> - <?php _e('Please create an empty folder and enter the images of the cards you wish to upload.', 'tarokina-pro'); ?></li>
					<li> - <?php _e('The folder name will be used as the deck name.', 'tarokina-pro'); ?></li>
					<li> - <?php _e('The name of each image will be used as the title of each card.', 'tarokina-pro'); ?></li>
					<li> - <?php _e('Please enter the name of each image with blank spaces. As an example, consider the following: The file name for the image of the Hanged Man is "The Hanged Man.jpg."', 'tarokina-pro'); ?></li>
					<li> - <?php _e('Please compress the folder. Please ensure that the images are located in the root of the ZIP file and not in a subfolder.', 'tarokina-pro'); ?></li>
				</ul>
			</div>
		</div>
<?php
	}






	// Elimina la imagen destacada cuando se elimina una publicación
	public function remove_featured_image($post_id)
	{
		// Comprueba si la publicación es de tipo 'tarokkina_pro'
		if (get_post_type($post_id) == 'tarokkina_pro') {
			// Obtiene el ID de la imagen destacada
			$thumbnail_id = get_post_thumbnail_id($post_id);

			// Si hay una imagen destacada, la elimina
			if ($thumbnail_id) {
				wp_delete_attachment($thumbnail_id, true);
			}
		}
	}

	/**
	 * Show cache cleared notice
	 */
	public function TkNaP_cache_cleared_notice()
	{
		$notice_data = get_transient('tarokina_cache_cleared_notice');
		
		if ($notice_data && is_array($notice_data)) {
			$type = isset($notice_data['type']) ? sanitize_text_field($notice_data['type']) : 'info';
			$message = isset($notice_data['message']) ? esc_html($notice_data['message']) : '';
			
			if (!empty($message)) {
				echo '<div class="notice notice-' . $type . ' is-dismissible">';
				echo '<p><strong>Tarokina Pro:</strong> ' . $message . '</p>';
				echo '</div>';
				
				// Delete the transient after showing it
				delete_transient('tarokina_cache_cleared_notice');
			}
		}
	}
}
