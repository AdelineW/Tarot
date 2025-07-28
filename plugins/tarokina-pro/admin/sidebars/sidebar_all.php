<?php
add_action( 'carbon_fields_container_tarots_after_sidebar', 'TkNaP_sidebar_key' );
add_action( 'carbon_fields_container_options_after_sidebar', 'TkNaP_sidebar_key' );
// Más Tarots
if (get_option('_tkna_more_tarots')) { 
    add_action( 'carbon_fields_container_tarots2_after_sidebar', 'TkNaP_sidebar_key' );
    add_action( 'carbon_fields_container_tarots3_after_sidebar', 'TkNaP_sidebar_key' );
    add_action( 'carbon_fields_container_tarots4_after_sidebar', 'TkNaP_sidebar_key' );
    add_action( 'carbon_fields_container_tarots5_after_sidebar', 'TkNaP_sidebar_key' );
    add_action( 'carbon_fields_container_tarots6_after_sidebar', 'TkNaP_sidebar_key' );
    add_action( 'carbon_fields_container_tarots7_after_sidebar', 'TkNaP_sidebar_key' );
    add_action( 'carbon_fields_container_tarots8_after_sidebar', 'TkNaP_sidebar_key' );
    add_action( 'carbon_fields_container_tarots9_after_sidebar', 'TkNaP_sidebar_key' );
    add_action( 'carbon_fields_container_tarots10_after_sidebar', 'TkNaP_sidebar_key' );
}

function TkNaP_sidebar_key(){	
?>
<div><p id="infoDelete"><?php echo esc_html__('If you delete a tarot, all texts created for that tarot will be deleted.', 'tarokina-pro')?></p></div>
<a id="cancelSib" href="#" class="cancelApi"><?php echo esc_html__('Cancel', 'tarokina-pro')?></a>


<div id="newsWidget" class="postbox">
    <div class="postbox-header">
        <h2 class="titleNews"><?php echo 'Tarokina Pro'?></h2>
    </div>
    <div class="inside">
       <ul>


            <li><span class="Nadd">Tarokina Restriction</span>: <?php esc_html_e('Use Woocommerce or EDD and earn money by charging for your tarot readings.', 'tarokina-pro')?><img style="margin-top:8px;width:75px;height:100%;position:relative;bottom: 2px;vertical-align: middle;" src="<?php echo TAROKINA_URL?>img/ecommerce-logos.png"><a href="https://arnelio.com/downloads/addon-tarokina-restriction/" target="_blank"><?php echo esc_html__('More information', 'tarokina-pro')?></a></li>

            <li><span class="Nadd">New feature</span>: <span class="NaddText"> <?php echo esc_html__('Maximum number of visible cards in the selector', 'tarokina-pro')?></span></li>

            <li><span class="Nadd">New feature</span>: <span class="NaddText"> <?php echo esc_html__('Add text before or after the name of a reversed card.', 'tarokina-pro')?></span></li>

            <li><span class="Nadd">New card selector</span>: <span class="NaddText"> <?php echo esc_html__('Now, you can select cards directly from the spread itself.', 'tarokina-pro')?></span></li>

            <li><span class="Nadd">Added</span>: <span class="NaddText"> <?php echo esc_html__('Clear cache button. Regenerate images and clear the tarot cache.', 'tarokina-pro')?></span></li>

            <li><a href="<?php echo admin_url( 'edit.php?post_type=tarokkina_pro&page=crb_carbon_fields_container_options.php' )?>">Options</a></li>
        </ul>
    </div>
</div>


<div id="" class="widgetArnelio postbox">
	<div class="postbox-header">
		<a href="https://arnelio.com/" target="_blank">
			<div class="arnelioLogo">	
				<img style="width:100%;height:100%" src="<?php echo esc_url(plugin_dir_url(dirname( __DIR__ )));?>img/logo-arnelio.svg" alt="Logo Arnelio">	
			</div>
		</a>
	</div>
	<div class="inside">
	<ul>
		<li><a href="https://arnelio.com/documentation/" target="_blank"><?php echo esc_html__('Documentation', 'tarokina-pro')?></a></li>
	    <li><a href="https://arnelio.com/offers/" target="_blank"><?php echo esc_html__('Offers', 'tarokina-pro')?></a></li>
		<li><a href="https://arnelio.com/" target="_blank"><?php echo esc_html__('Add-ons', 'tarokina-pro')?></a></li>
		<li><a href="https://arneliodemo.com/tarokina-pro-3/" target="_blank"><?php echo esc_html__('Demos', 'tarokina-pro')?></a></li>
	</ul>
	</div>
</div>

<div id="downloadDeck" class="ArnDeck postbox">
    <div class="postbox-header">
        <h2 class=""><?php echo esc_html__('Arnelio deck images', 'tarokina-pro')?></h2>
    </div>
    <div class="inside">

	<img src="<?php echo TAROKINA_URL?>/img/caja_1.png" alt="">
       <a class="button" href="<?php echo TAROKINA_URL?>/img/arnelio-deck.zip"><?php echo esc_html__('Download', 'tarokina-pro')?></a> 
    </div>
</div>

<?php

}