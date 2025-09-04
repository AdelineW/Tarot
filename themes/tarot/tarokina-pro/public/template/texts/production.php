<?php


    $num= 1;
    $i = 0;
	$rotar = '';
	 echo '
	 <div class="row">
	   <div class="col">
		<div class="tabs_result">
		'.$title_leyend;
    foreach ($IdsPost as $id) :

	$cardID = get_post_thumbnail_id( $id );
	$tkna_img_full =  get_option('_tkna_img_full') ?? '';
	$img_resize = ( $tkna_img_full == 'yes' ) ? 'full' : 'tarokkina_pro-mini';
	$cardImg = wp_get_attachment_image_src( $cardID, $img_resize );
				  
	  if ($cardImg == false ) {
		$image = ($http_ssl == 'yes') ? str_replace('http://','https://',TAROKINA_URL.'img/no-card.png') : TAROKINA_URL.'img/no-card.png' ;
	  }else{
		$image = ($http_ssl == 'yes') ? str_replace('http://','https://',$cardImg[0]) : $cardImg[0];
	  }


	
	switch ($tabsResult) {
		case 'one':
			$checked =  ($num == 1) ? 'checked' : '' ;
			break;
		case 'none':
			$checked =  '' ;
			break;
		case 'all':
			$checked =  'checked' ;
			break;
	}



    switch ($tarotMode) {
        case 'basic':
			$tarotsTexts = (get_post_meta( $id, '_tkta_text_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $id, '_tkta_text_'.$id_unique.'expert0')[0];
			$yesnoValue = (get_post_meta( $id, '_tkta_yesno_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $id, '_tkta_yesno_'.$id_unique.'expert0')[0];
            break;
		case 'flip':
			if (substr($flipType[$i],-1) == 0) {
				$rotar = 'transform: rotate(0deg)';
				$tarotsTexts = (get_post_meta( $id, '_tkta_text_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $id, '_tkta_text_'.$id_unique.'expert0')[0];
				$yesnoValue = (get_post_meta( $id, '_tkta_yesno_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $id, '_tkta_yesno_'.$id_unique.'expert0')[0];
			}
			if(substr($flipType[$i],-1) == 1){
				$rotar = 'transform: rotate(-180deg)';
				$tarotsTexts = (get_post_meta( $id, '_tkta_text_'.$id_unique.'inve_expert0') == []) ? '' : get_post_meta( $id, '_tkta_text_'.$id_unique.'inve_expert0')[0];
				$yesnoValue = (get_post_meta( $id, '_tkta_yesno_'.$id_unique.'inve_expert0') == []) ? '' : get_post_meta( $id, '_tkta_yesno_'.$id_unique.'inve_expert0')[0];
			}
			break;
		case 'expert':
			// En caso de que se haya elegido un spread de 1carta y el modo experto se utilizará el modo básico
			if ($type == '0cards' || $type == '1cards') {
				$tarotsTexts = (get_post_meta( $id, '_tkta_text_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $id, '_tkta_text_'.$id_unique.'expert0')[0];
				$yesnoValue = (get_post_meta( $id, '_tkta_yesno_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $id, '_tkta_yesno_'.$id_unique.'expert0')[0];
			}else{ 
				$tarotsTexts = (get_post_meta( $id, '_tkta_text_'.$id_unique.'expert'.$i) == []) ? '' : get_post_meta( $id, '_tkta_text_'.$id_unique.'expert'.$i)[0];
				$yesnoValue = '';
			}
			break;
		case 'eflip':
			// En caso de que se haya elegido un spread de 1carta y el modo experto se utilizará el modo básico
			if ($type == '0cards' || $type == '1cards') {
				if (substr($flipType[$i],-1) == 0) {
					$rotar = 'transform: rotate(0deg)';
					$tarotsTexts = (get_post_meta( $id, '_tkta_text_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $id, '_tkta_text_'.$id_unique.'expert0')[0];
					$yesnoValue = (get_post_meta( $id, '_tkta_yesno_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $id, '_tkta_yesno_'.$id_unique.'expert0')[0];
				}
				if(substr($flipType[$i],-1) == 1){
					$rotar = 'transform: rotate(-180deg)';
					$tarotsTexts = (get_post_meta( $id, '_tkta_text_'.$id_unique.'inve_expert0') == []) ? '' : get_post_meta( $id, '_tkta_text_'.$id_unique.'inve_expert0')[0];
					$yesnoValue = (get_post_meta( $id, '_tkta_yesno_'.$id_unique.'inve_expert0') == []) ? '' : get_post_meta( $id, '_tkta_yesno_'.$id_unique.'inve_expert0')[0];
				}		
			}else{
				if (substr($flipType[$i],-1) == 0) {
					$rotar = 'transform: rotate(0deg)';
					$tarotsTexts = (get_post_meta( $id, '_tkta_text_'.$id_unique.'expert'.$i) == []) ? '' : get_post_meta( $id, '_tkta_text_'.$id_unique.'expert'.$i)[0];
					$yesnoValue = '';
				}
				if(substr($flipType[$i],-1) == 1){
					$rotar = 'transform: rotate(-180deg)';
					$tarotsTexts = (get_post_meta( $id, '_tkta_text_'.$id_unique.'inve_expert'.$i) == []) ? '' : get_post_meta( $id, '_tkta_text_'.$id_unique.'inve_expert'.$i)[0];
					$yesnoValue = '';
				}
			}
			break;
    }


	$ult = '';
	$pen = '';
	if ($num == $IdsPost_N) {
		$ult = 'ult';
	} elseif ($num == $IdsPost_N -1) {
		$pen = 'pen';
	}

	$yesnoText = ($yesnoValue == 0) ? $TextYes : $TextNo ;
	$yesno = ($type == '0cards') ? '<div class="yesno">'.$yesnoText.'</div>' : '' ;


	// Nombre Cartas tkna_name_reversed
	if ($flipType[$i] == 'flip1') {
		$nameCard_reversed = ( get_option('_tkna_name_reversed') == false ) ? sprintf( esc_html__( '%s', 'tarokina-pro' ), '{card_name}' ) : get_option('_tkna_name_reversed') ;
		$nameCard = str_replace("{card_name}", esc_html(get_the_title( $id )),$nameCard_reversed);
	}else{
		$nameCard = esc_html(get_the_title( $id));
		$nameCard_reversed = '';
	}

	$name_1card = '<h2>'.$nameCard.'</h2>';

   ?>
            <div class="tab_result <?php echo esc_attr($ult.$pen)?>">
               
			<?php if ($type != '0cards' && $type != '1cards') { ?>

				<input type="checkbox" id="rd<?php echo esc_attr($num) ?>" name="rd" <?php echo $checked;?>>
				<a style="<?php echo esc_attr($result_color) ?>" href="#"><label class="tab-label_result" for="rd<?php echo esc_attr($num) ?>">
				<?php if ($ver_meanings == true) : ?>
					<?php echo esc_html($num) ?>.&nbsp;<?php echo esc_html($tarotsArr[0]['tkta_'.$num.'st']) ?>
					<?php else:?>
					<?php echo esc_html($num) ?>.&nbsp;<?php echo $nameCard ?>
					<?php endif ?>
			    </label></a>
                <div class="tab-content_result">
				

				<img style="<?php echo esc_attr($rotar) ?>" id="i-<?php echo esc_attr($num) ?>" class="card" src="<?php echo esc_url($image);?>" alt="card-<?php echo esc_html($num) ?>">
				    <?php if ($ver_meanings == true) : ?>
						<p style="font-weight:bold"><?php echo $nameCard ?></p>
					<?php endif;

						if (empty($tarotsTexts)) {

						}else{
							echo  '<div style="margin-top:10px;min-height: 230px">' .apply_filters('the_content', $tarotsTexts).'</div>';	
						}
					?>
                </div>
				<?php }else{ // una sola carta?>
					
						<?php
						if (empty($tarotsTexts)) { // sin texto
							echo '<div style="margin-top:25px">'.$yesno.'</div>'.$name_1card;
						}else{	
							echo $yesno.$name_1card.'<div class="Text1">' .apply_filters('the_content', $tarotsTexts).'</div>';		
						}
						?>



				<?php } ?>
            </div>

   <?php

    $num ++;
    $i ++;
	
    endforeach;
	echo '</div></div></div>';
	wp_die('');