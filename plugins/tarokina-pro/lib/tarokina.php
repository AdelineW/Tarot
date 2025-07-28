<?php
add_shortcode( 'tarot', function(){ return null; } );
add_filter('upgrader_post_install', 'tkna_install_manual', 100, 3);

function tkna_install_manual($response, $hook_extra, $result ) {

    if (substr($result['destination_name'],0,5) == 'tarok') {
        if ($result['destination_name'] == 'tarokki-classic_spreads') {
            sleep(1);
            require_once WP_CONTENT_DIR . '/plugins/tarokki-classic_spreads/includes/fields.php';
            sleep(1);
            update_option( 'tarokki-fields-classic_spreads', $fields );
           
        }elseif ($result['destination_name'] == 'tarokki-custom_spreads') {
            sleep(1);
            require_once WP_CONTENT_DIR . '/plugins/tarokki-custom_spreads/includes/fields.php';
            sleep(1);
		    update_option( 'tarokki-fields-custom_spreads', $fields );   
        }
        update_option( 'tkna_pro_modal_update', 'yes','yes' );
    }

}