<?php 
$Class_addons = new tarokki_addons();
$mycard_arr = (array) get_option('mycard_arr');
$carpeta_tarots = ( get_option( 'tarokki_carpeta_tarots' ) !== array()) ?  get_option( 'tarokki_carpeta_tarots' ) : array() ;


//Insertar restriction content tarokina dentro del array carpeta_tarots
if (function_exists('activate_tarokki_edd_restriction_tarokina')== true) {
  array_push($carpeta_tarots, 'edd_restriction_tarokina');
}



$addons_inactive = ( get_option( 'tkna_addon_inactive' ) !== array()) ?  get_option( 'tkna_addon_inactive' ) : array();
require_once TAROKINA_LIB_PATH .'tarokina-pro/fields.php';
$numCarpe = ( $carpeta_tarots != '') ?  count($carpeta_tarots) : 0 ;
$icon = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjBwdCIgaGVpZ2h0PSIyMXB0IiB2aWV3Qm94PSIwIDAgMjAgMjEiIHZlcnNpb249IjEuMSI+CjxnIGlkPSJzdXJmYWNlMSI+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDojZmY2ZDZmO2ZpbGwtb3BhY2l0eToxOyIgZD0iTSAxMC4wMDM5MDYgMCBDIDguNDkyMTg4IDAgNy4yNjU2MjUgMC41MzkwNjIgNy4yNjU2MjUgMS4yMDcwMzEgQyA3LjI2NTYyNSAxLjMzMjAzMSA3LjMwODU5NCAxLjQ1MzEyNSA3LjM5MDYyNSAxLjU2NjQwNiBMIDcuMzgyODEyIDEuNTcwMzEyIEMgOC45Mjk2ODggMi44MzIwMzEgMTEuOTUzMTI1IDIuNDcyNjU2IDEyLjY2NDA2MiAxLjQ1MzEyNSBMIDEyLjY2NDA2MiAxLjUgQyAxMi43MTg3NSAxLjQwNjI1IDEyLjc0NjA5NCAxLjMwODU5NCAxMi43NDYwOTQgMS4yMDcwMzEgTCAxMi43NDIxODggMS4xOTE0MDYgQyAxMi43MjY1NjIgMC41MzEyNSAxMS41MDc4MTIgMCAxMC4wMDM5MDYgMCBaIE0gMTIuNjYwMTU2IDEuNjEzMjgxIEwgMTIuNjI1IDIuODc1IEMgMTEuNjQwNjI1IDQuMDQyOTY5IDguNTg5ODQ0IDQuMjYxNzE5IDcuMjI2NTYyIDIuODU1NDY5IEwgNy4yNjU2MjUgMS42NDQ1MzEgTCA1LjY0MDYyNSAyLjU5Mzc1IEMgNS40OTIxODggMi41ODIwMzEgNS4zNDM3NSAyLjU3MDMxMiA1LjE5MTQwNiAyLjU3MDMxMiBDIDMuNjc1NzgxIDIuNTcwMzEyIDIuNDQ5MjE5IDMuMTEzMjgxIDIuNDQ5MjE5IDMuNzc3MzQ0IEMgMi40NDkyMTkgMy45Mzc1IDIuNTIzNDM4IDQuMDg5ODQ0IDIuNjUyMzQ0IDQuMjMwNDY5IEMgNC4yMTg3NSA1LjQyMTg3NSA3LjE2NDA2MiA1LjA2MjUgNy44NjMyODEgNC4wNTg1OTQgTCA3LjgyODEyNSA1LjQ3NjU2MiBDIDYuODQzNzUgNi42NDQ1MzEgMy43OTI5NjkgNi44NjMyODEgMi40Mjk2ODggNS40NTcwMzEgTCAyLjQ1NzAzMSA0LjQ1MzEyNSBMIDAuMzU1NDY5IDUuNjc5Njg4IEwgMTAuMDg1OTM4IDExLjI1MzkwNiBMIDE5LjgxMjUgNS42NDg0MzggTCAxNy41NzQyMTkgNC4zODY3MTkgTCAxNy41NDI5NjkgNS40MTc5NjkgQyAxNi41NjI1IDYuNTg1OTM4IDEzLjUwNzgxMiA2LjgwODU5NCAxMi4xNDg0MzggNS4zOTg0MzggTCAxMi4xODc1IDQuMDE1NjI1IEMgMTMuNjgzNTk0IDUuMzgyODEyIDE2LjgwODU5NCA1LjA1MDc4MSAxNy41NjY0MDYgNC4wMTk1MzEgQyAxNy41NjY0MDYgNC4wMTk1MzEgMTcuNTcwMzEyIDQuMDE1NjI1IDE3LjU3MDMxMiA0LjAxNTYyNSBDIDE3LjYxMzI4MSAzLjkyOTY4OCAxNy42NDA2MjUgMy44NDM3NSAxNy42NDA2MjUgMy43NSBMIDE3LjYzNjcxOSAzLjczODI4MSBDIDE3LjYyMTA5NCAzLjA3ODEyNSAxNi4zOTg0MzggMi41NDY4NzUgMTQuODk4NDM4IDIuNTQ2ODc1IEMgMTQuNzEwOTM4IDIuNTQ2ODc1IDE0LjUzMTI1IDIuNTU0Njg4IDE0LjM1NTQ2OSAyLjU3MDMxMiBaIE0gMTIuNzgxMjUgNi44MTI1IEwgMTIuNzQ2MDk0IDguMjM0Mzc1IEMgMTEuNzY1NjI1IDkuNDAyMzQ0IDguNzEwOTM4IDkuNjI1IDcuMzUxNTYyIDguMjE0ODQ0IEwgNy4zODY3MTkgNi44MzU5MzggQyA4Ljg5NDUzMSA4LjIxMDkzOCAxMi4wNTQ2ODggNy44NjMyODEgMTIuNzgxMjUgNi44MTI1IFogTSAxMi43ODEyNSA2LjgxMjUgIi8+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDojZmY2ZDZmO2ZpbGwtb3BhY2l0eToxOyIgZD0iTSAwLjA1NDY4NzUgNy40NDUzMTIgTCA5LjExMzI4MSAxMi44MDQ2ODggTCA5LjExMzI4MSAyMC45NDUzMTIgTCAwIDE1Ljg1NTQ2OSBaIE0gMC4wNTQ2ODc1IDcuNDQ1MzEyICIvPgo8cGF0aCBzdHlsZT0iIHN0cm9rZTpub25lO2ZpbGwtcnVsZTpub256ZXJvO2ZpbGw6I2ZmNmQ2ZjtmaWxsLW9wYWNpdHk6MTsiIGQ9Ik0gMjAuMDIzNDM4IDcuMjUgTCAxMC45MDYyNSAxMi44Nzg5MDYgTCAxMC45MDYyNSAyMS4wMjM0MzggTCAyMC4wMjM0MzggMTUuOTI5Njg4IFogTSAyMC4wMjM0MzggNy4yNSAiLz4KPC9nPgo8L3N2Zz4=';
?>


    <div id="bloq_tarjeta" class="bloq_tarjeta">
      <div class="tar_bloq pro show">
          <div class="tarjeta item som itRev">
            <a href="https://arnelio.com/downloads/tarokina-pro#respond" target="_blank" rel="noopener noreferrer">
                  <h3><?php echo esc_html__('Review','tarokina-pro')?></h3>
                  <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                  <p><?php echo esc_html__('Please rate Tarokina Pro on Arnelio.com to help us spread the word. Thank you from the Arnelio team!','tarokina-pro')?></p>
              </a>
            </div>
      </div>

      <div class="tar_bloq pro show">
          <div class="tarjeta item som itEma">
              <a href="http://eepurl.com/humb49" target="_blank" rel="noopener noreferrer">
                  <h3><?php echo esc_html__('Arnelio.com','tarokina-pro')?></h3>
                  <div class="email"><img src="<?php echo esc_url(plugin_dir_url( __DIR__ ))?>img/email.svg" alt="email" /></div>
                  <p><?php echo esc_html__('We notify you about discounts and updates.','tarokina-pro')?></p>
              </a>
          </div>
      </div>


    <?php // Tarokina pro
    require_once TAROKINA_LIB_PATH .'tarokina-pro/details_pro.php';
        $num = 0;
        $all_tarots=[];
        $i = 1;

       foreach ((array)$carpeta_tarots as $tarot) {
        if ($tarot != '') {
          $PATH_TAROT = WP_PLUGIN_DIR . '/tarokki-' .$tarot.'/';
          $PATH_IMG = WP_PLUGIN_URL . '/tarokki-' .$tarot.'/img/';
          
          $json_addon =  $PATH_TAROT.'data.json';
          $json_addon = file_get_contents($json_addon);
          $json_addon = json_decode($json_addon,true);


////////////////////// Crear el Transient con los datos ALL TAROTS  /////////////////
/////////////////////////////////////////////////////////////////////////////////////

          $addons_grid = get_transient('tarokki_addons_grid');

          if ( ! $addons_grid ){
            $addons = $json_addon[$tarot];
            $all_tarots[$json_addon[$tarot]['tarot_slug']] = $json_addon[$tarot];

            if ($i == $numCarpe) {
              set_transient( 'tarokki_addons_grid', $all_tarots, MINUTE_IN_SECONDS);
            }
          }else{
            $addons = $addons_grid[$tarot];
          }
          $addon_version = get_plugin_data(WP_PLUGIN_DIR . '/tarokki-' .$tarot.'/tarokki-'.$tarot.'.php');
/////////////////////////////////////////////////////////////////////////////////////


        $addon_slug = (isset($addons['tarot_slug'])) ? $addons['tarot_slug'] : '' ;
        $addon_mycard = (isset($mycard_arr[$addon_slug])) ? $mycard_arr[$addon_slug] : MYDECK ;
        $addon_url_plugin = (isset($addons['url_plugin'])) ? $addons['url_plugin'] : '' ;
      
        if ( $addon_mycard !== MYCARD && $addons['tarot_type'] == 'pro'){
            $c = MYDECK;
            $iconMenu = 'lock';

        }else {
            $c = '';
            $iconMenu = 'menu-alt3';
        }
        if (in_array($addon_slug, $addons_inactive)) {
          $inactive = ' inactive';
        }else{
          $inactive = ' ';
        }

    ?>

<div class="tar_bloq <?php echo esc_attr($addons['tarot_type'].$inactive) ?>">
<div class="tarjeta <?php echo esc_attr($c)?>">

       <img src="<?php echo esc_url($PATH_IMG.$addons['tarot_image'])?>" class="banner abrir<?php echo esc_attr($num) ?>">


    <div class="menu">
        <?php
          if( $addon_mycard !== false && $addon_mycard !== MYCARD ) {
                echo '<a style="color:#bebfcc" href="edit.php?post_type=tarokkina_pro&page=tarokina_pro_license"><div class="plugin_inactive">[<span class="iconPlug dashicons dashicons-admin-network"></span>]&nbsp; '.esc_html__('inactive', 'tarokina-pro').'</div></a>';
                }else{
                echo  '<div class="plugin_inactive plug_active"> '. esc_html__('Active','tarokina-pro') . '</div>';
          };
          $dir_Spreads = WP_PLUGIN_DIR.'/tarokki-'.$tarot. "/img/spreads";
          $url_Spreads =WP_PLUGIN_URL.'/tarokki-'.$tarot. "/img/spreads/";
          $directorio = scandir($dir_Spreads);
          unset($directorio[0]);
          unset($directorio[1]);

          if (is_array($directorio)) {
              natsort($directorio);
          } else {
            $directorio = array();
          }

        ?>
        <div class="opener abrir<?php echo esc_attr($num) ?>">
           <span class="dashicons dashicons-menu-alt3"></span>
        </div>

    </div>




    <div class="gridTextLicense">
       <?php echo esc_html($addons['tarot_title'])?>
    </div>


  </div>
  <div class='detail popup<?php echo esc_attr($num) ?>'>
    <div class='detail-container'>

      <div class="detailLogo grid">
         <div class="bloq_img">
    
           <img src="<?php echo esc_url($PATH_IMG.$addons['tarot_image'])?>" alt="Type">

           <div class="spread_gallery">
               <?php
               foreach ($directorio as $archivo) {

                if ($archivo=='.' || $archivo=='..' || substr($archivo,0,1) =='.') { 
                  echo ''; 
                } else {
                    echo '<div class="bloq_spread"><img src="'.esc_url($url_Spreads.$archivo).'" alt="spread"></div>'; 
                }
               } ?>
           </div>

         </div>
      </div>
  
      <dl>
        <dt class="titleDT">
        <?php echo esc_html__('Name','tarokina-pro')?>
        </dt>
        <dd>
            <?php echo esc_html($addons['tarot_title'])?>
        </dd>

        <dt class="titleDT">
        <span class="dashicons dashicons-admin-network"></span>&nbsp;<?php echo esc_html__('License','tarokina-pro')?>
        </dt>
        <dd>
        <?php

          if ($addons['tarot_type'] == 'free') {
            echo '<span style="color:#34c751"> '. esc_html__('Free','tarokina-pro') . '</span>';
          }else{
            if( $addon_mycard !== false && $addon_mycard == MYCARD ) {
              echo  '<span style="color:#34c751"> '. esc_html__('Active','tarokina-pro') . '</span>';
            }else{
              echo '<span style="color:#ff6d6f"> '.esc_html__('inactive', 'tarokina-pro').'</span>&nbsp;<a title="Ayuda" href="edit.php?post_type=tarokkina_pro&page=tarokina_pro_license">[?]</a>';
            }
          }

        ?>
        </dd>


        <dt class="titleDT">
        <span class="dashicons dashicons-clock"></span>&nbsp;<?php echo esc_html__('Version','tarokina-pro')?>
        </dt>
        <dd>
        <?php echo esc_html($addon_version['Version'])?>
        </dd>


        <dt class="titleDT">
        <span class="dashicons dashicons-admin-home"></span>&nbsp;<?php echo esc_html__('Homepage','tarokina-pro')?>
        </dt>
        <dd>
           <a class="" href="<?php echo esc_url($addon_url_plugin) ?>"> <?php echo esc_html__('Website','tarokina-pro')?> >></a>
        </dd>


        <dt class="titleDT">
          <?php echo esc_html__('Info','tarokina-pro')?>
          </dt>
          <dd>
          <?php echo $addon_version['Description'];?>  
          </dd>


      </dl>
    </div>
    <div class='detail-nav'>
      <button class='close cerrar<?php echo esc_attr($num) ?>'>
      <?php echo esc_html__('Close','tarokina-pro')?>
      </button>
    </div>
  </div>
  <script>
    // Abrir popup a toda pantalla con los detalles
    (function( $ ) {
      $(document).ready(function() {
      $('.abrir<?php echo esc_attr($num) ?>, .cerrar<?php echo esc_attr($num) ?>').on('click', function(e) {
        e.preventDefault();
        $('.popup<?php echo esc_attr($num) ?>, html, body').toggleClass('open');
        });
      });
    })( jQuery );
    </script>
</div>
  
  <?php 
  
  $num++;$i++; } }  ?>

    <div class="masAddons tar_bloq show">
      <div class="tarjeta">
        <span class="mas"><a target="_blank" href="https://arnelio.com/">+</a></span>
        <img src="<?php echo esc_url(plugin_dir_url( __DIR__ )); ?>/img/icon-grande-gris.svg">   
      </div>
    </div>
 </div>
