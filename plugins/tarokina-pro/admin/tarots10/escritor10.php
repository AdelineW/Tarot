<?php
///////////////// REGISTRO DE LOS CAMPOS POR EL ESCRITOR ///////////////
// theme_options, complex, tarots/vista.php, tarot list, include fields
////////////////////////////////////////////////////////////////////////
$Class_addons = new tarokki_addons();
$settings_updated = ( isset($_GET['settings-updated'])) ?  sanitize_text_field($_GET['settings-updated']) : '' ;
$addons_grid = get_transient('tarokki_addons_grid');
$carpeta_tarots = ( get_option( 'tkna_addon_inactive' ) !== array()) ?  get_option( 'tkna_addon_inactive' ) : array() ;
foreach ($Class_addons->TkNaP_addons_url() as $addon) {
    include_once(ABSPATH.'wp-admin/includes/plugin.php');

    $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' .$addon);
    $data_name = strtr(substr($plugin_data['Name'],8), " ", "-");
    $data_name = strtolower($data_name);
   
    if (!in_array($data_name, $carpeta_tarots) ) {
        $carpeta_tarots [] = $data_name;
    }

}

// Eliminar alguno de mis plugins en la carpeta_tarots
$array_quitar = array('edd_restriction_tarokina');
$carpeta_tarots = array_diff($carpeta_tarots, $array_quitar);
update_option( 'tarokki_carpeta_tarots', $carpeta_tarots );	

    $TOP= "
    use Carbon_Fields\Container;
    use Carbon_Fields\Field;
    \$type = [];
    \$type = ( get_option('tarokki_carpeta_tarots') !== array()) ?  get_option('tarokki_carpeta_tarots') : array() ;
    array_push(\$type, \"tarokina-pro\");
    \$type = array_combine(\$type,\$type);
    function arr_barajas(){
        global \$wpdb;
        \$arr_barajas = [];
        \$q_barajas = \$wpdb->get_results(
            \$wpdb->prepare(\"
           ( SELECT * FROM (
               SELECT {\$wpdb->terms}.term_id, {\$wpdb->terms}.name
               FROM {\$wpdb->terms}
               JOIN {\$wpdb->term_taxonomy}
               ON {\$wpdb->term_taxonomy}.term_id = {\$wpdb->terms}.term_id
               WHERE taxonomy = %s
               ORDER BY term_id ASC
           ) as T
           );
           \",
           'tarokkina_pro-cat'
           )
       );
       if (\$q_barajas == []) {
        \$arr_barajas = [
            __('No decks.','tarokina-pro')
        ];
       }else{
        foreach (\$q_barajas as \$baraja) {
            \$arr_barajas [\$baraja->term_id] = \$baraja->name;
           }
       }
       return \$arr_barajas;  
    };

    if (get_option('tarokki_install_demo')== 'yes') {
        \$installDemo = '';
        \$democlose = '<div class=\"modalContainer\"><div style=\"background: #742c7a\" class=\"modal_save\"><img  style=\"width:265px\" src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/caja_1.png\" alt=\"tarokina-pro\"><button id=\"democlose\" name=\"democlose\" value=\"yes\" type=\"submit\" class=\"button btn_close\">'.esc_html__('close', 'tarokina-pro').'</button><img class=\"spinnerClass\" id=\"spinner_demo\" style=\"display:none\" src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/spinner-2x.gif\" alt=\"spinner\"></div></div>';

    }else{  
        \$installDemo = '<button id=\"demoinstall\" name=\"demoinstall\" value=\"yes\" type=\"submit\" class=\"button\">'.esc_html__('Install', 'tarokina-pro').'</button><img class=\"spinnerClass\" id=\"spinner_demo\" style=\"display:none\" src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/spinner-2x.gif\" alt=\"spinner\">';
        \$democlose = '';
    }

    if (!in_array('Arnelio', arr_barajas()) || get_option('tarokki_install_demo') == 'yes') {
       \$DemOn = 0;
       update_option( 'tarokki_install_demo', '');
    }else{
        \$DemOn = 1;
    }
    
    Container::make( 'theme_options', 'tarots10', esc_html__('Tarots','tarokina-pro') )
    ->set_page_parent( 'edit.php?post_type=tarokkina_pro' )
        ->add_fields( array(
    Field::make( 'html', 'tkna_demoinstall')->set_html('<div id=\"iddemo\"><img src=\"'.esc_url(plugin_dir_url(dirname( __DIR__ ) )).'img/caja_1.png\" alt=\"Deck Tarokina\"><h2>Arnelio Deck</h2><p>'. esc_html__('Try our 22 Major Arcana deck in a sample data tarot', 'tarokina-pro').'.</p>&nbsp;&nbsp;&nbsp; '.\$installDemo.\$democlose.'</div>')->set_classes( 'bloq_demoinstall arnelioDeck_'.\$DemOn.''),
    Field::make( 'html', 'tarokki_cabecera_text_tarots' )
        ->set_html( '
        <span id=\"text_holder\">'.esc_html__('No Tarot', 'tarokina-pro').'</span>
        <ul class=\"gradient-list\">
        <li>'.esc_html__('Create a deck from the menu > Decks. For example: Marseille, Raider, etc', 'tarokina-pro').'</li>
        <li>'.esc_html__('Add cards to the new deck and assign an image to them from the menu > Cards. You will not be able to add any text until you create a tarot in step 3.', 'tarokina-pro').'</li>
        <li>'.esc_html__('To create a tarot, fill in a simple form by clicking on the < Add Tarot > button. You can create several tarots with the same deck. Save changes. Now you can write the texts of your new tarot by editing each card.', 'tarokina-pro').'</li>
        </ul><div class=\"titleFormsBloq\"><div class=\"titleForms\">'.esc_html__( 'Tarots','tarokina-pro' ).'&nbsp;91-100</div><span class=\"dashicons dashicons-arrow-down-alt2\"></span></div>
        ')->set_classes( 'infoTarots' ),
    Field::make( 'complex', 'tarokki_tarot_complex10', '' )
    ->set_collapsed( true )
    ->set_max('10')
    ->setup_labels(array( 'plural_name' => esc_html__( 'Tarots','tarokina-pro' ),
    'singular_name' => esc_html__( 'Tarot','tarokina-pro' ),))
    ";

    $FOOTER = "
    ,Field::make( 'html', 'tarokki_btn_delete_tarot' )->set_html( '<button id=\"del_tarots\" class=\"button del_tarots\">".esc_html__('Delete tarot', 'tarokina-pro')."</button>')->set_classes( 'html_del_tarots' )
        ));
    ";

//////////////// ACTUALIZAR LA VISTA CON LOS CAMPOS DE LOS TAROTS //////////////

////////////////////////////////////////////////////////////////////////////////

    $add_fields = [];

    // update Classic fields
    if (class_exists('Tarokki_Classic_spreads')) {
		include_once TAROKKI_CLASSIC_SPREADS_INCLUDES .'fields.php';
		update_option( 'tarokki-fields-classic_spreads', $fields );
    }
    // update Custom fields
    if (class_exists('Tarokki_Custom_spreads')) {
		include_once TAROKKI_CUSTOM_SPREADS_INCLUDES .'fields.php';
		update_option( 'tarokki-fields-custom_spreads', $fields );
    }


    foreach ($carpeta_tarots as $tarot) {
            $fields = ( get_option( 'tarokki-fields-'.$tarot) != false) ?  get_option( 'tarokki-fields-'.$tarot) : array() ;
            $add_fields[] = implode($fields);
            delete_transient( 'tarokki_addons_grid' );
            delete_transient( 'tarokki_carpeta_tarots' );
    }
    include TAROKINA_LIB_PATH . 'tarokina-pro/fields.php';
    $fields_pro = $fields_pro[0];
    $add_fields= implode($add_fields);
    $secciones_de_la_vista = [$TOP,$fields_pro,$add_fields,$FOOTER];
    $vista= implode($secciones_de_la_vista);
    $escribir = fopen(TAROKINA_ADMIN_PATH."tarots10/vista10.php", "w");
    fwrite($escribir, "<?php if ( ! defined( 'WPINC' ) ) {die;}". PHP_EOL);
    fwrite($escribir, $vista . PHP_EOL);
    fclose($escribir);




