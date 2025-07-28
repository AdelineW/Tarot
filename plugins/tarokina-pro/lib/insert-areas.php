<?php
    $tkta_ecommerce = get_option('_tkta_ecommerce');
    $ecommerce_img = ($tkta_ecommerce == 'edd') ? 'svg' : 'png';
    $ecommerce_name = ($tkta_ecommerce == 'edd') ? 'Easy Digital Downloads' : 'Woocommerce';
  if ( get_option('_tkta_ecommerce') == false ||  get_option('_tkta_ecommerce') == 'none' || function_exists('run_tarokki_edd_restriction_tarokina') == false ) {
    $ecommerce = '';
    $tkna_shop_id = '';
    $tkta_time_restriction = '';
  }else{
    $ecommerce = ["
    Field::make( 'html', 'tkna_img_eco' )->set_help_text('<img style=\"width:47px;height:47px;vertical-align:middle;margin-right:8px\" src=\"'.esc_url(plugin_dir_url(dirname( __FILE__,2 ))).'img/$tkta_ecommerce.$ecommerce_img\">&nbsp;<h1>$ecommerce_name</h1>')->set_classes( '$tkta_ecommerce shopId lisoDefault lisoDefault_a'),
    Field::make( 'text', 'tkna_shop_id', ' ' )->set_help_text( '".esc_html__('ID of the product you have created in ecommerce','tarokina-pro')."<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoEcome option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>' )->set_attribute( 'type', 'number' )->set_attribute( 'placeholder', 'ID')->set_classes( '$tkta_ecommerce shopId lisoDefault'),
        Field::make( 'text', 'tkta_time_restriction', esc_html__( 'Time','tarokina-pro' ) )->set_help_text('
        <div class=\"textHelp\">'.esc_html__('Set in minutes how long the tarot result will remain unlocked. The countdown starts when a payment is completed.','tarokina-pro').'</div>
        ".esc_html__('1 Hour','tarokina-pro').": 60, 
        ".esc_html__('1 Day','tarokina-pro').": 1440,
        ".esc_html__('1 Week','tarokina-pro').": 10080,
        ".esc_html__('1 Month','tarokina-pro').": 43830,
        ".esc_html__('1 Year','tarokina-pro').": 525949
        ')->set_attribute( 'type', 'number' )->set_attribute( 'placeholder', esc_html__('Minutes','tarokina-pro'))->set_classes( '$tkta_ecommerce shopId timeRestric lisoDefault' ),
        Field::make( 'html', 'tkna_shop_text' )->set_help_text( esc_html__( 'You can change the locking text from the Options menu.', 'tarokina-pro') . '<br><a href=\"edit.php?post_type=tarokkina_pro&page=crb_carbon_fields_container_options.php\">'.esc_html__('Click here','tarokina-pro').'</a>' )->set_classes( '$tkta_ecommerce shopId timeRestric lisoDefault lisoDefault_b'),
    "][0];
  }


    