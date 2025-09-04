<?php
// =============================================================
//                Template public developer
// =============================================================
if ( ! defined( 'WPINC' ) ) {die;}

$TarotName = $tarotOptions['tkta_name'] ?? '';
$plugin = $tarotOptions['tkta_type'] ?? '';
$spread = $tarotOptions['tkta_spread'] ?? '';
$spread_N = ($spread =='0cards') ? 1 : (int) substr($spread, 0, -5) ;
$modes = $tarotOptions['tkta_mode'] ?? '';
$id_unique = trim($tarotOptions['tkta_id']);

// spread image
if ( $plugin == 'tarokina-pro' ) {
    if ($plugin !== 'tarokina-pro') {$spread = substr($spread, 0, -1);}
    $image_Spread = "".esc_url(plugins_url())."/tarokina-pro/img/spreads/$spread.svg";
}else{
    $image_Spread = "".esc_url(plugins_url())."/tarokki-$plugin/img/spreads/$spread.svg";
}


$title_spread = $tarotOptions['tkta_title_spread'];
$title_spread_style = ($sp_title_color) ? 'color:'.$sp_title_color.' !important' : '';
$sub_title_spread = $tarotOptions['tkta_subtitle_spread'];

$sp_text_legend = $tarotOptions['tkta_legend'];
$sp_text_legend = ($sp_text_legend) ? $sp_text_legend : '' ;
$sp_text_button = $tarotOptions['tkta_pro_text_button'];

$rutaBack = plugin_dir_path( dirname( __FILE__ ) );
$rutaFront = plugin_dir_url( dirname( __FILE__ ) );
$size = 'full';

list($r, $g, $b) = sscanf($sp_title_color, "#%02x%02x%02x");


$backfaceDefault =  plugin_dir_url( dirname( __FILE__,2 ) ).'img/back-default.png';
$backfaceImg = ($backface != false) ? $backface[0]  : $backfaceDefault;
$ids_restriction = (empty(get_option('tkna_restrict_post_id'))) ? array() : get_option('tkna_restrict_post_id');
$refe = [];

// Mode Name and Total Text
switch ($modes) {
    case 'basic':
        $mode_name = esc_html__('Basic', 'tarokina-pro' );
        $totalText = 1;
        break;
    case 'flip':
        $mode_name = esc_html__( 'Basic reversed', 'tarokina-pro' );   
        $totalText = 2;
        break;
    case 'expert':
        $mode_name = esc_html__( 'Expert', 'tarokina-pro' );
        $totalText = $spread_N;
        break;
    case 'eflip':
        $mode_name = esc_html__( 'Expert reversed', 'tarokina-pro' ); 
        $totalText = $spread_N * 2;        
    break;
}

if ($Ids_cards == false) {

    echo '<div id="tkna_preloader" style="background:#f2f2f2; color:#676767; margin:50px 0; padding:50px; text-align:center;">
            <div id="tablero" data-orden="1,2,3">
                <div id="deck">
                    '.esc_html__('Tarot is not available','tarokina-pro').'
                </div>
            </div>
        </div>';

}else{
    $nCd = count($Ids_cards);
    $numCards = 12;
    $sizeImg = array_rand($Ids_cards);
    $sizeImg = $Ids_cards[$sizeImg];
    ?>

<style>
    body #tkna_preloader .spread {
        margin-top: <?php echo $tarot_margin_top?>px;
        margin-bottom: <?php echo $margin_spread?>px;
    }
    body #tkna_preloader {
        margin-bottom: <?php echo $tarot_margin_bottom?>px;
    }
 </style>

<?php
$back_imageSpread = ($back_spread == true) ? ';background-image:url('.$backfaceImg.')' : '' ;
if (current_user_can( 'manage_options' )) {
    echo '<div class="mess_only_admin">' . esc_html__('This feature is only activated if you are an administrator.','tarokina-pro').'</div>';
}

?>


