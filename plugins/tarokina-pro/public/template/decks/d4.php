<?php
// Imagen spread
if (  $plugin == 'tarokina-pro') {
    if ($plugin !== 'tarokina-pro') {$spread = substr($spread, 0, -1);}
    $image_Spread = "".esc_url(plugins_url())."/tarokina-pro/img/spreads/$spread.svg";
}else{
    $image_Spread = "".esc_url(plugins_url())."/tarokki-$plugin/img/spreads/$spread.svg";
}

?>
<div id="bloq_deck" class="bloq_deck">
    <div class="cab_deck">
        <div id="card_select" class="card_select">
            
            <?php if ($ver_meanings !== false) {  ?>

                <span id="TitleSelect2" class="TitleSelect2 parpadea"><?php echo $tarotOptions['tkta_1st'] ?></span>  

            <?php }else{?>
                <span id="TitleSelect2" class="TitleSelect2"></span>
            <?php } ?>

    </div>  
    </div>

    <div style="grid-template-columns: repeat(auto-fill, minmax(<?php echo esc_attr($numCards) ?>%, 1fr))" id="deck">
        <?php
        $num= 1;
        $i = 0;
        $flip = array(1,0,0);
   
        if ( $http_ssl == 'yes') {
            $deckImgsFull = (get_option($deck .'_deck_tarokki_full_ssl')) ? get_option($deck .'_deck_tarokki_full_ssl') : get_option($deck .'_deck_tarokki_full') ;
        }else{
            $deckImgsFull = (get_option($deck .'_deck_tarokki_full')) ? get_option($deck .'_deck_tarokki_full') : false ;
        }

        // Foreach principal con el Grid
        $text_expert = [];
        $numText = [];
        $numTextArray = [];
        foreach ($Ids_cards as $ids => $img) {

            $rand = array_rand($flip);
            $rev []= $flip[$rand];
            $image_full = $deckImgsFull[$ids];
            $nameCard = esc_html(get_the_title($ids));

            // Contando los Textos
            if ( $modes == 'basic' ) {
                $text = (get_post_meta( $ids, '_tkta_text_'.$id_unique.'expert0') == []) ? 0 : get_post_meta( $ids, '_tkta_text_'.$id_unique.'expert0')[0];
                if (empty($text)) {
                    $text = 0;
                }else{
                    $text = 1;
                }
                $numText[] = $text;
                $Textos = ( $numText[$i] > $totalText ) ? $totalText : $numText[$i];
                $numTextArray [] = $Textos;
    
            }elseif ( $modes == 'flip' ) {
    
                $text = (get_post_meta( $ids, '_tkta_text_'.$id_unique.'expert0') == []) ? 0 : get_post_meta( $ids, '_tkta_text_'.$id_unique.'expert0')[0];
                $inve_text = (get_post_meta( $ids, '_tkta_text_'.$id_unique.'inve_expert0') == []) ? 0 : get_post_meta( $ids, '_tkta_text_'.$id_unique.'inve_expert0')[0];
                if (empty($text)) {
                    $text = 0;
                }else{
                    $text = 1;
                }
                if (empty($inve_text)) {
                    $inve_text = 0;
                }else{
                    $inve_text = 1;
                }
                $numText[] = $text + $inve_text;
                $Textos = ( $numText[$i] > $totalText ) ? $totalText : $numText[$i];
                $numTextArray [] = $Textos;
    
            }elseif ( $modes == 'expert' ) {
                for ($t=0; $t < $spread_N; $t++) {
                    $text = (get_post_meta( $ids, '_tkta_text_'.$id_unique.'expert'.$t) == []) ? 0 : get_post_meta( $ids, '_tkta_text_'.$id_unique.'expert'.$t)[0];
                    if (empty($text)) {
                        $text = 0;
                    }else{
                        $text = 1;
                    }
                    $text_expert[$t] = $text;         
                }   
                $numText[] = $text_expert; 
                $Textos = ( array_sum($numText[$i]) > $totalText ) ? $totalText : array_sum($numText[$i]);
                $numTextArray [] = $Textos;
      
            }elseif ( $modes == 'eflip' ) {
                for ($t=0; $t < $spread_N; $t++) {  
                    $text = (get_post_meta( $ids, '_tkta_text_'.$id_unique.'expert'.$t) == []) ? 0 : get_post_meta( $ids, '_tkta_text_'.$id_unique.'expert'.$t)[0];
                    $inve_text = (get_post_meta( $ids, '_tkta_text_'.$id_unique.'inve_expert'.$t) == []) ? 0 : get_post_meta( $ids, '_tkta_text_'.$id_unique.'inve_expert'.$t)[0];
                    if (empty($text)) {
                        $text = 0;
                    }else{
                        $text = 1;
                    }
                    if (empty($inve_text)) {
                        $inve_text = 0;
                    }else{
                        $inve_text = 1;
                    }
                    $text_expert[$t] = $text + $inve_text;         
                }   
                $numText[] = $text_expert; 
                $Textos = ( array_sum($numText[$i]) > $totalText ) ? $totalText : array_sum($numText[$i]);
                $numTextArray [] = $Textos;
            }

            if ( $Textos == 0 ) {
                $card_color = 'background:#f8d7da;color:#58151c;';
                $border_color = '#f1aeb5';
            }elseif ($Textos >= 1 && $Textos < $totalText) {
                // $card_color = 'background:#fff3cd;color:#664d03;';
                // $border_color = '#ffe69c';
                $card_color = 'background:#f8d7da;color:#58151c;';
                $border_color = '#f1aeb5';
            }elseif ($Textos == $totalText) {
                $card_color = 'background:#d1e7dd;color:#0a3622;';
                $border_color = '#a3cfbb';
            }

        ?>
            <span id="back-<?php echo esc_attr($num) ?>" data-src="i-<?php echo esc_attr($num) ?>" data-flip="flip<?php echo ($mode == 'flip' || $mode == 'eflip') ? esc_attr($rev[$i]) : '' ;?>" data-id="<?php echo esc_attr($ids) ?>" class="backface flip-card-inner" style="margin-bottom:20px;">
                <div class="flip-card-back">
                    <div style="border-radius: 6px 6px 0 0;border: 1px solid transparent;<?php echo esc_attr($card_color) ?>" class="numtext"><span class="numtextA"><?php echo $Textos ?></span>&nbsp;<span class="numtextB"><?php echo $totalText ?></span></div>
                    <picture style="border-radius:6px;border: 2px solid <?php echo esc_attr($border_color) ?>">
                    <img class="card flip<?php echo ($mode == 'flip' || $mode == 'eflip') ? esc_attr($rev[$i]) : '' ?>" id="i-<?php echo esc_attr($num)?>" src="<?php echo esc_attr($img)?>" alt="card-<?php echo esc_html($num)?>" data-name="<?php echo esc_attr($nameCard)?>" data-text="<?php echo esc_attr($Textos)?>" data-total="<?php echo sprintf( _n( '%s text', '%s texts', $totalText, 'tarokina-pro' ), $totalText );?>" data-empty="<?php echo esc_attr($totalText - $Textos)?>" data-full="<?php echo esc_attr($image_full)?>">
                    </picture>
                </div>
            </span>
            <?php $i++; $num ++;} ?>
            
        <div style="visibility:hidden;" id="dev_info_tarot" class="dev_info_tarot" data-tarotext="<?php echo array_sum($numTextArray)?>" data-tarotempty="<?php echo (count($Ids_cards) * $totalText) - array_sum($numTextArray) ?>" data-tarototal="<?php echo sprintf( _n( '%s text', '%s texts', count($Ids_cards) * $totalText, 'tarokina-pro' ), count($Ids_cards) * $totalText );?>"></div>

    </div>
</div>