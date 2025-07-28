<?php
    $num = 1;
	$rotar = '';
    $ult = '';
	$pen = '';
	$yesno = '' ;
    
	 echo '
	 <div class="row">
	   <div class="col">
		<div class="tabs_result">
		'.$title_leyend;


        switch ($tarotMode) {
            case 'basic':
                $numText = 1;
                $ver_meanings = false;
                //$tabsResult = 'all';
                break;
            case 'flip':
                $numText = 1;
                $ver_meanings = false;
                //$tabsResult = 'all';
                break;
            case 'expert':
                $numText = $type_N;
                $ver_meanings = true;
                //$tabsResult = 'all';
                break;
            case 'eflip':
                $numText = $type_N;
                $ver_meanings = true;
                //$tabsResult = 'all';
                break;
        }

    

    ////////////////////////////////////////// CARDS //////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////

    for ($i=0; $i < $numText ; $i++) { 
       
        $cardID = get_post_thumbnail_id( $IdsPost[0] );
        $cardImg = wp_get_attachment_image_src( $cardID, 'tarokkina_pro-mini');
                    
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


    if ( $tarotMode == 'basic' ) {
        $tarotsTexts = (get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'expert0')[0];
        $yesnoValue = '';

    }elseif ( $tarotMode == 'flip' ) {

        $rotar = 'transform: rotate(0deg)';
        $tarotsTexts = (get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'expert0') == []) ? '' : get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'expert0')[0];
        $yesnoValue = '';

    }elseif ( $tarotMode == 'expert' ) {

        $tarotsTexts = (get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'expert'.$i) == []) ? '' : get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'expert'.$i)[0];
        $yesnoValue = '';	

    }elseif ( $tarotMode == 'eflip' ) {

        $rotar = 'transform: rotate(0deg)';
        $tarotsTexts = (get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'expert'.$i) == []) ? '' : get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'expert'.$i)[0];
        $yesnoValue = '';
			
    }

	if ($num == $IdsPost_N -1) {
		$pen = 'pen';
	}

	// Nombre Cartas tkna_name_reversed
    $nameCard = esc_html(get_the_title( $IdsPost[0]));
    $nameCard_reversed = '';
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
                            echo '<div class="noText"><span style="background:#f2f2f2" class="">'.esc_html__('Empty','tarokina-pro').'</span></div>';
						}else{
							echo  '<div style="margin-top:10px;min-height: 230px">' .apply_filters('the_content', $tarotsTexts).'</div>';	
						}
					?>
                </div>
				<?php }else{ // una sola carta?>
					
						<?php
						if (empty($tarotsTexts)) { // sin texto
                            echo '<div class="noText"><span style="background:#f2f2f2" class="">'.esc_html__('Empty','tarokina-pro').'</span></div>';
						}else{	
							echo $yesno.$name_1card.'<div class="Text1">' .apply_filters('the_content', $tarotsTexts).'</div>';		
						}
						?>

				<?php } ?>
            </div>

   <?php $num ++; }; 


    ////////////////////////////////////////// REVERDED CARDS //////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////

    if ($tarotMode == 'flip' || $tarotMode == 'eflip') {

        echo '<div style="background:#f2f2f2;margin-top:80px" class="amarillo_color titleReversedCardsDevp">'.ucfirst(esc_html__('reversed','tarokina-pro')).'</div>';

        $num_r = 1;
        for ($i_r=0; $i_r < $numText ; $i_r++) { 
       
            $cardID = get_post_thumbnail_id( $IdsPost[0] );
            $cardImg = wp_get_attachment_image_src( $cardID, 'tarokkina_pro-mini');
                        
            if ($cardImg == false ) {
                $image = ($http_ssl == 'yes') ? str_replace('http://','https://',TAROKINA_URL.'img/no-card.png') : TAROKINA_URL.'img/no-card.png' ;
            }else{
                $image = ($http_ssl == 'yes') ? str_replace('http://','https://',$cardImg[0]) : $cardImg[0];
            }
        
            switch ($tabsResult) {
                case 'one':
                    $checked =  ($num_r == 1) ? 'checked' : '' ;
                    break;
                case 'none':
                    $checked =  '' ;
                    break;
                case 'all':
                    $checked =  'checked' ;
                    break;
            }
        
        
        if ( $tarotMode == 'flip' ) {
        
            $rotar = 'transform: rotate(-180deg)';
            $tarotsTexts = (get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'inve_expert0') == []) ? '' : get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'inve_expert0')[0];
            $yesnoValue = '';
        
        }elseif ( $tarotMode == 'eflip' ) {
        
            $rotar = 'transform: rotate(-180deg)';
            $tarotsTexts = (get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'inve_expert'.$i_r) == []) ? '' : get_post_meta( $IdsPost[0], '_tkta_text_'.$id_unique.'inve_expert'.$i_r)[0];
            $yesnoValue = '';
                
        }
        
        if ($num_r == $IdsPost_N -1) {
            $pen = 'pen';
        }
        
        // Nombre Cartas tkna_name_reversed
        $nameCard = esc_html(get_the_title( $IdsPost[0]));
        $nameCard_reversed = '';
        $name_1card = '<h2>'.$nameCard.'</h2>';
        
        ?>
                <div class="tab_result <?php echo esc_attr($ult.$pen)?>">
                   
                <?php if ($type != '0cards' && $type != '1cards') { ?>
        
                    <input type="checkbox" id="rd_rev<?php echo esc_attr($num_r) ?>" name="rd_rev" <?php echo $checked;?>>
                    <a style="<?php echo esc_attr($result_color) ?>" href="#"><label class="tab-label_result" for="rd_rev<?php echo esc_attr($num_r) ?>">
                    <?php if ($ver_meanings == true) : ?>
                        <?php echo esc_html($num_r) ?>.&nbsp;<?php echo esc_html($tarotsArr[0]['tkta_'.$num_r.'st']) ?>
                        <?php else:?>
                        <?php echo esc_html($num_r) ?>.&nbsp;<?php echo $nameCard.' - '.esc_html__('reversed','tarokina-pro') ?>
                        <?php endif ?>
                    </label></a>
                    <div class="tab-content_result">
                    
        
                    <img style="<?php echo esc_attr($rotar) ?>" id="i-<?php echo esc_attr($num_r) ?>" class="card" src="<?php echo esc_url($image);?>" alt="card-<?php echo esc_html($num_r) ?>">
                        <?php if ($ver_meanings == true) : ?>
                            <p style="font-weight:bold"><?php echo $nameCard.' - '.esc_html__('reversed','tarokina-pro') ?></p>
                        <?php endif;
        
                            if (empty($tarotsTexts)) {
                                echo '<div class="noText"><span style="background:#f2f2f2" class="">'.esc_html__('Empty','tarokina-pro').'</span></div>';
                            }else{
                                echo  '<div style="margin-top:10px;min-height: 230px">' .apply_filters('the_content', $tarotsTexts).'</div>';	
                            }
                        ?>
                    </div>
                    <?php }else{ // una sola carta?>
                        
                            <?php
                            if (empty($tarotsTexts)) { // sin texto
                                echo '<div class="noText"><span style="background:#f2f2f2" class="">'.esc_html__('Empty','tarokina-pro').'</span></div>';
                            }else{	
                                echo $yesno.$name_1card.'<div class="Text1">' .apply_filters('the_content', $tarotsTexts).'</div>';		
                            }
                            ?>
        
                    <?php } ?>
                </div>
        
        <?php $num_r ++; }; // End For 

    }


	echo '</div></div></div>';
	wp_die('<form id="bloq-re-throw" method="post" action="#tkna_preloader"><button '.$A_botonColor.' class="re-throw" type="submit">'.esc_html__('Cards','tarokina-pro').'</button></form>');
