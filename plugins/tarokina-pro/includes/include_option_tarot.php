<?php
	global $current_screen;
	$checkCartas = get_option('_tarokki_checkCartas');
	$pageId = (isset($current_screen) && $current_screen !== null && property_exists($current_screen, 'id')) ? $current_screen->id : '';
	$cids = (isset($_GET['cids'])) ? sanitize_text_field($_GET['cids']) : '';

	if ($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots') {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots/options_tarots.php';
	}elseif($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots2'){
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots2/options_tarots2.php';
	}elseif($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots3'){
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots3/options_tarots3.php';
	}elseif($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots4'){
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots4/options_tarots4.php';
	}elseif($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots5'){
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots5/options_tarots5.php';
	}elseif($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots6'){
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots6/options_tarots6.php';
	}elseif($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots7'){
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots7/options_tarots7.php';
	}elseif($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots8'){
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots8/options_tarots8.php';
	}elseif($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots9'){
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots9/options_tarots9.php';
	}elseif($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots10'){
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots10/options_tarots10.php';
	}else{
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots/options_tarots.php';
	}

	if ( $pageId == 'edit-tarokkina_pro' || $cids == 1 || $pageId == 'tarokkina_pro') {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots/options_tarots.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots2/options_tarots2.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots3/options_tarots3.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots4/options_tarots4.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots5/options_tarots5.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots6/options_tarots6.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots7/options_tarots7.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots8/options_tarots8.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots9/options_tarots9.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/tarots10/options_tarots10.php';
	}

	$tarot_names     = get_option( 'tarokki_tarot_names');
	$tarot_tarotsAll = get_option( 'tarokki_tarotsAll');
	$EDD_products    = get_option( 'tarokki_EDD_products');

	$tarot_names2     = get_option( 'tarokki_tarot_names2');
	$tarot_tarotsAll2 = get_option( 'tarokki_tarotsAll2');
	$EDD_products2    = get_option( 'tarokki_EDD_products2');

	$tarot_names3     = get_option( 'tarokki_tarot_names3');
	$tarot_tarotsAll3 = get_option( 'tarokki_tarotsAll3');
	$EDD_products3    = get_option( 'tarokki_EDD_products3');

	$tarot_names4     = get_option( 'tarokki_tarot_names4');
	$tarot_tarotsAll4 = get_option( 'tarokki_tarotsAll4');
	$EDD_products4    = get_option( 'tarokki_EDD_products4');

	$tarot_names5     = get_option( 'tarokki_tarot_names5');
	$tarot_tarotsAll5 = get_option( 'tarokki_tarotsAll5');
	$EDD_products5    = get_option( 'tarokki_EDD_products5');

	$tarot_names6     = get_option( 'tarokki_tarot_names6');
	$tarot_tarotsAll6 = get_option( 'tarokki_tarotsAll6');
	$EDD_products6    = get_option( 'tarokki_EDD_products6');

	$tarot_names7     = get_option( 'tarokki_tarot_names7');
	$tarot_tarotsAll7 = get_option( 'tarokki_tarotsAll7');
	$EDD_products7    = get_option( 'tarokki_EDD_products7');

	$tarot_names8     = get_option( 'tarokki_tarot_names8');
	$tarot_tarotsAll8 = get_option( 'tarokki_tarotsAll8');
	$EDD_products8    = get_option( 'tarokki_EDD_products8');

	$tarot_names9     = get_option( 'tarokki_tarot_names9');
	$tarot_tarotsAll9 = get_option( 'tarokki_tarotsAll9');
	$EDD_products9    = get_option( 'tarokki_EDD_products9');

	$tarot_names10     = get_option( 'tarokki_tarot_names10');
	$tarot_tarotsAll10 = get_option( 'tarokki_tarotsAll10');
	$EDD_products10    = get_option( 'tarokki_EDD_products10');
	
	$tarot_names_array = array_merge($tarot_names,$tarot_names2,$tarot_names3,$tarot_names4,$tarot_names5,$tarot_names6,$tarot_names7,$tarot_names8,$tarot_names9,$tarot_names10);
	$tarot_tarotsAll_array = array_merge($tarot_tarotsAll,$tarot_tarotsAll2,$tarot_tarotsAll3,$tarot_tarotsAll4,$tarot_tarotsAll5,$tarot_tarotsAll6,$tarot_tarotsAll7,$tarot_tarotsAll8,$tarot_tarotsAll9,$tarot_tarotsAll10);
	update_option( 'tarokki_tarot_names_array',array_unique($tarot_names_array));
	update_option( 'tarokki_tarotsAll_array',$tarot_tarotsAll_array);

	// EDD Products ids y tiempo
	$EDD_products_array = $EDD_products + $EDD_products2 + $EDD_products3 + $EDD_products4 + $EDD_products5 + $EDD_products6 + $EDD_products7 + $EDD_products8 + $EDD_products9 + $EDD_products10;
	update_option( 'tarokki_EDD_products_array',$EDD_products_array);

	update_option( 'tkna_pro_modal_update', '','yes' );
	delete_option('_tarokki_checkCartas' );