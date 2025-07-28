<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
$restriction_none = (class_exists('Tarokki_Edd_restriction_tarokina')) ? '' : 'tkta_options_tabs';
$tkta_ecommerce = get_option('_tkta_ecommerce','none');
$ecommerce_img = ($tkta_ecommerce == 'edd') ? 'svg' : 'png';

    Container::make( 'theme_options', 'options', __('Options','tarokina-pro'))
    ->set_page_parent( 'edit.php?post_type=tarokkina_pro' )
    ->add_tab(  esc_html__('General','tarokina-pro'), array(

         Field::make( 'checkbox', 'tkna_more_tarots', esc_html__('Create 100 tarots','tarokina-pro')  )->set_help_text(esc_html__( 'If you need to create more than ten tarots, enable this option.', 'tarokina-pro' ).'<a data-text="'.esc_html__('Help','tarokina-pro').'" class="infoOpti option_tooltip" href="#" target="_blank"><img src="'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg" alt="info"></a>')->set_classes('lisoDefault colorA fieldOption tabs_color'),
         Field::make( 'checkbox', 'tkna_http_ssl', esc_html__('Force SSL on images','tarokina-pro')  )->set_help_text(esc_html__( 'Enabling this option will force all images to load with https.', 'tarokina-pro' ))->set_classes('lisoDefault colorA fieldOption tabs_color'),
         Field::make( 'checkbox', 'tkna_img_full', esc_html__('Force images without compression only in the tarot result.','tarokina-pro')  )->set_help_text(esc_html__( 'If the uploaded images are too large, they may slow down the page loading time.', 'tarokina-pro' ))->set_classes('lisoDefault colorA fieldOption tabs_color'),
         Field::make( 'checkbox', 'change_license_domain', esc_html__('Alternative license activation.','tarokina-pro')  )->set_help_text(esc_html__( "If you're having trouble activating your license, enable this option. It will attempt to validate the license using an alternative method.", 'tarokina-pro' ))->set_classes('lisoDefault colorA fieldOption tabs_color'),
         Field::make( 'text', 'tkna_name_reversed', esc_html__( 'Add text before or after the name of a reversed card.','tarokina-pro' ) )->set_help_text( sprintf( esc_html__( '%s is a necessary variable that shows the name of the card.', 'tarokina-pro' ), '{card_name}' ).'<br>'.sprintf( esc_html__( 'Example 1: %s - reversed', 'tarokina-pro' ), '{card_name}' ).'<br>'.sprintf( esc_html__( 'Example 2: reversed - %s', 'tarokina-pro' ), '{card_name}' ) )->set_default_value(sprintf( esc_html__( '%s', 'tarokina-pro' ), '{card_name}' ))->set_attribute( 'placeholder', sprintf( esc_html__( '%s', 'tarokina-pro' ), '{card_name}' ) )->set_classes( 'lisoDefault colorA fieldOption'),
         Field::make( 'checkbox', 'tkna_pro_unistall', esc_html__('Uninstall Tarokina Pro','tarokina-pro')  )->set_classes('lisoDefault colorA fieldOption tabs_color'),
        Field::make( 'html', 'tkta_pro_text_unistall' )->set_html( '
        <span class="text_unistall">'.esc_html__('Check this if you would like to remove ALL Tarokina Pro data upon plugin deletion.','tarokina-pro').'</span>
        ')->set_conditional_logic( array(
            array(
                'field' => 'tkna_pro_unistall',
                'value' => true,
            ))),
        
    ))
    ->add_tab(  esc_html__('Tarokina Restriction','tarokina-pro'), array(
        
        Field::make( 'select', 'tkta_ecommerce', esc_html__( 'Select Ecommerce','tarokina-pro' ) )->set_options(array(
            'none' => esc_html__('Select','tarokina-pro'),
            'woo' => 'Woocommerce',
            'edd' => 'Easy Digital Downloads',
        ))->set_help_text('<a data-text="'.esc_html__('Help','tarokina-pro').'" class="infoOpti option_tooltip" href="#" target="_blank"><img src="'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg" alt="info"></a>'.'<img style="width:47px;height:47px;vertical-align:middle;margin-right:8px" src="'.esc_url(plugin_dir_url(dirname( __FILE__,2 ))).'img/'.$tkta_ecommerce.'.'.$ecommerce_img.'">'.esc_html__('The parameters are in each tarot created.','tarokina-pro').'&nbsp;&nbsp;<a href="'.admin_url( 'edit.php?post_type=tarokkina_pro&page=crb_carbon_fields_container_tarots.php').'">'.esc_html__('Tarots','tarokina-pro').' »</a>')->set_classes('lisoDefault colorA fieldOption')->set_default_value('none'),

        Field::make( 'select', 'tkta_ecommerce_logout', esc_html__( 'Logout from tarot','tarokina-pro' ) )->set_help_text( esc_html__('Displays a header widget on the tarot page with username, links and countdown. Visible only for registered users.','tarokina-pro'))->set_classes( 'lisoDefault colorA fieldOption')
        ->add_options( array(
            2 => esc_html__( 'Deactivate', 'tarokina-pro' ),
            0 => esc_html__('Theme 1','tarokina-pro'),
            1 => esc_html__('Theme 2','tarokina-pro')
        ))->set_default_value('0'),

        Field::make( 'select', 'tkta_ecommerce_link_acount', esc_html__( 'Login from tarot','tarokina-pro' ) )->set_help_text( esc_html__('Displays a form or a login button on the tarot page. Visible only to unregistered users.','tarokina-pro'))->set_classes( 'lisoDefault colorA fieldOption')
        ->add_options( array(
           2 => esc_html__('Deactivate','tarokina-pro'),
           0 => esc_html__( 'Form', 'tarokina-pro' ),
           1 => esc_html__( 'Button', 'tarokina-pro' )
        ))->set_default_value('1'),
         Field::make( 'text', 'tkna_restric_text', __( 'Information text to unlock the tarot by purchasing a product.','tarokina-pro' ) )->set_help_text( sprintf( esc_html__( '%s is the variable containing the link to the product in the store.', 'tarokina-pro' ), '{product_name}' ) )->set_default_value( sprintf( esc_html__( 'This content is restricted to buyers of %s', 'tarokina-pro' ), '{product_name}' ) )->set_attribute( 'placeholder', '...')->set_classes( 'lisoDefault colorA fieldOption'),

         

         Field::make( 'radio', 'tkta_edd_link_direct', esc_html__( 'Link','tarokina-pro' ) )->set_help_text( sprintf( esc_html__( 'When clicking %s, redirect to the checkout or the product page.', 'tarokina-pro' ), '{product_name}' ) )->set_classes( 'lisoDefault colorA fieldOption')
         ->add_options( array(
             0 => esc_html__( 'Checkout page', 'tarokina-pro' ),
             1 => esc_html__( 'Product page', 'tarokina-pro' ),
         )),
    ) )
    ->add_tab(  esc_html__('System Status','tarokina-pro'), array(
        Field::make( 'html', 'tkna_pro_optiontab2' )
                ->set_html('<div class="system_status">'. __( 'If you want to export a handy list of all the information on this page, you can use the button below to copy it to the clipboard. You can then paste it in a text file and save it to your device, or paste it in an email exchange with a support engineer or theme/plugin developer for example.' ).'</div><a class="btnStatus button" href="/wp-admin/site-health.php?tab=debug">'.__( 'Site Health Info' ).'</a>'),
    ) )->set_classes( ''.$restriction_none.'' );
