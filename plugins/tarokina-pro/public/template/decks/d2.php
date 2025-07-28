<div id="bloq_deck" class="bloq_deck">

<?php if ($ver_meanings !== false) {  ?>

<span id="TitleSelect2" class="TitleSelect2 parpadea"><?php echo $tarotOptions['tkta_1st'] ?></span>  
<div style="height:40px"></div>
<?php }else{?>
<span id="TitleSelect2" class="TitleSelect2"></span>
<?php } ?>

    <div id="deck">
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

        <span id="back-<?php echo esc_attr($num) ?>" data-src="i-<?php echo esc_attr($num) ?>" data-flip="flip<?php echo ($mode == 'flip' || $mode == 'eflip') ? esc_attr($rev[$i]) : '' ;?>" data-id="<?php echo esc_attr($ids) ?>" class="backface c_select flip-card-inner" style="background-image:url(<?php echo esc_attr($backfaceImg) ?>)">
            <div id="bloq-i-<?php echo esc_attr($num) ?>" class="flip-card-back">
                <div class="marcador" id="marcador-<?php echo esc_attr($num) ?>"></div>
                <picture>
                    <img class="card flip<?php echo ($mode == 'flip' || $mode == 'eflip') ? esc_attr($rev[$i]) : '' ?>" id="i-<?php echo esc_attr($num)?>" src="<?php echo esc_attr($img)?>" alt="card-<?php echo esc_html($num)?>" data-full="<?php echo esc_attr($image_full)?>">
                </picture>
              
            </div> 
        </span>
            <?php $i++; $num ++;} ?>
    </div>

    
    <div class="cab_deck">
    <button style="<?php echo ($btn_BackColor !== '') ?  'background:'.esc_attr($btn_BackColor).';' : ''; echo ($btn_TextColor !== '') ?  'color:'.esc_attr($btn_TextColor).'!important;' : '';?>" id="barajar">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 49.7 49.7"  xml:space="preserve"><g>
            <path fill="currentColor" d="M27,13.85h9v8.964l13.7-9.964L36,2.886v8.964h-9c-7.168,0-13,5.832-13,13c0,6.065-4.935,11-11,11H1c-0.553,0-1,0.447-1,1
                s0.447,1,1,1h2c7.168,0,13-5.832,13-13C16,18.785,20.935,13.85,27,13.85z M38,6.814l8.3,6.036L38,18.886V6.814z"/>
            <path fill="currentColor" d="M1,13.85h2c2.713,0,5.318,0.994,7.336,2.799c0.191,0.171,0.43,0.255,0.667,0.255c0.274,0,0.548-0.112,0.745-0.333
                c0.368-0.412,0.333-1.044-0.078-1.412C9.285,13.025,6.206,11.85,3,11.85H1c-0.553,0-1,0.447-1,1S0.447,13.85,1,13.85z"/>
            <path fill="currentColor" d="M36,35.85h-9c-2.685,0-5.27-0.976-7.278-2.748c-0.411-0.365-1.044-0.327-1.411,0.089c-0.365,0.414-0.326,1.046,0.089,1.411
                c2.374,2.095,5.429,3.248,8.601,3.248h9v8.964l13.7-9.964L36,26.886V35.85z M38,30.814l8.3,6.036L38,42.886V30.814z"/></g></svg>
        <span>&nbsp;&nbsp;<?php echo $text_shuffle?></span>
    </button>
    </div>
</div>