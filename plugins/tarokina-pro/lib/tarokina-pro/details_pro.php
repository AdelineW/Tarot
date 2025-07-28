<?php
$PATH_URL_FREE = TAROKINA_LIB_URL .'tarokina-pro';
$mycard_arr = (array) get_option('mycard_arr');
$arr_tarokina_pro = (isset($mycard_arr['tarokina-pro'])) ? $mycard_arr['tarokina-pro'] : '' ;
?>
        <div class="tar_bloq pro show">
         <div class="tarjeta">

            <img src="<?php echo esc_url($PATH_URL_FREE).'/img/tarokina-pro.svg'?>" class="banner abrir_pro">

            <div class="menu">
                  <?php
                  if( $arr_tarokina_pro !== false && $arr_tarokina_pro == MYCARD ) {

                    echo  '<div class="plugin_inactive plug_active"> '. esc_html__('Active','tarokina-pro') . '</div>';
                  }else{
                        echo '<a style="color:#bebfcc" href="edit.php?post_type=tarokkina_pro&page=tarokina_pro_license"><div class="plugin_inactive">[<span class="iconPlug dashicons dashicons-admin-network"></span>]&nbsp; '.esc_html__('inactive', 'tarokina-pro').'</div></a>';    
                  }
                    $dir_Spreads_pro = WP_PLUGIN_DIR. "/tarokina-pro/img/spreads";
                    $url_Spreads_pro = TAROKINA_URL. "/img/spreads/";
                    $directorio = scandir($dir_Spreads_pro);
                    unset($directorio[0]);
                    unset($directorio[1]);
                    natsort($directorio);
                  ?>

              <div class="opener abrir_pro">
              <span class="dashicons dashicons-menu-alt3"></span>
              </div>
            </div>

            <div class="gridTextLicense">
              Tarokina Pro
             </div>
          </div>
        </div>


      <div class='detail popup_pro'>
       <div class='detail-container'>
       <div class="detailLogo">
         <div class="bloq_img">
           <img src="<?php echo esc_url($PATH_URL_FREE).'/img/tarokina-pro.svg'?>" alt="Type">

           <div class="spread_gallery">
               <?php
               foreach ($directorio as $archivo) {
                if ($archivo=='.' || $archivo=='..' || $archivo=='.DS_Store') { 
                  echo ''; 
                } else {
                    echo '<div class="bloq_spread"><img src="'.esc_url($url_Spreads_pro.$archivo).'" alt="spread"></div>';
                    
                }
               }
               ?>
           </div>

         </div>
       
      </div>
      <dl>

      <dt class="titleDT">
      <?php echo esc_html__('Name','tarokina-pro')?>
        </dt>
        <dd>
          Tarokina Pro
        </dd>


        <dt class="titleDT">
        <span class="dashicons dashicons-admin-network"></span>&nbsp;<?php echo esc_html__('License','tarokina-pro')?>
        </dt>
        <dd>
        <?php 
              if( $arr_tarokina_pro !== false && $arr_tarokina_pro == MYCARD ) {
                echo  '<span style="color:#34c751"> '. esc_html__('Active','tarokina-pro') . '</span>';
            }else{
                echo '<span style="color:red"> '.esc_html__('inactive', 'tarokina-pro').'</span>&nbsp;<a title="Ayuda" href="edit.php?post_type=tarokkina_pro&page=tarokina_pro_license" rel="noopener">[?]</a>';
            }?>
        </dd>

        <dt class="titleDT">
        <span class="dashicons dashicons-clock"></span>&nbsp;<?php echo esc_html__('Version','tarokina-pro')?>
        </dt>
        <dd>
        <?php echo esc_html(TAROKKINA_PRO_VERSION)?>
        </dd>


        <dt class="titleDT">
        <span class="dashicons dashicons-admin-home"></span>&nbsp;<?php echo esc_html__('Homepage','tarokina-pro')?>
        </dt>
        <dd>
           <a class="" href="https://arnelio.com/downloads/tarokina-pro/"> <?php echo esc_html__('Website','tarokina-pro')?> >></a>
        </dd>

        <dt class="titleDT">
        <?php echo esc_html__('Info','tarokina-pro')?>
        </dt>
        <dd>
           <?php echo esc_html__('New Tarot plugin. Intuitive and easy to use. Provides accurate tarot readings on WordPress.','tarokina-pro')?>
        </dd>
      </dl>
    </div>
    <div class='detail-nav'>
      <button class='close cerrar_pro'>
      <?php echo esc_html__('Close','tarokina-pro')?>
      </button>
    </div>
  </div>
  <script>
    (function( $ ) {
      $(document).ready(function() {
      $('.abrir_pro, .cerrar_pro').on('click', function(e) {
        e.preventDefault();
        $('.popup_pro, html, body').toggleClass('open');
        });
      });
    })( jQuery );
    </script>