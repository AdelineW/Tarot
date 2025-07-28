<?php
// =============================================================
//                Template Default
// =============================================================
if ( ! defined( 'WPINC' ) ) {die;}
// Textos
$title_spread = $tarotOptions['tkta_title_spread'];
$title_spread_style = ($sp_title_color) ? 'color:'.$sp_title_color.' !important' : '';
$sub_title_spread = $tarotOptions['tkta_subtitle_spread'];
$sp_text_legend = $tarotOptions['tkta_legend'];
$sp_text_legend = ($sp_text_legend) ? $sp_text_legend : '' ;
$sp_text_button = $tarotOptions['tkta_pro_text_button'];
$rutaBack = plugin_dir_path( dirname( __FILE__ ) );
$rutaFront = plugin_dir_url( dirname( __FILE__ ) );
$size = 'full';
$colors_3 = ($sp_title_color) ? list($r, $g, $b) = sscanf($sp_title_color, "#%02x%02x%02x") : list($r, $g, $b) = sscanf("#9e9e9e", "#%02x%02x%02x") ;
$fondoCard = ($sp_bg_color == 'inherit' || $sp_bg_color == '#ffffff') ? 'background-color: rgba('.$r.','.$g.','.$b.',0.05 )' : 'background-color: rgba(248, 248, 248, 0.3)';
$backfaceDefault =  plugin_dir_url( dirname( __FILE__,2 ) ).'img/back-default.png';
$backfaceImg = ($backface != false) ? $backface[0]  : $backfaceDefault;
$ids_restriction = (empty(get_option('tkna_restrict_post_id'))) ? array() : get_option('tkna_restrict_post_id');
$refe = [];
if ($Ids_cards == false) {
    echo '<div id="tkna_preloader" style="background:#f2f2f2; color:#676767; margin:50px 0; padding:50px; text-align:center;"><div id="tablero" data-orden="1,2,3"><div id="deck">'.esc_html__('Tarot is not available','tarokina-pro').' </div></div></div>';  
}else{
    $sizeImg = array_rand($Ids_cards);
    $sizeImg = $Ids_cards[$sizeImg];
    ?>
    <style>
        body .spread .tablero {opacity: 0}
        <?php
            if ( $sp_bg_color == 'inherit' && $img_fondo == array()) { 
                $color_numCard = $sp_title_color ;
                ?>   
                    body #tkna_preloader .spread {padding-bottom: 46px;}
                    body #tkna_preloader .spread{margin-top: 50px;}
            <?php }else{
                $color_numCard = '' ;
            }
            foreach ($clickOrden as $order => $value) {
                $order = $order +1;
                echo ".m".esc_attr($value)."::before {content: '".esc_attr($order)."'}";
            } 
        ?>
    </style>

<?php
if (in_array(get_the_id(), $ids_restriction) == false || $tkna_shop_id == "" || function_exists('WC')== false  || $mycard_arr['edd_restriction_tarokina'] !== MYCARD || current_user_can( 'manage_options' )) { 

    $transient = false;

}else{
    $user = wp_get_current_user();
    if (wp_using_ext_object_cache()) {
        // desabilitar cache externa para el transitorio
        global $_wp_using_ext_object_cache;
        $_wp_using_ext_object_cache = false;
        $transientTime = get_transient( 'timeout_tkna_time_'.$user->ID .'_'.$tkna_shop_id );
        $transient = get_transient( 'tkna_time_'.$user->ID .'_'.$tkna_shop_id );
        $_wp_using_ext_object_cache = true;
    }else{
        $transientTime = get_transient( 'timeout_tkna_time_'.$user->ID .'_'.$tkna_shop_id );
        $transient = get_transient( 'tkna_time_'.$user->ID .'_'.$tkna_shop_id );
    }
    $fieldTarot = $tarotOptions['tkta_time_restriction'];
    $fieldTarot = ($tarotOptions['tkta_time_restriction'] !=='') ? (int) $tarotOptions['tkta_time_restriction'] : 1 ;


    // El usuario se ha resgistrado
   $logout = get_option('_tkta_ecommerce_logout',0) ?? 0;
   if ( is_user_logged_in() && $logout == 0 ) { ?>

       <div class="box-taro-login">
           <div class="text-end">
               <div class="icon">
                   <img class="image" src="<?php echo esc_url( get_avatar_url( $user->ID ) ); ?>" alt="<?php echo $user->display_name?>">
               </div>
               <div>
                   <h3 class="nameU"><?php echo $user->display_name?></h3>
                       <div class="taro_btns">
                           <a class="" href="<?php echo wc_get_account_endpoint_url( 'orders' )?>"> <?php echo esc_html__( 'Orders', 'tarokina-pro' ) ;?></a>&nbsp;|&nbsp;<a style="text-decoration:none!important" href="<?php echo wp_logout_url( get_permalink() )?>" tabindex="1" class=""><?php echo __( 'Logout', 'tarokina-pro' )?></a> 
                   </div>
               </div>   
           </div>

           <?php if ( is_user_logged_in() && $transient !== false ) { ?>
                   <div class="timeBloq">
                       <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 122.88 99.56" style="enable-background:new 0 0 122.88 99.56" xml:space="preserve"><script xmlns=""/><style type="text/css">.st1{fill-rule:evenodd;clip-rule:evenodd;fill:#029300;}</style><g><path fill="currentColor" d="M73.1,0c6.73,0,13.16,1.34,19.03,3.78c6.09,2.52,11.57,6.22,16.16,10.81c4.59,4.58,8.28,10.06,10.81,16.17 c2.43,5.87,3.78,12.3,3.78,19.03c0,6.73-1.34,13.16-3.78,19.03c-2.52,6.09-6.22,11.58-10.81,16.16 c-4.58,4.59-10.06,8.28-16.17,10.81c-5.87,2.43-12.3,3.78-19.03,3.78c-6.73,0-13.16-1.34-19.03-3.77 c-6.09-2.52-11.57-6.22-16.16-10.81l-0.01-0.01c-4.59-4.59-8.29-10.07-10.81-16.16c-0.78-1.89-1.45-3.83-2-5.82 c1.04,0.1,2.1,0.15,3.17,0.15c2.03,0,4.01-0.18,5.94-0.53c0.32,0.96,0.67,1.91,1.05,2.84c2.07,5,5.11,9.51,8.9,13.29 c3.78,3.78,8.29,6.82,13.29,8.9c4.81,1.99,10.11,3.1,15.66,3.1c5.56,0,10.85-1.1,15.66-3.1c5-2.07,9.51-5.11,13.29-8.9 c3.78-3.78,6.82-8.29,8.9-13.29c1.99-4.81,3.1-10.11,3.1-15.66c0-5.56-1.1-10.85-3.1-15.66c-2.07-5-5.11-9.51-8.9-13.29 c-3.78-3.78-8.29-6.82-13.29-8.9c-4.81-1.99-10.11-3.1-15.66-3.1c-5.56,0-10.85,1.1-15.66,3.1c-0.43,0.18-0.86,0.37-1.28,0.56 c-1.64-2.58-3.62-4.92-5.89-6.95c1.24-0.64,2.51-1.23,3.8-1.77C59.94,1.34,66.37,0,73.1,0L73.1,0z M67.38,26.12 c0-1.22,0.5-2.33,1.3-3.13c0.8-0.8,1.9-1.3,3.12-1.3c1.22,0,2.33,0.5,3.13,1.3c0.8,0.8,1.3,1.91,1.3,3.13v23.22l17.35,10.29 c1.04,0.62,1.74,1.6,2.03,2.7c0.28,1.09,0.15,2.29-0.47,3.34c-0.62,1.04-1.6,1.74-2.7,2.03c-1.09,0.28-2.29,0.15-3.33-0.47 L69.65,55.71c-0.67-0.37-1.22-0.91-1.62-1.55c-0.41-0.67-0.65-1.46-0.65-2.3V26.12L67.38,26.12z"/><path class="st1" d="M26.99,2.56c14.91,0,26.99,12.08,26.99,26.99c0,14.91-12.08,26.99-26.99,26.99C12.08,56.54,0,44.45,0,29.55 C0,14.64,12.08,2.56,26.99,2.56L26.99,2.56z M15.05,30.27c0.36-2.1,2.76-3.27,4.65-2.13c0.17,0.1,0.34,0.22,0.49,0.36l0.02,0.01 c0.85,0.81,1.8,1.66,2.74,2.5l0.81,0.73l9.59-10.06c0.57-0.6,0.99-0.99,1.85-1.18c2.94-0.65,5.01,2.95,2.93,5.15L26.17,38.19 c-1.13,1.2-3.14,1.31-4.35,0.16c-0.69-0.64-1.45-1.3-2.21-1.96c-1.32-1.15-2.67-2.32-3.77-3.48 C15.18,32.25,14.89,31.17,15.05,30.27L15.05,30.27z"/></g></svg>
                   
                       <?php 
                           $relojArr = tkna_reloj_edd($transientTime, $fieldTarot);
                           echo '<div class="relo">'.$relojArr['time1'].'&nbsp;&nbsp;'.$relojArr['time2'].'</div>';
                       ?>
                   </div>      
                   <div class="w3-progress-container">
                       <div id="myBar" class="w3-progressbar w3-green" style="width:<?php echo $relojArr['proportion']?>%;max-width:100%"></div>
                   </div>
           <?php } ?>
       </div>
   <?php 
   } elseif ( is_user_logged_in() && $logout == 1 ) { ?>
           
           <div class="box-taro-login dis_user">
                <div class="iconTime">
                    <div class="icon">
                        <img class="image" src="<?php echo esc_url( get_avatar_url( $user->ID ) ); ?>" alt="<?php echo $user->display_name?>">
                    </div>
               
                    <div class="taro_btns">
                   <a class="" href="<?php echo wc_get_account_endpoint_url( 'orders' )?>"> <?php echo esc_html__( 'Orders', 'tarokina-pro' ) ;?></a>&nbsp;|&nbsp;<a style="text-decoration:none!important" href="<?php echo wp_logout_url( get_permalink() )?>" tabindex="1" class=""><?php echo __( 'Logout', 'tarokina-pro' )?></a> 
                    </div>

                </div>

               <?php if ( is_user_logged_in() && $transient !== false) { ?>
                   <div class="timeBloq">
                       <?php 
                           $relojArr = tkna_reloj($transientTime, $fieldTarot);
                           echo '<div class="relo">'.$relojArr['time1'].'&nbsp;&nbsp;'.$relojArr['time2'].'</div>';
                       ?>
                   </div>
                   <div class="w3-progress-container">
                       <div id="myBar" class="w3-progressbar w3-green" style="width:<?php echo $relojArr['proportion']?>%;max-width:100%"></div>
                   </div>   
                <?php } ?>
                
                <h3 class="nameU"><?php echo $user->display_name?></h3>
           </div>
   <?php }
}
?>

<?php
    if (current_user_can( 'manage_options' ) && function_exists('run_tarokki_edd_restriction_tarokina') !== false && $tkna_shop_id !== '' ) {
        echo '<div class="mess_only_admin">' . esc_html__('This message is only visible to WordPress admins. Open your tarot in a new incognito window or log out of your administrator session to activate Tarokina Restriction.','tarokina-pro').'</div>';
    }
?>

    <div style="margin-top:50px;margin-bottom:50px;" id="tkna_preloader" data-version="<?php esc_attr_e(TAROKKINA_PRO_VERSION)?>" data-nametarot="<?php esc_attr_e($nombre)?>" data-n="<?php esc_attr_e($type_N)?>" class="_<?php esc_attr_e($cssYesNo)?>">
        <div style="<?php echo esc_attr($spreadImg.$ancho)?>" class="spread">
             <div style="background:<?php echo esc_attr($sp_bg_color) . esc_attr($opacity)?>" class="capaColor"></div>

            <?php  if ($title_spread) { ?>
                <h2 style="<?php esc_attr_e($title_spread_style) ?>" class="opT"><?php echo esc_html($title_spread) ?></h2>
            <?php } ?>

            <div class="opT subTitle" style="display:block !important; opacity:0.8 !important; visibility:visible !important; position:static !important; z-index:9!important;margin:0!important;">
                <p id="subTitle" class="opT" style="color:<?php esc_attr_e( $sp_text_color)?>!important; display:block !important; opacity:0.8 !important; visibility:visible !important; position:static !important; z-index:9!important;margin:0!important;"><?php echo esc_html($sub_title_spread)?></p>
            </div>

            <?php
            $num= 1;
            $i0 = 0;
            $flip = array(1,0,0);

            $Ids_cards_k = array_keys($Ids_cards);
            shuffle($Ids_cards_k);
            $Ids_cards_max = array_slice($Ids_cards_k, 0, $type_N);
            $Ids_cards_arr = array();
            $deckImgsFull = get_option($deck .'_deck_tarokki_full');

            foreach($Ids_cards_max as $key) {
                $Ids_cards_arr[$key] = $Ids_cards[$key];
            }

            $cartas_ids = array_keys($Ids_cards_arr);
            $cartas_urls = array_slice($Ids_cards_arr, 0, $type_N);


            $restar_cartas = (int)$type_N - (int)count($Ids_cards);
            for ($i=1; $i <= $restar_cartas; $i++) {
                $cartas_ids [] = $i;
                $cartas_urls [] = plugin_dir_url(dirname( __FILE__,2 ) ).'img/no-card.png';
            }
            $cartas_array = array_combine($cartas_ids,$cartas_urls);
            $shuffled_array = array();
            $keysCartas = array_keys($cartas_array);
            shuffle($keysCartas);
            foreach ($keysCartas as $key){
                $shuffled_array[$key] = $cartas_array[$key];
            }
            $cartas_keys = array_keys($shuffled_array);
            $cartas_values = array_values($shuffled_array);

            ?>

            <div id="tablero" class="tablero d3" data-orden="<?php esc_attr_e(implode(",", $clickOrden))?>" <?php esc_attr_e($size_cards)?>>
                <?php for ($i=1; $i <= $type_N; $i++) {
                    $rand = array_rand($flip);
                    $rev []= $flip[$rand];

                    $imgBack = substr($cartas_values[$i0],-11);
                    if ($imgBack == 'no-card.png' || $cartas_values[$i0] == null ) {
                        $image_full = plugin_dir_url(dirname( __FILE__,2 ) ).'img/no-card.png';
                    }else{
                        $image_full = $deckImgsFull[$cartas_keys[$i0]];
                    }  
                ?>
                <div data-src="i-<?php echo esc_attr($num) ?>" data-id="<?php echo esc_attr($cartas_keys[$i0]) ?>" style="box-shadow:none !important;border:none !important;cursor: pointer;<?php echo ($transient !== false && is_user_logged_in() || function_exists('run_tarokki_edd_restriction_tarokina') == false || $tkna_shop_id == "" || current_user_can( 'manage_options' ) ) ? '' : 'pointer-events: none;' ?>" id="molde<?php echo esc_attr($i)?>" class="m<?php echo esc_attr($i)?> molde">
                    <picture class="trasera">
                    <img <?php echo $size_1card?> class="traseraImg" src="<?php echo $backfaceImg ?>" alt="card">
                    </picture>
                    <picture class="card_click" id="blogPic<?php echo esc_attr($i)?>"><img style="width:100%;
                    height:auto;" data-flip="flip<?php echo ($mode == 'flip' || $mode == 'eflip') ? esc_attr($rev[$i0]) : '' ;?>" class="cardImg" id="pic<?php echo esc_attr($i)?>" src="<?php echo $image_full?>" alt="card<?php echo esc_attr($i)?>"></picture>
                </div>
            <?php $i0++; $num++; };?>
            </div>

            <div id="loader_tkna-wrapper">
                <div style="border-left-color:<?php echo esc_attr($sp_title_color)?>" class="loader_tkna"></div>
            </div>      
        </div>
       
        <?php if ($ver_meanings !== false) { ?>
            <div style="<?php echo esc_attr($ancho)?>;margin-top:45px" id="text_spread" class="text_spread">
                <h3 class="meaningsTitles"><?php echo esc_html($sp_text_legend)?></h3>
                <ol>
                <?php 
                for ($i=1; $i <= $type_N; $i++) { 
                    $the_card_repres = $tarotOptions['tkta_'.$i.'st'];
                    $the_card_repres = ($the_card_repres) ? $the_card_repres : '' ;
                    $refe [] = $the_card_repres;
                    echo '<li id = "textSp_'.esc_attr($i).'"> '.esc_html($the_card_repres).' </li>';
                };?>  
                </ol>
            </div>
        <?php }else{ $refe = ['&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;','&nbsp;']?>
            <div style="" id="text_spread" class="text_spread"></div> 
        <?php } ?>
        <?php      
                if (in_array(get_the_id(), $ids_restriction) == false || $tkna_shop_id == "" || function_exists('WC')== false || function_exists('run_tarokki_edd_restriction_tarokina') == false  || $mycard_arr['edd_restriction_tarokina'] !== MYCARD || current_user_can( 'manage_options' )) { 

                    ///////////////// Restriction  Desactivado ///////////////////////////
                ?>
                
                <div class="_<?php esc_attr_e($cssYesNo)?>">
                    <div id="cont_result"><div id="results"></div></div>
                </div>
                    
                   <?php }else{
                
                         ///////////////// Restriction  Activado ///////////////////////////
                   
                        if ($transient !== false && is_user_logged_in()) {  // Se ha comprado y existe Transient ?>
                
                        <div class="_<?php esc_attr_e($cssYesNo)?>">
                             <div id="cont_result"><div id="results"></div></div>
                        </div>
                                      
                       <?php }else{
                
                            // Ha caducado,  el Transient no existe, hay que volver a pagar
                
                            $product = wc_get_product( $tkna_shop_id );
                            $productName = ($product !== false) ? $product->get_name() : 'N/A';
                            $productID = ($product !== false) ? $product->get_id() : '';
                
                            $link_acount = get_option('_tkta_ecommerce_link_acount',1) ?? 1;
                            $link_direct = get_option('_tkta_edd_link_direct',0) ?? 0;

                
                            if ($link_direct == 0) {
                                $link_product = '<a href="'.wc_get_cart_url().'??add-to-cart='.$tkna_shop_id.'">'.$productName.'</a>';  
                            }else{
                                $link_product = '<a href="'.get_permalink( $productID ).'">'.$productName.'</a>';
                            }
                        
                            $restric_text = ( get_option('_tkna_restric_text') == false || get_option('_tkna_restric_text') == '' ) ? sprintf( esc_html__( 'This content is restricted to buyers of %s.', 'tarokina-pro' ), '{product_name}' ) : get_option('_tkna_restric_text') ;
                            $restric_text = str_replace("{product_name}",$link_product,$restric_text);
                           
                           
                          echo '<div id="cont_result" style="display: block">
                          <p style="text-align:center">
                          <svg xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto; display: inherit" class="h-5 w-5" width="42px" height="42px" viewBox="0 0 20 20" fill="currentColor">
                              <path
                              fill-rule="evenodd"
                              d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                              clip-rule="evenodd"
                              ></path>
                          </svg>';
                          $modal = '
                          <div class="loginRestri-container">
                              <input id="loginRestri-toggle" type="checkbox">
                              <label class="loginRestri-btn" for="loginRestri-toggle">'.__('Login','tarokina-pro').'</label> 
                              <label class="loginRestri-backdrop" for="loginRestri-toggle"></label>
                              <div class="loginRestri-content">
                              <label class="loginRestri-close" for="loginRestri-toggle">&#x2715;</label>
                              '.do_shortcode('[woocommerce_my_account]').'
                              </div>          
                          </div>
                          ';
                          echo (! is_user_logged_in() && $link_acount == 1) ? $modal : '' ;
                          
                          echo '</p>
                          <div class="edd_cr_message">
                          <p>
                              '.$restric_text.'
                          </p>
                          </div>
                          <div id="results"></div>
                      </div>';

                          // formulario para registrarse
                          if ( ! is_user_logged_in() && $link_acount == 0 ) { 
                            echo '<div class="woo_my_account">'.do_shortcode('[woocommerce_my_account]').'</div>';
                          }
                      
                       } 
                    }
        ?>
    </div>
 <?php }
