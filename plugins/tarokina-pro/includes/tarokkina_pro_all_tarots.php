<?php

if (get_option('_tkna_more_tarots')) {
    $pg = (isset($_GET['tarots'])) ? sanitize_text_field($_GET['tarots']) : 0;
    $v = (isset($_GET['v'])) ? sanitize_text_field($_GET['v']) : 0;

    if ( $pg == 1 && $v == 1 || $pg == 0 && $v == 1 ) {
        $tarots =  get_option( 'tarokki_tarotsAll');
    }elseif ( $pg == 1 && $v == 0 ) {
        $tarots =  array();    
    }elseif ( $pg == 2 && $v == 1  ) {
        $tarots =  get_option( 'tarokki_tarotsAll2');
    }elseif ( $pg == 3 && $v == 1  ) {
        $tarots =  get_option( 'tarokki_tarotsAll3');
    }elseif ( $pg == 4 && $v == 1  ) {
        $tarots =  get_option( 'tarokki_tarotsAll4');
    }elseif ( $pg == 5 && $v == 1  ) {
        $tarots =  get_option( 'tarokki_tarotsAll5');
    }elseif ( $pg == 6 && $v == 1  ) {
        $tarots =  get_option( 'tarokki_tarotsAll6');    
    }elseif ( $pg == 7 && $v == 1  ) {
        $tarots =  get_option( 'tarokki_tarotsAll7');   
    }elseif ( $pg == 8 && $v == 1  ) {
        $tarots =  get_option( 'tarokki_tarotsAll8');  
    }elseif ( $pg == 9 && $v == 1  ) {
        $tarots =  get_option( 'tarokki_tarotsAll9');   
    }elseif ( $pg == 10 && $v == 1  ) {
        $tarots =  get_option( 'tarokki_tarotsAll10');    
    }else{
        $tarots =  get_option( 'tarokki_tarotsAll_array');             
    }

}else{
    $tarots =  get_option( 'tarokki_tarotsAll');
    $tarots = ($tarots) ? $tarots : array() ;
}