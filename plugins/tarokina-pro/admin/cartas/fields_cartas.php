<?php
// use Carbon_Fields\Container;
// use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'TkNaP_tarokkina_pro_cartas');

function TkNaP_tarokkina_pro_cartas() {
    $urlPost = isset($_GET['post']) ? sanitize_text_field($_GET['post']) : '';

global $wpdb;
$query_deck = $wpdb->prepare("
    SELECT t.name, t.term_id
    FROM {$wpdb->prefix}terms AS t
    INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id = tt.term_id
    INNER JOIN {$wpdb->prefix}term_relationships AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
    WHERE tr.object_id = %d
    AND tt.taxonomy = %s
", $urlPost, 'tarokkina_pro-cat');

$deck_post = $wpdb->get_results($query_deck)[0]->term_id ?? null;
$deck_post = (int)$deck_post;

$is_init_none = isset($_GET['i']) && $_GET['i'] == '0' ? true : false;

    if (isset($_GET['t_id'])) {
        $tkta_id_url = trim($_GET['t_id']);
        update_option( 'tkta_id_url',$tkta_id_url );
    }else{
        $tkta_id_url = get_option( 'tkta_id_url');
    }

    if ($is_init_none) {
        function tkina_css_init_none() {
           ?>
            <style>
                #postbox-container-2 {
                    display: none !important;
                }
            </style>
            <?php
        }
        add_action('admin_head', 'tkina_css_init_none');
       
    }

    $thumbID = get_post_thumbnail_id( $urlPost);
    $imgPost = wp_get_attachment_url( $thumbID );
    $title = (get_the_title( $urlPost ) !== null) ? get_the_title( $urlPost ) : '' ;

            if (isset($_POST['save']) && $_POST['save']== true || isset($_GET['post_type']) && $_GET['post_type']== 'tarokkina_pro' && isset($_GET['action']) && $_GET['action']== 'edit') {
                
            $style = (get_option('_tkna_more_tarots')) ? 'style="background: transparent;width: 0;"' :
            '' ;

            // llamada a todos los tarots
            require plugin_dir_path( dirname( __DIR__ ) ) . 'includes/tarokkina_pro_all_tarots.php';

            
            $del_vista = fopen( TAROKINA_ADMIN_PATH.'cartas/carta_vista.php', 'w');
            fwrite($del_vista, ''); // Vaciar archivo
            fwrite($del_vista, "<?php if ( ! defined( 'WPINC' ) ) {die;}".PHP_EOL."use Carbon_Fields\Container;use Carbon_Fields\Field;". PHP_EOL);
            fclose($del_vista);

            foreach ($tarots as $key => $tarot) {
                $deks = ($tarot['tkta_barajas'] != '') ? $tarot['tkta_barajas'] : 0 ;
                $id_unique = trim($tarot['tkta_id']);

                
                if ($tkta_id_url == $id_unique ) {
                
                    $tarot = $tarots[$key];
                
                    $tirada = $tarot['tkta_name'];
                    $modes = $tarot['tkta_mode'];
                    $spread = $tarot['tkta_spread'];
                    $spread_N = ($spread =='0cards') ? 1 : (int) substr($spread, 0, -5) ;
                    $hiddenImgYesNo = ($spread =='0cards') ? 'style="height:80px"' : '' ;
                    $addon = $tarot['_type'];
                    $i = substr($spread,-1);
                
                    $mostrar_Refs = $tarot['tkta_meanings'];
                    $Refs = ($mostrar_Refs == false) ? 'style="visibility:hidden;height: 0"' : '' ;
                
                    $TextYes = $tarot['tkta_text_yes'] ?? '';
                    $TextYes = ($TextYes != '') ? $TextYes : esc_html__('Yes','tarokina-pro');
                
                    $TextNo = $tarot['tkta_text_no'] ?? '';
                    $TextNo = ($TextNo != '') ? $TextNo : esc_html__('No','tarokina-pro');
                
                
                    //$addons_inactive = get_option('tkna_addon_inactive');
                    $mycard_arr = (array) get_option('mycard_arr');
                    $mycard_Addon = (isset($mycard_arr[$addon] )) ? $mycard_arr[$addon] : '' ;
                
                
                    // spread image
                    if ( $mycard_Addon !== MYCARD || $addon == 'tarokina-pro') {
                        if ($addon !== 'tarokina-pro') {$spread = substr($spread, 0, -1);}
                        $image_Spread = "".esc_url(plugins_url())."/tarokina-pro/img/spreads/$spread.svg";
                    }else{
                        $image_Spread = "".esc_url(plugins_url())."/tarokki-$addon/img/spreads/$spread.svg";
                    }
                
                    //  Title card top Text
                    $tabTitle = "''.\$title.' - ".$tirada."'" ;
                
                    switch ($modes) {
                        case 'basic':
                                $mode_name = esc_html__('Basic', 'tarokina-pro' );
                                include TAROKINA_ADMIN_PATH. 'cartas/write-basic-subfields.php';
                            break;
                        case 'flip':
                                $mode_name = esc_html__( 'Basic reversed', 'tarokina-pro' );
                                include TAROKINA_ADMIN_PATH. 'cartas/write-flip-subfields.php';    
                            break;
                        case 'expert':
                
                                // En caso de que se haya elegido un spread de 1carta y el modo experto se utilizará el modo básico
                                
                                if ($spread != '0cards' && $spread != '1cards') {
                                    $mode_name = esc_html__( 'Expert', 'tarokina-pro' );
                                    include TAROKINA_ADMIN_PATH. 'cartas/write-expert-subfields.php';
                                }else{
                                    $mode_name = esc_html__('Basic', 'tarokina-pro' );
                                    include TAROKINA_ADMIN_PATH. 'cartas/write-basic-subfields.php'; 
                                }
                                    
                            break;
                        case 'eflip':
                
                            // En caso de que se haya elegido un spread de 1carta y el modo experto se utilizará el modo básico
                            
                            if ($spread != '0cards' && $spread != '1cards') {
                                $mode_name = esc_html__( 'Expert reversed', 'tarokina-pro' );
                                include TAROKINA_ADMIN_PATH. 'cartas/write-eflip-subfields.php';
                            }else{
                                $mode_name = esc_html__( 'Basic reversed', 'tarokina-pro' );
                                include TAROKINA_ADMIN_PATH. 'cartas/write-flip-subfields.php'; 
                            }
                                
                        break;
                    }
                }
            }// Foreach
            include TAROKINA_ADMIN_PATH. 'cartas/carta_vista.php';
            }
};