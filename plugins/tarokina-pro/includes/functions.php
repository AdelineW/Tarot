<?php

function tark_pro_x8z_urlText($string_in){
    $string_output=mb_strtolower($string_in, 'UTF-8');

    $find=array('¥','µ','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','\'','"');

    $repl=array('-','-','a','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','ny','o','o','o','o','o','o','u','u','u','u','y','y','','' );
    $string_output=str_replace($find, $repl, $string_output);

    $find=array(' ', '&','%','$','·','!','(',')','?','¿','¡',':','+','*','\n','\r\n', '\\', '´', '`', '¨', ']', '[');
    $string_output=str_replace($find, '_', $string_output);
    $string_output=str_replace('--', '', $string_output);
    return $string_output;
}


// AÑADIR ASYNC al js del tarot
function TkNa_async_tarokina_pro($tag, $handle, $src) {
	if ($handle === 'tarokina') {
		if (false === stripos($tag, 'async')) {
			$tag = str_replace('<script ', '<script async ', $tag);
		}
	}
	return $tag;
}
add_filter('script_loader_tag', 'TkNa_async_tarokina_pro', 1, 3);



// AÑADIR defer AL SCRIPT back_tarots.js
function TkNa_defer_back_tarots_pro($tag, $handle, $src) {
	if ($handle === 'tarokkina_pro_js_back_tarots') {
		if (false === stripos($tag, 'defer')) {
			$tag = str_replace('<script ', '<script defer ', $tag);
		}
	}
	return $tag;
}
add_filter('script_loader_tag', 'TkNa_defer_back_tarots_pro', 1, 3);




// Tamaño cartas
function TkNa_size_cards($spread,$size){
    $array = [
        '1cards' => ['s'=>150,'m'=>200,'l'=>300],
        '0cards' => ['s'=>150,'m'=>200,'l'=>300],
        '2cards' => ['s'=>260,'m'=>300,'l'=>350],
        '3cards' => ['s'=>420,'m'=>465,'l'=>525],
        '4cards' => ['s'=>420,'m'=>520,'l'=>660],
        '5cards' => ['s'=>475,'m'=>590,'l'=>675],
        '6cards' => ['s'=>475,'m'=>555,'l'=>640],
        '7cards' => ['s'=>475,'m'=>555,'l'=>640],
        '8cards' => ['s'=>475,'m'=>555,'l'=>640],
        '9cards' => ['s'=>475,'m'=>555,'l'=>640],
        '10cards' => ['s'=>475,'m'=>555,'l'=>640],
        '3cards1' => ['s'=>330,'m'=>420,'l'=>500],
        '3cards2' => ['s'=>330,'m'=>420,'l'=>500],
        '4cards2' => ['s'=>440,'m'=>540,'l'=>620],
        '4cards1' => ['s'=>240,'m'=>280,'l'=>325],
        '5cards3' => ['s'=>240,'m'=>280,'l'=>325],
        '5cards1' => ['s'=>475,'m'=>530,'l'=>590],
        '5cards2' => ['s'=>475,'m'=>530,'l'=>590],
        '7cards2' => ['s'=>621,'m'=>660,'l'=>700],
        '7cards3' => ['s'=>621,'m'=>660,'l'=>700],
        '10cards1' => ['s'=>465,'m'=>520,'l'=>588],
        '3cards3' => ['s'=>260,'m'=>375,'l'=>460],
        '6cards1' => ['s'=>342,'m'=>445,'l'=>570],
        '6cards2' => ['s'=>255,'m'=>380,'l'=>470],
        '7cards1' => ['s'=>361,'m'=>435,'l'=>570],
        '7cards4' => ['s'=>255,'m'=>350,'l'=>448],
        '8cards1' => ['s'=>509,'m'=>600,'l'=>700],
        '8cards2' => ['s'=>310,'m'=>390,'l'=>490],
        '9cards1' => ['s'=>310,'m'=>380,'l'=>450],
        '9cards2' => ['s'=>427,'m'=>525,'l'=>610],
        '10cards2' => ['s'=>310,'m'=>390,'l'=>450]
    ];
    return $array[$spread][$size];
}






