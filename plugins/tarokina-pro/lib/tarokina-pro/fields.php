<?php
if ( ! defined( 'WPINC' ) ) {die;}
use Carbon_Fields\Container;
use Carbon_Fields\Field;
$id_unico = uniqid(mt_rand());

include_once WP_CONTENT_DIR . '/plugins/tarokina-pro/lib/insert-areas.php';

$fields_pro = ["
   ->add_fields( 'tarokina-pro','Tarokina', array(
        Field::make( 'hidden', 'tkta_id', '' )->set_default_value('$id_unico'),
        Field::make( 'text', 'tkta_name', esc_html__( 'Name','tarokina-pro' ) )->set_required( true )->set_help_text('". esc_html__('Warning: Do not use special characters. For example: ü,ñ,ý,*,š,ā. If you change it, you will need to paste the shortcode back into your page.','tarokina-pro')."<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoSvg option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>')->set_attribute( 'placeholder', esc_html__('Required','tarokina-pro'))->set_classes( 'nombre lisoDefault lisoDefault_a colorA' ),
        Field::make( 'html', 'tkta_shortcode' )->set_html( ' <div class=\"titleShort\">'.esc_html__('Shortcode', 'tarokina-pro').'&nbsp;<span class=\"dashicons dashicons-shortcode\"></span></div><input class=\"cf-field__body short\">' )->set_help_text( esc_html__('Upon completion of this form and saving changes, a shortcode will be created. Paste this code on any page to activate this tarot.','tarokina-pro') )->set_classes( 'lisoDefault colorA' ),
        $ecommerce
        Field::make( 'radio', 'tkta_barajas', esc_html__( 'Deck','tarokina-pro' ) )
        ->set_options('arr_barajas')->set_help_text( esc_html__('Select a deck. If none are available, you must create one first. Visit the cards within the selected deck to generate the necessary texts.','tarokina-pro'))->set_classes( 'lisoDefault tarokki_barajas colorB' ),
        Field::make( 'radio', 'tkta_mode', esc_html__( 'Mode','tarokina-pro' ) )->set_classes( 'lisoDefault lisoDefault_a tarokki_mode colorA' )->set_help_text('<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoSvg option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>')->add_options( array(
                'basic' => esc_html__( 'Basic', 'tarokina-pro' ),
                'flip' => esc_html__( 'Basic reversed', 'tarokina-pro' ),
                'expert' => esc_html__( 'Expert', 'tarokina-pro' ),
                'eflip' => esc_html__( 'Expert reversed', 'tarokina-pro' ),
            )),
        Field::make( 'html', 'tkta_info_basic' )
        ->set_html(esc_html__('Basic Mode: A single text is used for all references in each card. Example: Tarot with 3 spreads: present, past and future. Same text for present, past and future. You will opt for a tarot less precise in the answers but simpler to create. The final quality will depend on the skill you have in creating the texts.','tarokina-pro'))
        ->set_classes('colorA lisoDefault tkta_info'),
        Field::make( 'html', 'tkta_info_flip' )
        ->set_html(esc_html__('Reversed Basic Mode: 2 texts are used for all references on each card (A or B), if the card is reversed, the alternative text B will be used. Example: Tarot with 3 spreads: present, past and future. 1 text (A or B) for present, past and future. The final quality will depend on the skill you have in elaborating the 2 texts.','tarokina-pro'))
        ->set_classes('colorA lisoDefault tkta_info'),
        Field::make( 'html', 'tkta_info_expert' )
        ->set_html(esc_html__('Expert Mode: Multiple texts are used per card (1 text per reference in each card). You will get a very precise tarot in the answers. There are many texts but the final result will be very accurate, since each reference will have its own text.','tarokina-pro'))
        ->set_classes('colorA lisoDefault tkta_info'),
        Field::make( 'html', 'tkta_info_inve_expert' )
        ->set_html(esc_html__('Reversed expert Mode: Multiple texts are used per card (2 texts per reference on each card). If the card is reversed, the alternative text B will be used on each reference. There are twice as many texts as in Expert mode.','tarokina-pro'))
        ->set_classes('colorA lisoDefault lisoDefault_b tkta_info'),
        Field::make( 'checkbox', 'tkna_devp', esc_html__('Development','tarokina-pro')  )->set_help_text(esc_html__( 'Use the development view to identify the cards containing text. This function is only accessible in the public section if you are logged in as an administrator.', 'tarokina-pro' ))->set_classes('lisoDefault lisoDefault_ab colorA tabs_color developer tkta_info new'),
        Field::make( 'radio', 'tkta_start', esc_html__( 'Start','tarokina-pro' ) )->set_help_text( esc_html__('Display a button or the card selector','tarokina-pro'))->set_classes( 'colorA lisoDefault lisoDefault_ab tarokki_mode' )->set_default_value('selector')
        ->add_options( array(
            'btn' => esc_html__( 'Push start button', 'tarokina-pro' ),
            'selector' => esc_html__( 'Show the card selector', 'tarokina-pro' )
        )),
        Field::make( 'radio', 'tkta_tablero', esc_html__( 'Card Selector','tarokina-pro' ) )->set_help_text('".esc_html__('Ways to choose the cards during a consultation.','tarokina-pro')."<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoSvg option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>&nbsp;&nbsp;<a class=\"button\" href=\"https://arnelio.com/downloads/tarokina-pro/#selectors\" target=\"_blank\">".esc_html__( 'View Selectors','tarokina-pro' )."</a>')->set_classes( 'colorA lisoDefault lisoDefault_a tarokki_mode borderDashed_a tarokki_selector' )
        ->add_options( array(
            'd1' => esc_html__( 'Classic Grid', 'tarokina-pro' ),
            'd2' => esc_html__( 'Horizontal Shuffle', 'tarokina-pro' ),
            'd3' => esc_html__( 'Click on the spread cards', 'tarokina-pro' )
        )),
        Field::make( 'text', 'tkta_tablero_num', esc_html__( 'Maximum number of visible cards in the selector','tarokina-pro' ) )->set_help_text( esc_html__('This setting only affects the amount of cards that are visible in the selector, but all cards in the assigned deck remain accessible. Every time the webpage refreshes, new images are loaded.','tarokina-pro'))->set_attribute( 'type', 'number' )->set_attribute( 'placeholder', '".esc_html__('auto','tarokina-pro')."')->set_default_value(40)->set_classes( 'tablero_num colorA borderDashed_b lisoDefault lisoDefault_b tarokki_mode' ),
        Field::make( 'html', 'tkta_information_text_2' )->set_html( '
        <div class=\"title_inside\"><span style=\"margin-right:5px\" class=\"dashicons dashicons-columns\"></span><span class=\"dashicons dashicons-columns\"></span> ".esc_html__('Tarokina Spreads','tarokina-pro')."</div>
        ' )->set_classes( 'title_inside' ),
        Field::make( 'select', 'tkta_type', esc_html__( 'Select a spread','tarokina-pro' ) )->set_options(\$type)->set_help_text( esc_html__('If you have purchased any add-ons, you can add new spreads from this selector.','tarokina-pro'))->set_classes( 'lisoDefault lisoDefault_a colorA' )->set_default_value('tarokina-pro'),
        Field::make( 'radio_image', 'tkta_spread', ' ' )->set_options( array(
            '1cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/1cards.svg',
            '0cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/0cards.svg',
            '2cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/2cards.svg',
            '3cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/3cards.svg',
            '4cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/4cards.svg',
            '5cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/5cards.svg',
            '6cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/6cards.svg',
            '7cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/7cards.svg',
            '8cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/8cards.svg',
            '9cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/9cards.svg',
            '10cards' => plugin_dir_url(dirname( __DIR__ ) ).'img/spreads/10cards.svg'
        ) )->set_classes( 'lisoDefault colorA' ),
        Field::make( 'text', 'tkta_text_yes', '' )
        ->set_attribute( 'placeholder', '".esc_html__('Yes','tarokina-pro')."')->set_classes( 'textYesNo lisoDefault colorA' ),
        Field::make( 'text', 'tkta_text_no', '' )
        ->set_help_text( '".esc_html__('Change this text.','tarokina-pro')."<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoSvg option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>' )->set_attribute( 'placeholder', '".esc_html__('No','tarokina-pro')."')->set_classes( 'textYesNo yesno2 lisoDefault colorA' ),
        Field::make( 'radio', 'tkta_sizecards', esc_html__( 'Card size','tarokina-pro' ) )->set_help_text(esc_html__('You can Increase the size of the cards. Specific sizes depend on the selected spread.','tarokina-pro'))->set_classes( 'lisoDefault colorA fieldFondo borde_abj' )
            ->add_options( array(
                's' => esc_html__( 'Small', 'tarokina-pro' ),
                'm' => esc_html__( 'Medium', 'tarokina-pro' ),
                'l' => esc_html__( 'Big', 'tarokina-pro' )
            )),
        Field::make( 'media_gallery', 'tkta_image_backface', esc_html__( 'Back of the card','tarokina-pro' ))->set_classes( 'colorB lisoDefault backface' ),
        Field::make( 'checkbox', 'tkna_back_spread', esc_html__('You can also insert the image in the Tarot spread.','tarokina-pro') )->set_help_text( esc_html__('Tarot Card Back.','tarokina-pro'))->set_classes('lisoDefault colorA borde_abj'),
        Field::make( 'radio', 'tkta_result_tabs', esc_html__( 'Open tabs in the result','tarokina-pro' ) )->set_help_text( esc_html__('Opening or closing the tabs on the result.','tarokina-pro'))->set_classes( 'colorA lisoDefault lisoDefault_b tarokki_mode' )
        ->add_options( array(
            'one' => esc_html__( 'First tab opened', 'tarokina-pro' ),
            'none' => esc_html__( 'All tabs closed', 'tarokina-pro' ),
            'all' => esc_html__( 'All tabs open', 'tarokina-pro' )
        )),
        Field::make( 'checkbox', 'tkta_meanings', esc_html__( 'Show Positions','tarokina-pro' ) )->set_option_value( 'false' )->set_help_text( '<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoSvg option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>' )->set_classes('refCard refc1 tabs_color lisoDefault lisoDefault_ab'),
        Field::make( 'radio', 'tkta_meanings_position', ' ' )->set_help_text(esc_html__('Displays a table listing references by position.','tarokina-pro'))->set_classes( 'refCard lisoDefault lisoDefault_ab borde_abj' )->set_default_value('0')
        ->add_options( array(
           esc_html__( 'Above', 'tarokina-pro' ),
           esc_html__( 'Below', 'tarokina-pro' ),
        )),
        Field::make( 'text', 'tkta_1st', esc_html__('1. card:', 'tarokina-pro') )
        ->set_default_value( esc_html__('Present','tarokina-pro'))->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'text', 'tkta_2st', esc_html__('2. card:', 'tarokina-pro') )
        ->set_default_value( esc_html__('Past','tarokina-pro'))->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'text', 'tkta_3st', esc_html__('3. card:', 'tarokina-pro') )
        ->set_default_value( esc_html__('Future','tarokina-pro'))->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'text', 'tkta_4st', esc_html__('4. card:', 'tarokina-pro') )
        ->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'text', 'tkta_5st', esc_html__('5. card:', 'tarokina-pro') )
        ->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'text', 'tkta_6st', esc_html__('6. card:', 'tarokina-pro') )
        ->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'text', 'tkta_7st', esc_html__('7. card:', 'tarokina-pro') )
        ->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'text', 'tkta_8st', esc_html__('8. card:', 'tarokina-pro') )
        ->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'text', 'tkta_9st', esc_html__('9. card:', 'tarokina-pro') )
        ->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'text', 'tkta_10st', esc_html__('10. card:', 'tarokina-pro') )
        ->set_classes( 'refCard lisoDefault lisoDefault_ab' ),
        Field::make( 'html', 'tkta_texts' )->set_html( '<div class=\"title_inside stile\"><span class=\"dashicons dashicons-edit\"></span> '.esc_html__('Change texts','tarokina-pro').'</div>' )->set_classes( 'title_inside_estilos' ),
        Field::make( 'text', 'tkta_title_spread', esc_html__('Title','tarokina-pro'))->set_default_value( esc_html__('Tarot title','tarokina-pro'))->set_attribute( 'placeholder', '".esc_html__('hidden','tarokina-pro')."')->set_help_text( esc_html__('You can leave this field empty to disable it','tarokina-pro') )->set_classes( 'colorA lisoEstilos lisoEstilos_a' ),
        Field::make( 'text', 'tkta_subtitle_spread', esc_html__('Subtitle','tarokina-pro'))->set_default_value( esc_html__('Subtitle','tarokina-pro'))->set_attribute( 'placeholder', '".esc_html__('hidden','tarokina-pro')."')->set_help_text( esc_html__('You can leave this field empty to disable it','tarokina-pro'))->set_classes( 'colorB lisoEstilos' ),
        Field::make( 'text', 'tkta_legend', esc_html__('Positions text','tarokina-pro'))->set_default_value( esc_html__('Positions','tarokina-pro'))->set_attribute( 'placeholder', '".esc_html__('hidden','tarokina-pro')."')->set_help_text( '".esc_html__('You can leave this field empty to disable it','tarokina-pro')."<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoSvg option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>' )->set_classes( 'colorA lisoEstilos' ),
        Field::make( 'text', 'tkta_pro_text_button', esc_html__('Start button text', 'tarokina-pro'))->set_attribute( 'placeholder', '".esc_html__('hidden','tarokina-pro')."')->set_default_value(esc_html__( 'Click to start your reading', 'tarokina-pro' ))->set_help_text( esc_html__( 'Change the button\'s text', 'tarokina-pro' ))->set_classes( 'colorA lisoEstilos lisoEstilos' ),
        Field::make( 'text', 'tkta_pro_text_button_volver', esc_html__('Get a new‌ reading text button', 'tarokina-pro'))->set_attribute( 'placeholder', '".esc_html__('hidden','tarokina-pro')."')->set_default_value(esc_html__( 'New reading', 'tarokina-pro' ))->set_help_text( esc_html__( 'Change the button\'s text', 'tarokina-pro' ))->set_classes( 'colorB lisoEstilos' ),
        Field::make( 'text', 'tkta_shuffle', esc_html__('Shuffle text','tarokina-pro'))->set_default_value( esc_html__('Shuffle','tarokina-pro'))->set_attribute( 'placeholder', '".esc_html__('hidden','tarokina-pro')."')->set_help_text( esc_html__('Change the button\'s text','tarokina-pro'))->set_classes( 'colorA lisoEstilos lisoEstilos_b' ),
        Field::make( 'html', 'tkta_style' )->set_html( '<div class=\"title_inside stile\"><span class=\"dashicons dashicons-color-picker\"></span> '.esc_html__('Colors','tarokina-pro').'</div>' )->set_classes( 'title_inside_estilos' ),
        Field::make( 'color', 'tkta_title_spread_color', esc_html__( 'Title color','tarokina-pro' ))->set_classes( 'colorA lisoEstilos lisoEstilos_a' ),
        Field::make( 'color', 'tkta_texto_spread_color', esc_html__( 'Subtitle color','tarokina-pro' ))->set_classes( 'colorA lisoEstilos' ),
        Field::make( 'color', 'tkta_btn_back_color', esc_html__( 'Button background-color','tarokina-pro' ))->set_help_text( '".esc_html__( 'The theme button is loaded by default.', 'tarokina-pro' )."<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoSvg option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>')->set_classes('colorA lisoEstilos'),
        Field::make( 'color', 'tkta_btn_text_color', esc_html__( 'Button Text Color','tarokina-pro' ))->set_help_text( esc_html__( 'The theme button is loaded by default.', 'tarokina-pro' ))->set_classes('colorA lisoEstilos'),
        Field::make( 'color', 'tkta_result_color', esc_html__( 'Title color in the result','tarokina-pro' ) )->set_help_text( esc_html__( ' Title color in the final draw. The theme link is loaded by default.', 'tarokina-pro' ))->set_classes('colorA lisoEstilos lisoEstilos_b'),
        Field::make( 'html', 'tkta_fondo' )->set_html( '<div class=\"title_inside stile\"><span class=\"dashicons dashicons-format-image\"></span> '.esc_html__('Background','tarokina-pro').'</div>' )->set_classes( 'title_inside_estilos' ),
        Field::make( 'color', 'tkta_background_color', esc_html__( 'Background color','tarokina-pro' ) )->set_help_text( '<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoSvg option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>' )->set_classes( 'fieldFondo lisoEstilos lisoEstilos_a' ),
        Field::make( 'media_gallery', 'tkta_image_background', esc_html__( 'Background image','tarokina-pro' ) )->set_classes( 'fieldFondo imgBack lisoEstilos' )->set_help_text( ''.esc_html__('Download free images','tarokina-pro').' - <a target=\"_blank\" href=\"https://pixabay.com/images/search/nature%20mystical/\">pixabay.com</a>' ),
        Field::make( 'text', 'tkta_image_transparent', esc_html__( 'Mix - Color Overlay','tarokina-pro' ) )->set_help_text(esc_html__('Mix between image and background-color','tarokina-pro') )->set_attribute( 'type', 'range' )->set_default_value(1)->set_classes( 'fieldFondo lisoEstilos lisoEstilos_b range' ),

        Field::make( 'html', 'tkta_css' )->set_html( '<div class=\"title_inside stile\"><span class=\"dashicons dashicons-image-flip-vertical\"></span> '.esc_html__('SPACE','tarokina-pro').'</div>' )->set_classes( 'title_inside_estilos' ),
        Field::make( 'text', 'tkta_tarot_margin_top', esc_html__( 'Tarot Margin Top','tarokina-pro' ) )->set_attribute( 'type', 'number' )->set_attribute( 'placeholder', '".esc_html__('auto','tarokina-pro')."')->set_default_value(60)->set_classes( 'tarotWidth colorB lisoEstilos lisoEstilos_a' ),
        Field::make( 'text', 'tkta_tarot_margin_bottom', esc_html__( 'Tarot Margin Bottom','tarokina-pro' ) )->set_help_text( '".esc_html__('Modify the top and bottom margins of the tarot within the WordPress content.','tarokina-pro')."<a data-text=\"'.esc_html__('Help','tarokina-pro').'\" class=\"infoSvg option_tooltip\" href=\"#\" target=\"_blank\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/info.svg\" alt=\"info\"></a>' )->set_attribute( 'type', 'number' )->set_attribute( 'placeholder', '".esc_html__('auto','tarokina-pro')."')->set_default_value(60)->set_classes( 'tarotWidth colorB lisoEstilos' ),
        Field::make( 'text', 'tkta_width', esc_html__( 'Tarot Width','tarokina-pro' ) )->set_help_text( esc_html__('Modify your tarot so that it is narrower within your page. Example: 550px','tarokina-pro'))->set_attribute( 'type', 'number' )->set_attribute( 'placeholder', '".esc_html__('auto','tarokina-pro')."')->set_classes( 'tarotWidth colorB lisoEstilos' ),
        Field::make( 'text', 'tkta_margin_spread', esc_html__( 'Spread Margin Bottom','tarokina-pro' ) )->set_help_text( esc_html__('Modify the spacing between the spread and the card selector.','tarokina-pro'))->set_attribute( 'type', 'number' )->set_attribute( 'placeholder', '".esc_html__('0','tarokina-pro')."')->set_default_value(-20)->set_classes( 'tarotWidth colorB lisoEstilos lisoEstilos_b' ),
       ) )->set_classes( 'bloq_del_tarots' )->set_header_template('<% if (tkta_name) { %><%- tkta_name %><% } %>')
    "];

