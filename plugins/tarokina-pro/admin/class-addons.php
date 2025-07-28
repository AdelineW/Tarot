<?php

class tarokki_addons {

    public function __construct( ) {

    }


    // Lista con los Addons activados en el área de plugins
    private function TkNaP_addons_url(){
        global $wpdb;
       $plugins_activados = $wpdb->get_var(
        $wpdb->prepare("
           ( SELECT * FROM (
               SELECT {$wpdb->options}.option_value
               FROM {$wpdb->options}
               WHERE option_name = %s
           ) as T
           );
           ",
           'active_plugins'
           )
       );
    
       $filtered = array_filter(maybe_unserialize($plugins_activados), function ($key) {
        return strpos($key, 'tarokki-') === 0;
        });
        return  array_values($filtered);
      }



    // Tarots 1 -10
    public function TkNaP_Escritor(){
        require_once TAROKINA_ADMIN_PATH . 'tarots/escritor.php';
    }

    public function TkNaP_Vista(){
        require_once TAROKINA_ADMIN_PATH . 'tarots/vista.php';
    }


    // Tarots 11 -20
    public function TkNaP_Escritor2(){
        require_once TAROKINA_ADMIN_PATH . 'tarots2/escritor2.php';
    }
    public function TkNaP_Vista2(){
        require_once TAROKINA_ADMIN_PATH . 'tarots2/vista2.php';
    }

    // Tarots 21 -30
    public function TkNaP_Escritor3(){
        require_once TAROKINA_ADMIN_PATH . 'tarots3/escritor3.php';
    }
    public function TkNaP_Vista3(){
        require_once TAROKINA_ADMIN_PATH . 'tarots3/vista3.php';
    }


    // Tarots 31 -40
    public function TkNaP_Escritor4(){
        require_once TAROKINA_ADMIN_PATH . 'tarots4/escritor4.php';
    }
    public function TkNaP_Vista4(){
        require_once TAROKINA_ADMIN_PATH . 'tarots4/vista4.php';
    }

    // Tarots 41 -50
    public function TkNaP_Escritor5(){
        require_once TAROKINA_ADMIN_PATH . 'tarots5/escritor5.php';
    }
    public function TkNaP_Vista5(){
        require_once TAROKINA_ADMIN_PATH . 'tarots5/vista5.php';
    }

    // Tarots 51 -60
    public function TkNaP_Escritor6(){
        require_once TAROKINA_ADMIN_PATH . 'tarots6/escritor6.php';  
    }
    public function TkNaP_Vista6(){
        require_once TAROKINA_ADMIN_PATH . 'tarots6/vista6.php';
    }

    // Tarots 61 -70
    public function TkNaP_Escritor7(){
        require_once TAROKINA_ADMIN_PATH . 'tarots7/escritor7.php';
    }
    public function TkNaP_Vista7(){
        require_once TAROKINA_ADMIN_PATH . 'tarots7/vista7.php';
    }

    // Tarots 71 -80
    public function TkNaP_Escritor8(){
        require_once TAROKINA_ADMIN_PATH . 'tarots8/escritor8.php';
    }
    public function TkNaP_Vista8(){
        require_once TAROKINA_ADMIN_PATH . 'tarots8/vista8.php';
    }

    // Tarots 81 -90
    public function TkNaP_Escritor9(){
        require_once TAROKINA_ADMIN_PATH . 'tarots9/escritor9.php';
    }
    public function TkNaP_Vista9(){
        require_once TAROKINA_ADMIN_PATH . 'tarots9/vista9.php';
    }

    // Tarots 91 -100
    public function TkNaP_Escritor10(){
        require_once TAROKINA_ADMIN_PATH . 'tarots10/escritor10.php';
    }
    public function TkNaP_Vista10(){
        require_once TAROKINA_ADMIN_PATH . 'tarots10/vista10.php';
    }


    // admin/tarots/grid.php
    public function TkNaP_Grid(){
        require_once TAROKINA_ADMIN_PATH . 'grid.php';
    }


    // admin/fields/fields_options.php
    public function TkNaP_Options(){
        require_once TAROKINA_ADMIN_PATH . 'fields/fields_options.php';

    }


}// class