<div id="tkna_preloader" data-version="<?php esc_attr_e(TAROKKINA_PRO_VERSION)?>" data-nametarot="<?php esc_attr_e($nombre)?>" data-n="<?php esc_attr_e($type_N)?>" class="_<?php esc_attr_e($cssYesNo)?> devp_<?php esc_attr_e($devp)?>">

    <div class="spread">
        <div style="background:#f2f2f2" class="capaColor"></div>

        <h2 id="spreadTitle" class="opT"><?php echo esc_html($TarotName) ?></h2>

        <div class="opT subTitle">
             <p id="subTitle" class="opT"><img src="<?php echo WP_PLUGIN_URL ?>/tarokina-pro/img/spinner-2x.gif" alt="spinner"></p>
             <div id="imgSpread" style="overflow:hidden;height:142px;margin-top:20px"><img width="200px" src="<?php echo esc_url($image_Spread)?>"></div>
        </div>

        <div id="tablero" class="tablero" data-orden="<?php esc_attr_e(implode(",", $clickOrden))?>" style="max-width: 150px; margin-top:0">
            <?php for ($i=1; $i <= $type_N; $i++) { 
            echo '<div style="" id="molde'.$i.'" class="m'.$i.' molde">
            <picture style="visibility:hidden !important">
                <img '.$size_1card.' class="sizeImg" src="'.esc_url( $sizeImg ).'" alt="card">
            </picture>
            <picture id="pic'.$i.'"></picture>
            </div>';
            };?>
        </div>

        <div id="loader_tkna-wrapper">
            <div style="border-left-color:#333333" class="loader_tkna"></div>
        </div>
        
    </div>


        <div class="TableInfo"><?php echo __( 'Mode', 'tarokina-pro' ) .': '.$mode_name?> </div>
        
        <?php $refe = ['&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;']?>
        <!-- <div style="" id="text_spread" class="table-card-info">
            <?php echo sprintf( _n( 'Check how many texts you are missing. You must write %s text on each card.', 'Check how many texts you are missing. You must write %s texts on each card.', $totalText, 'tarokina-pro' ), $totalText );?>
        </div> -->
               
        <div style="display:<?php echo esc_attr($start_dis_1)?>" id="btnResult">
        <button style="<?php echo ($btn_BackColor !== '') ?  'background:'.esc_attr($btn_BackColor).';' : ''; echo ($btn_TextColor !== '') ?  'color:'.esc_attr($btn_TextColor).'!important;' : '';?>" id="readTarot" class="button">
        <svg id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 141.52 116.14"><g id="Azul"><path class="cls-1" fill="currentColor" d="M137.19,78.41C118,87.67,98.8,96.79,79.73,106.26a43,43,0,0,1-31.54,3.22c-13.43-3.64-26.92-7.06-40.36-10.7A7,7,0,0,0,1,100.36c1.49.43,2.7.81,3.92,1.13,18.17,4.77,36.35,9.5,54.51,14.33a8.23,8.23,0,0,0,6.11-.63Q98.73,99,132,83c3-1.44,6-2.92,9.32-4.58A3.7,3.7,0,0,0,137.19,78.41Z"/><path class="cls-1" fill="currentColor" d="M49.85,73.05c5.77,1.82,10.37,1.45,15.56-1.73C72.5,67,79.58,62.77,85.06,56.24,94.08,45.48,97.85,33.19,95,19a3.87,3.87,0,0,0-3.09-3.34C74.76,10.63,57.64,5.55,40.5.51c-3.07-.9-3.07-.88-2.58,2.32A40.36,40.36,0,0,1,34.53,27C28.12,40.29,18,49.6,5.09,55.66,3.4,56.45,1.7,57.2,0,58l.16.63c.64.2,1.28.42,1.93.61C18,63.77,34.05,68,49.85,73.05Z"/><path class="cls-1" fill="currentColor" d="M59.69,88.19a7.19,7.19,0,0,0,5.38-.53Q94.73,73.25,124.43,59c5.47-2.64,10.94-5.3,16.8-8.14a5.44,5.44,0,0,0-1-.7c-11.89-3.15-23.79-6.25-35.67-9.44-1.78-.48-2.19.46-2.65,1.88C97,57.63,87.25,68.33,74.17,76c-3.78,2.22-7.7,4.21-11.45,6.48a8.76,8.76,0,0,1-7.55,1c-14.92-4.39-29.91-8.51-44.8-13-3.66-1.1-6.32.12-9.58,2.21l4.14,1.1C23.18,78.57,41.45,83.33,59.69,88.19Z"/><path class="cls-1" fill="currentColor" d="M134.33,64.86c-18.21,8.86-36.52,17.5-54.67,26.49a42.74,42.74,0,0,1-31.29,3.23C36.59,91.4,24.7,88.58,13,85.11,8.45,83.76,4.92,85,.89,87.52c.81.29,1.2.46,1.6.56,19.07,5,38.15,10,57.21,15a7.24,7.24,0,0,0,5.38-.53q33.58-16.32,67.2-32.51c2.9-1.4,5.78-2.82,9.24-4.5C138.71,64.65,136.74,63.68,134.33,64.86Z"/></g></svg>
            <?php echo esc_html($sp_text_button) ?>
        </button>
        </div>



        <!-- Barajas Selector -->
        <div style="<?php echo esc_attr($ancho)?>;margin-top:30px;display:<?php echo esc_attr($start_dis_2)?>" id="d0" class="<?php echo esc_attr($tablero) ?>">
            <?php require_once TAROKINA_PUBLIC_PATH . 'template/decks/'.$tablero.'.php'; ?>
        </div>




    <div id="cont_result"><div id="results"></div></div>

</div>

<?php
}
