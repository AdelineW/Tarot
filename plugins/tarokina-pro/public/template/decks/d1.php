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
        $maxCard = $tablero_num; // Número máximo de cartas en el Selector de cartas - Tablero
        $maxCard = ( $nCd > $maxCard ) ? $maxCard : $nCd ;
        $Ids_cards_k = array_keys($Ids_cards);
        shuffle($Ids_cards_k);
        $Ids_cards_max = array_slice($Ids_cards_k, 0, $maxCard);
        $Ids_cards_arr = array();

        if ( $http_ssl == 'yes') {
            $deckImgsFull = (get_option($deck .'_deck_tarokki_full_ssl')) ? get_option($deck .'_deck_tarokki_full_ssl') : get_option($deck .'_deck_tarokki_full') ;
        }else{
            $deckImgsFull = (get_option($deck .'_deck_tarokki_full')) ? get_option($deck .'_deck_tarokki_full') : false ;
        }

        foreach($Ids_cards_max as $key) {
            $Ids_cards_arr[$key] = $Ids_cards[$key];
        }


        foreach ($Ids_cards_arr as $ids => $img) {

            $rand = array_rand($flip);
            $rev []= $flip[$rand];

            $image_full = $deckImgsFull[$ids];

        ?>
        <span id="back-<?php echo esc_attr($num) ?>" data-src="i-<?php echo esc_attr($num) ?>" data-flip="flip<?php echo ($mode == 'flip' || $mode == 'eflip') ? esc_attr($rev[$i]) : '' ;?>" data-id="<?php echo esc_attr($ids) ?>" class="backface flip-card-inner" style="background-image:url(<?php echo $backfaceImg ?>)">
            <div class="flip-card-back">
                <div class="marcador" id="marcador-<?php echo esc_attr($num) ?>"></div>
                <picture>
                    <img class="card flip<?php echo ($mode == 'flip' || $mode == 'eflip') ? esc_attr($rev[$i]) : '' ?>" id="i-<?php echo esc_attr($num)?>" src="<?php echo esc_attr($img)?>" alt="card-<?php echo esc_html($num)?>" data-full="<?php echo esc_attr($image_full)?>">
                </picture>
            </div>
        </span>
            <?php $i++; $num ++;} ?>
    </div>
</div>
