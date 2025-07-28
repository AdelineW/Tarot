<?php
if ( function_exists('elementor_load_plugin_textdomain')) {
 
    function TkNa_init_elementor() {
        add_shortcode( 'tarot', 'TkNaF_shortcode_elementor');
        add_shortcode( 'tarot_pro', 'TkNa_shortcode_elementor');
    }
    add_action( 'elementor/preview/init', 'TkNa_init_elementor' );



    function TkNa_shortAdmin_elementor($output, $tag, $attr, $m){ 	
        $getElementor = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : null;	
        if(  $tag == 'tarot' && $getElementor == 'elementor' || $tag == 'tarot_pro' && $getElementor == 'elementor' ) {
            $nameTarot = strtr($attr['name'], '_', ' ');
            $output = '<div style="position: relative;border: 1px solid;height: 350px;text-align: center;padding-top: 38px;">
            <h4 style="margin: 0;font-weight: 400;">'.ucfirst($nameTarot).'​</h4>
            <div style="margin-top: 8px;font-size: 13px;margin-bottom: 20px;">*&nbsp;'.__('Click on preview changes to see the tarot.', 'tarokina-pro').'</div>
            <div style="display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;"><img style="display: block;" src="'. plugin_dir_url( __DIR__ ).'img/tarokina-img-elementor.png" alt="tarokina elementor"></div>
            </div>';
        };  
        return $output; 
    } 
    add_filter('do_shortcode_tag', 'TkNa_shortAdmin_elementor', 1, 4);
    

}