// Reloj Woocomerce
function tkna_reloj($transientTime, $fieldTarot){
    $date = $transientTime - time();
    $date1 = new DateTime("@0"); //starting seconds
    $date2 = new DateTime("@$date"); // ending seconds
    $interval =  date_diff($date1, $date2); //the time difference
    $fieldTarot_S = $fieldTarot * 60;

    if (substr($date,0,1) !== '-') {

        if ( $interval->y !== 0 ) {
            
            $time_S = ($interval->y  * 31536000) + ($interval->m * 2628000);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s year', '%s years', (int) $interval->y, 'woocommerce' ), number_format_i18n( (int) $interval->y )),
                'time2' => sprintf( _n( '%s month', '%s months', (int) $interval->m, 'woocommerce' ), number_format_i18n( (int) $interval->m ) ),
                'proportion' => $result
            ];
        

        }elseif ( $interval->m !== 0) {

            $time_S = ($interval->m  * 2628000) + ($interval->d * 86400);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s month', '%s months', (int) $interval->m, 'woocommerce' ), number_format_i18n( (int) $interval->m ) ),
                'time2' => sprintf( _n( '%s day', '%s days', (int) $interval->d, 'woocommerce' ), number_format_i18n( (int) $interval->d ) ),
                'proportion' => $result
            ];


        }elseif ( $interval->d !== 0) {

            $time_S = ($interval->d  * 86400) + ($interval->h * 3600);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s day', '%s days', (int)  $interval->d, 'woocommerce' ), number_format_i18n( (int)  $interval->d ) ),
                'time2' => sprintf( _n( '%s hour', '%s hours', (int) $interval->h, 'woocommerce' ), number_format_i18n( (int) $interval->h ) ),
                'proportion' => $result
            ];

        }elseif ($interval->h !== 0) {

            $time_S = ($interval->h  * 3600) + ($interval->i * 60);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');
 
            return [
                'time1' => sprintf( _n( '%s hour', '%s hours', (int) $interval->h, 'woocommerce' ), number_format_i18n( (int) $interval->h ) ),
                'time2' => sprintf( _n( '%s minute', '%s minutes', (int) $interval->i, 'woocommerce' ), number_format_i18n( (int) $interval->i ) ),
                'proportion' => $result
            ];

        }elseif ( $interval->i !== 0 ) {

            $time_S = ($interval->i  * 60) + ($interval->s * 1);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s minute', '%s minutes', (int) $interval->i, 'woocommerce' ), number_format_i18n( (int) $interval->i ) ),
                'time2' => sprintf( _n( '%s second', '%s seconds', (int) $interval->s, 'woocommerce' ), number_format_i18n( (int) $interval->s ) ),
                'proportion' => $result
            ];

        }elseif ( $interval->s !== 0  ) {

            if ($fieldTarot !== '' ) {
                 $sg = $fieldTarot_S;
            }else{
                $sg = 60;
            };

            $time_S = ($interval->i  * 60) + ($interval->s * 1);
            $result = ( $time_S / $sg) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s minute', '%s minutes', (int) $interval->i, 'woocommerce' ), number_format_i18n( (int) $interval->i ) ),
                'time2' => sprintf( _n( '%s second', '%s seconds', (int) $interval->s, 'woocommerce' ), number_format_i18n( (int) $interval->s ) ),
                'proportion' => $result
            ];

        };
    }

}



