<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       tarokkina_pro/fer
 * @since      1.0.0
 *
 * @package    Tarokkina_pro
 * @subpackage Tarokkina_pro/public
 */

class Tarokkina_pro_Public {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	

   // TkNaP_Vista del shortcode
   // Crear los nuevos postmeta con el nombre del tarot, para poder acceder a ellos aunque no tenga el id.
   // después, crear un if, si el nombre está en el shortcode pues se busca el postmeta con el nombre y si no, de la manera tradicional llamando al postmeta por su id.

	public function TkNaP_pro_shortcode(){

		if (!class_exists('Tarokkina_free_Public')) {
			add_shortcode( 'tarot', 'TkNaP_add_shortco_pro');
		}
		add_shortcode( 'tarot_pro', 'TkNaP_add_shortco_pro');

		
		function TkNaP_add_shortco_pro( $atts = array(), $content = null, $tag = ''){

			extract(shortcode_atts( array(
				'name' => ''
			), $atts));
			

			if (get_option('_tkna_more_tarots')) {
				$tarotsNames = get_option('tarokki_tarot_names_array');
				$tarotsNames = ($tarotsNames) ? $tarotsNames : array() ;
			}else{
				$tarotsNames = get_option('tarokki_tarot_names');
				$tarotsNames = ($tarotsNames) ? $tarotsNames : array() ;
			}


			if (in_array('tarokina_' .$name, $tarotsNames)) {
				$global_transient =  get_option( 'tarokina_' .$name )['tarokina_' .$name ];
			}

			$tarotOptions = (isset($global_transient)) ? $global_transient[0] : array();



			// none
			$BtnPosition = $tarotOptions['tkta_button_position'] ?? 1;

			$start = $tarotOptions['tkta_start'] ?? 'selector';
			$meanings_position = $tarotOptions['tkta_meanings_position'] ?? 0;

			$back_spread = $tarotOptions['tkna_back_spread'] ?? false; // true false


			if ($start == 'selector') {
				$start_dis_1 = 'none';
				$start_dis_2 = 'block';
			}else{
				$start_dis_1 = 'flex';
				$start_dis_2 = 'none';
			}
					

			if ($tarotOptions) {

				$deck = $tarotOptions['tkta_barajas'];
			    $deck = ($deck) ? $deck : '' ;


				// Creando los arrays de las cartas
				$checkCartas = get_option('_tarokki_checkCartas');
				$http_ssl =  get_option('_tkna_http_ssl') ?? false;

				if ( $http_ssl == 'yes') {
					$Ids_cards = (get_option($deck .'_deck_tarokki_ssl')) ? get_option($deck .'_deck_tarokki_ssl') : get_option($deck .'_deck_tarokki') ;
				}else{
					$Ids_cards = (get_option($deck .'_deck_tarokki')) ? get_option($deck .'_deck_tarokki') : false ;
				}
				

				 if (!$checkCartas ) {
					update_option('_tarokki_checkCartas','yes' );
			    }

				$type = $tarotOptions['tkta_spread'] ?? '';
				$type_N = ($type =='0cards') ? 1 : (int) substr($type, 0, -5) ;
				$cssYesNo = ($type =='0cards') ? '1cards' : $type ;



				$sp_bg_color = $tarotOptions['tkta_background_color'] ?? '';
				$sp_title_color = $tarotOptions['tkta_title_spread_color'] ?? '';
				$sp_text_color = $tarotOptions['tkta_texto_spread_color'] ?? '';

				// Boton
				$btn_BackColor = $tarotOptions['tkta_btn_back_color'] ?? '';
				$btn_TextColor = $tarotOptions['tkta_btn_text_color'] ?? '';
				$text_shuffle = $tarotOptions['tkta_shuffle'] ?? '';
				$text_shuffle = (!$text_shuffle) ? 'Shuffle' : $text_shuffle;

                // Imagen de fondo
				$img_fondo = ($tarotOptions['tkta_image_background'] !== array()) ? $tarotOptions['tkta_image_background'][0] : array();
				$ancho = ($tarotOptions['tkta_width']== null) ? 'max-width:100%' : 'max-width:'.$tarotOptions['tkta_width'].'px' ;

				$spreadImg = wp_get_attachment_image_src( $img_fondo, 'full')[0] ?? array();
				$spreadImg = ( $img_fondo !== array()) ? 'background:url('.$spreadImg.');': '';
				

				$spreadImgOpac = $tarotOptions['tkta_image_transparent'];
				$spreadImgOpac = ($spreadImgOpac)? $spreadImgOpac / 100 : 0 ;
				$opacity = ($spreadImg == '') ? ';opacity:1 ' : ';opacity: '.$spreadImgOpac ;

				$tarot_margin_top = $tarotOptions['tkta_tarot_margin_top'] ?? '';
				$tarot_margin_bottom = $tarotOptions['tkta_tarot_margin_bottom'] ?? '';
				$margin_spread = $tarotOptions['tkta_margin_spread'] ?? 0;

				$nombre = tark_pro_x8z_urlText($tarotOptions['tkta_name']) ?? '';

				$deck = $tarotOptions['tkta_barajas'] ?? '';

				$orden = (isset($tarotOptions['tkta_card_order'])) ? $tarotOptions['tkta_card_order'] : '' ;

				$backface = ($tarotOptions['tkta_image_backface'] != array()) ? wp_get_attachment_image_src( $tarotOptions['tkta_image_backface'][0], 'full') : '';

				$mode = $tarotOptions['tkta_mode'] ?? '';
				$ver_meanings = $tarotOptions['tkta_meanings'] ?? false;

				// TAROKINA RESTRICTION 
				$tkna_shop_id = $tarotOptions['tkna_shop_id'] ?? '';
				$tkta_ecommerce = get_option('_tkta_ecommerce','edd');
				$tkta_ecommerce = ($tkta_ecommerce == 'none') ? 'edd' : $tkta_ecommerce ;
				// https://decodecms.com/url-para-agregar-varios-productos-al-carrito-de-woocommerce/
				// https://woocommerce.github.io/code-reference/files/woocommerce-includes-class-wc-order.html#source-view.292

				// https://developer.woocommerce.com/
				// https://woocommerce.github.io/code-reference/hooks/hooks.html
				// https://www.businessbloomer.com/woocommerce-get-cart-info-total-items-etc-from-cart-object/

	
				// crear un array con los ids del producto y el shortcode
				$productid_post_id = get_option('tkna_productid_post_id', []) ?? [];
				if (! in_array( get_the_ID(), $productid_post_id ) && $tkna_shop_id !== '' ) {
					$productid_post_id += [ get_the_ID() => $tkna_shop_id ];
				}
	
				if ( $tkna_shop_id == '' ) {
					unset($productid_post_id[ get_the_ID() ]);
				}

				update_option( 'tkna_productid_post_id', $productid_post_id );	
	

				// Size
				$tkta_sizecards = $tarotOptions['tkta_sizecards'] ?? '';
				$size_cards = ($tarotOptions['tkta_sizecards'] != '') ? 'style=max-width:'.TkNa_size_cards($spread=$type,$size=$tkta_sizecards).'px' : '' ;

				if ($type == '0cards' || $type == '1cards') {
					$size_1card = ($tarotOptions['tkta_sizecards'] != '') ? 'style="width:'.TkNa_size_cards($spread=$type,$size=$tkta_sizecards).'px"' : '' ;
				}else{
					$size_1card = '';
				}



				$tablero_num = ($tarotOptions['tkta_tablero_num'] !== '') ? $tarotOptions['tkta_tablero_num'] : 22 ;




				ob_start();
				$addon = 'tarokki-'.$tarotOptions['_type'];
				$addon_ = str_replace("-", "_", $tarotOptions['_type']);
				$addon_n = $tarotOptions['_type'];
				$mycard_arr = (array) get_option('mycard_arr');
				$addon_mycard = (isset($mycard_arr[$addon_n])) ? $mycard_arr[$addon_n] : '' ;
				$tablero = $tarotOptions['tkta_tablero'];


				// Developer
				$devp = $tarotOptions['tkna_devp'] ?? '';
				if ( $devp == 1 && current_user_can( 'manage_options' )) {
					$addon = 'tarokki-tarokina-pro';
					$type = "1cards";
					$type_N = "1";
					$cssYesNo = '1cards';
					$tkta_sizecards = 's';
					$tablero = 'd4';
					$ver_meanings = false;
				}


				
				// Comprobar si el addon está activo
					if (function_exists('TkNaP_wp_content_')) {
						TkNaP_wp_content_();
					}

					if ( $addon == 'tarokki-classic_spreads' && function_exists('wp_content_classic_spreads' ) ) {
						wp_content_classic_spreads();
					}

					if ( $addon == 'tarokki-custom_spreads' && function_exists('wp_content_custom_spreads' ) ) {
						wp_content_custom_spreads();
					}
			
					if (function_exists('wp_content_edd_restriction_tarokina')) {
						wp_content_edd_restriction_tarokina();
					}

				


				// si addon es igual a tarokki-tarokina-pro ó si existe la función activar en un addon desactivado ó ... 
				if ( $addon == 'tarokki-tarokina-pro' ||!function_exists( 'activate_tarokki_' . $addon_) || $addon !== 'tarokki-tarokina-pro' && $addon_mycard !== MYCARD ) {

					if ($addon_n !== 'tarokina-pro' && $tablero !== 'd4') {
						$cssYesNo = substr($cssYesNo, 0, -1);
					}

					$clickOrden = [];
					for ($i=1; $i <= $type_N ; $i++) { 
						$clickOrden [] =  (int)$i;
					}
					

					// CSS
						$css_inline = plugin_dir_url(dirname(__DIR__)).'tarokina-pro/css/'.$type_N.'cards.css';
						echo "<style>@import url($css_inline);</style>";

				}else{

					include_once plugin_dir_path(dirname(__DIR__)).$addon.'/template/order/'.$type.'.php';

					$orden = explode(",", $orden);
					$clickOrden = [];
					foreach ($orden as $value) {
						$clickOrden [] =  (int)$value;
					};

					// CSS
					$css_inline = plugin_dir_url(dirname(__DIR__)).$addon.'/css/'.$type.'.css';
					echo "<style>@import url($css_inline);</style>";

				}

		

				if ($tablero == 'd1' || $tablero == 'd2') {
					include plugin_dir_path(dirname(__DIR__)). 'tarokina-pro/public/template/'.$tkta_ecommerce.'/template_init.php';
				}elseif($tablero == 'd3'){
					include plugin_dir_path(dirname(__DIR__)). 'tarokina-pro/public/template/'.$tkta_ecommerce.'/template_click.php';
				}else{
					include plugin_dir_path(dirname(__DIR__)). 'tarokina-pro/public/template/developer/template_init.php';
				}

				if (!is_admin() && $Ids_cards !== false) {
					wp_enqueue_script( 'anime', plugin_dir_url( __FILE__ ) . 'js/anime.min.js', array(), TAROKKINA_PRO_VERSION, true );
					wp_enqueue_script( 'tarokina', plugin_dir_url( __FILE__ ) . 'js/'.$tablero.'-min.js', array('anime','jquery'), TAROKKINA_PRO_VERSION, true );
				}


				$arr_tarokina_pro = (isset($mycard_arr['tarokina-pro'])) ? $mycard_arr['tarokina-pro'] : '' ;
				wp_localize_script( 'tarokina', 'ajax_tarot', array(
					'url'    => admin_url( 'admin-ajax.php' ),
					'nonce'  => wp_create_nonce( 'tarotpro-nonce' ),
					'action' => 'e-tarot',
					'refe' => $refe,
					'myCard' => 'PGEgc3R5bGU9ImRpc3BsYXk6YmxvY2sgIWltcG9ydGFudDtvcGFjaXR5OjEgIWltcG9ydGFudDt2aXNpYmlsaXR5OiB2aXNpYmxlICFpbXBvcnRhbnQ7Zm9udC1zaXplOjEzcHggIWltcG9ydGFudDtiYWNrZ3JvdW5kOiByZ2JhKDI1NSwgMjU1LCAyNTUsIDAuNSk7Y29sb3I6ICMzMzMzMzMgIWltcG9ydGFudDtib3JkZXItcmFkaXVzOiAxMHB4O21hcmdpbjogOHB4IGF1dG8gIWltcG9ydGFudDtwb3NpdGlvbjogc3RhdGljICFpbXBvcnRhbnQ7IiBocmVmPSJodHRwczovL2FybmVsaW8uY29tL2Rvd25sb2Fkcy90YXJva2luYS1wcm8iIHRhcmdldD0iX2JsYW5rIj5hcm5lbGlvLmNvbTwvYT4=',
					'wp_content' => $arr_tarokina_pro
				) );

				$output = ob_get_contents();
				ob_end_clean();
				return $output;

			}

		}
	}



////////////////////   Ajax Tarot 2 ////////////////////


function TkNaP_pro_x8z_tarotAjax2() {

	$IdsPost = explode(",", sanitize_text_field($_POST['cards']));
	$IdsPost_N = count($IdsPost);
	$http_ssl =  get_option('_tkna_http_ssl') ?? false;
	$flipType = explode(",", sanitize_text_field($_POST['flip']));
	$nameTarot = sanitize_text_field($_POST['nametarot']);
	$tarotsArr =  get_option( 'tarokina_' .$nameTarot )['tarokina_' .$nameTarot ];
	$id_unique = trim($tarotsArr[0]['tkta_id']);
	$tarotMode =  $tarotsArr[0]['tkta_mode'];
	$ver_meanings = $tarotsArr[0]['tkta_meanings'];
	$type = $tarotsArr[0]['tkta_spread'] ?? '';
	$type_N = ($type =='0cards') ? 1 : (int) substr($type, 0, -5) ;
	$result_color = $tarotsArr[0]['tkta_result_color'] ?? '';
	$result_color = ($result_color == '') ? '' : 'color:'. $result_color ;

	$TextYes = $tarotsArr[0]['tkta_text_yes'] ?? '';
	$TextYes = ($TextYes != '') ? $TextYes : esc_html__('Yes','tarokina-pro');

	$TextNo = $tarotsArr[0]['tkta_text_no'] ?? '';
	$TextNo = ($TextNo != '') ? $TextNo : esc_html__('No','tarokina-pro');

	$tabsResult = $tarotsArr[0]['tkta_result_tabs'] ?? 'one';

	if ($ver_meanings == true) {
	   $title_leyend = $tarotsArr[0]['tkta_legend'];
	   $title_leyend = ($title_leyend) ?'<h3 class="meaningsTitles">'.esc_html($title_leyend).'</h3>' :  '' ;
	}else{
	   $title_leyend = ''; 
	}

	$text_button_volver = $tarotsArr[0]['tkta_pro_text_button_volver'];

// Boton
   $A_btn_BackColor = $tarotsArr[0]['tkta_btn_back_color'];
   $A_btn_BackColor = ($A_btn_BackColor) ? $A_btn_BackColor : '';

   $A_btn_TextColor = $tarotsArr[0]['tkta_btn_text_color'];
   $A_btn_TextColor = ($A_btn_TextColor) ? $A_btn_TextColor : '';

   if ($A_btn_BackColor !== ''){
	   $A_botonColor = 'style="background:' . $A_btn_BackColor . ';color:' . $A_btn_TextColor.'"';
   }else{
	   $A_botonColor = '';
   }

   // Developer
   $devp = $tarotsArr[0]['tkna_devp'] ?? '';
	if ( $devp == 1 && current_user_can( 'manage_options' )) {
		$title_leyend = '';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/template/texts/developer.php';
   }else{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/template/texts/production.php';
   }

}
/////////////////////////////////////////



     // Remove Shortcodes from vista Category
	function TkNaP_pro_remove_shortcode() {
	if ( !is_admin() && is_home() || !is_admin() && is_category() ) {
		add_shortcode( 'tarot_pro', '__return_false' );
		add_shortcode( 'tarot', '__return_false' );
		}    
		
	}

}