// Reloj EDD
function tkna_reloj_edd($transientTime, $fieldTarot){
    $date = $transientTime - time();
    $date1 = new DateTime("@0"); //starting seconds
    $date2 = new DateTime("@$date"); // ending seconds
    $interval =  date_diff($date1, $date2); //the time difference
    $fieldTarot_S = $fieldTarot * 60;

    if (substr($date,0,1) !== '-') {

        if ( $interval->y !== 0 ) {
            
            $time_S = ($interval->y  * 31536000) + ($interval->m * 2628000);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s year', '%s years', (int) $interval->y, 'tarokina-pro' ), number_format_i18n( (int) $interval->y )),
                'time2' => sprintf( _n( '%s month', '%s months', (int) $interval->m, 'tarokina-pro' ), number_format_i18n( (int) $interval->m ) ),
                'proportion' => $result
            ];
        

        }elseif ( $interval->m !== 0) {

            $time_S = ($interval->m  * 2628000) + ($interval->d * 86400);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s month', '%s months', (int) $interval->m, 'tarokina-pro' ), number_format_i18n( (int) $interval->m ) ),
                'time2' => sprintf( _n( '%s day', '%s days', (int) $interval->d, 'tarokina-pro' ), number_format_i18n( (int) $interval->d ) ),
                'proportion' => $result
            ];


        }elseif ( $interval->d !== 0) {

            $time_S = ($interval->d  * 86400) + ($interval->h * 3600);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s day', '%s days', (int)  $interval->d, 'tarokina-pro' ), number_format_i18n( (int)  $interval->d ) ),
                'time2' => sprintf( _n( '%s hour', '%s hours', (int) $interval->h, 'tarokina-pro' ), number_format_i18n( (int) $interval->h ) ),
                'proportion' => $result
            ];

        }elseif ($interval->h !== 0) {

            $time_S = ($interval->h  * 3600) + ($interval->i * 60);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');
 
            return [
                'time1' => sprintf( _n( '%s hour', '%s hours', (int) $interval->h, 'tarokina-pro' ), number_format_i18n( (int) $interval->h ) ),
                'time2' => sprintf( _n( '%s minute', '%s minutes', (int) $interval->i, 'tarokina-pro' ), number_format_i18n( (int) $interval->i ) ),
                'proportion' => $result
            ];

        }elseif ( $interval->i !== 0 ) {

            $time_S = ($interval->i  * 60) + ($interval->s * 1);
            $result = ( $time_S / $fieldTarot_S) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s minute', '%s minutes', (int) $interval->i, 'tarokina-pro' ), number_format_i18n( (int) $interval->i ) ),
                'time2' => sprintf( _n( '%s second', '%s seconds', (int) $interval->s, 'tarokina-pro' ), number_format_i18n( (int) $interval->s ) ),
                'proportion' => $result
            ];

        }elseif ( $interval->s !== 0  ) {

            if ($fieldTarot !== '' ) {
                 $sg = $fieldTarot_S;
            }else{
                $sg = 60;
            };

            $time_S = ($interval->i  * 60) + ($interval->s * 1);
            $result = ( $time_S / $sg) * 100;
            $result = number_format($result, 0, '', '');

            return [
                'time1' => sprintf( _n( '%s minute', '%s minutes', (int) $interval->i, 'tarokina-pro' ), number_format_i18n( (int) $interval->i ) ),
                'time2' => sprintf( _n( '%s second', '%s seconds', (int) $interval->s, 'tarokina-pro' ), number_format_i18n( (int) $interval->s ) ),
                'proportion' => $result
            ];

        };
    }

}


// delete directory
function delete_directory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}




// Traducir el texto que se carga antes de cargar el plugin
// Se utiliza en el valor final que se imprime
if (!function_exists('tkina_resolve_callable')) {
  function tkina_resolve_callable($value)
  {

    $lock_values = [
      'Shuffle',
      // Puedes añadir otros valores que no deben ser tratados como funciones aquí
    ];

    // Si $value es un string y está en $lock_values, devolverlo directamente.
    if (is_string($value) && in_array($value, $lock_values, true)) {
      return $value;
    }

    return is_callable($value) ? call_user_func($value) : $value;
  }
}
