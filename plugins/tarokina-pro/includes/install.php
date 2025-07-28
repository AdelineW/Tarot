<?php


    global $wpdb;

    $json    = TAROKINA_LIB_PATH . 'demo-deck/data.json';
    $json2   = file_get_contents($json);
    $array   = json_decode($json2,true);


    $id_demo = 99999999;
    $nameDemo = 'Demo tarot';
    $nameDeck = 'Arnelio';

    foreach ($array as $subarray) {

        $post_name = $subarray['post_name'];
        $titulo    = $subarray['title'];
        $imagen    = plugins_url() .'/'. $subarray['image'];
        $texto     = $subarray['texto'];


            

            // Register Post Data
            $post                = array();
            $post['post_name']   = $post_name;
            $post['post_status'] = 'publish';
            $post['post_type']   = 'tarokkina_pro';    // can be a CPT too
            $post['tax_input']   = array('tarokina');
            $post['post_title']  = $titulo;


            // Create Post
            $post_id = wp_insert_post( $post );
            // Vincular taxonomia
            wp_set_object_terms( $post_id, $nameDeck, 'tarokkina_pro-cat', true );

            // Add Featured Image to Post
            $image_url  = $imagen;
            $upload_dir = wp_upload_dir();                  // Set upload folder

            $ssl_F=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );  
            $image_data = file_get_contents($image_url, false, stream_context_create($ssl_F));

            $filename   = basename( $image_url );           // Create image file name

            // Check folder permission and define file location
            if( wp_mkdir_p( $upload_dir['path'] ) ) {
                $file = $upload_dir['path'] . '/' . $filename;
            } else {
                $file = $upload_dir['basedir'] . '/' . $filename;
            }


            // Create the image  file on the server
            file_put_contents( $file, $image_data );
            // Check image file type
            $wp_filetype = wp_check_filetype( $filename, null );


            // Set attachment data
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title'     => sanitize_file_name( $filename ),
                'post_content'   => update_post_meta($post_id,'_tkta_text_'.$id_demo.'expert0',$texto),
                'post_status'    => 'publish',
            );

            // Create the attachment
            $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

            // Include image.php
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            // Define attachment metadata
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

            // Assign metadata to attachment
            wp_update_attachment_metadata( $attach_id, $attach_data );

            // And finally assign featured image to post
            set_post_thumbnail( $post_id, $attach_id );

             // Vinvular los textos con el tarot
            update_post_meta($post_id,'tkta_text_'.$id_demo.'expert0',$post_id.$id_demo.'expert0');

        


    }// foreach

    $tarot_id_cat = get_term_by('name', $nameDeck, 'tarokkina_pro-cat')->term_id;

    $numDemo = $wpdb->get_var($wpdb->prepare("
    SELECT option_name
    FROM $wpdb->options
    WHERE option_value = '%d'
    ",$id_demo));


    delete_option( '_tarokki_tarot_complex|||0|_empty' );
    $id_complex = (int)substr($numDemo,31,1);

    if (false !== get_option( 'tarokki_tarot_names' )) {
        $n = count(get_option( 'tarokki_tarot_names' ));
    }else{
        $n = $id_complex;
    }



         // Crear transient con las imagenes de la categoría
        $args = array(
            'post_type' => 'tarokkina_pro',
            'fields'=>'ids',
            'tax_query' => array(
                array(
                    'taxonomy' => 'tarokkina_pro-cat',
                    'field' => 'term_id',
                    'terms' => $tarot_id_cat,
                    'include_children' => true,
                    )
                ),
            'post_status' => 'publish',
            'posts_per_page' => -1
            );

        $cardsids = new WP_Query( $args );
        

        update_option( $tarot_id_cat. '_tarokki_cardsids', $cardsids->posts );
              
        update_option( '_tarokki_tarot_complex|tkta_barajas|'.$n.'|0|value',$tarot_id_cat,'no');

        update_option( '_tarokki_tarot_complex|||'.$n.'|value','tarokina-pro','no' );
        update_option( '_tarokki_tarot_complex|tkta_id|'.$n.'|0|value', $id_demo, 'no');
        update_option( '_tarokki_tarot_complex|tkta_type|'.$n.'|0|value','tarokina-pro', 'no');
        update_option( '_tarokki_tarot_complex|tkta_name|'.$n.'|0|value',$nameDemo, 'no');
        update_option( '_tarokki_tarot_complex|tkta_mode|'.$n.'|0|value','basic','no' );
        update_option( '_tarokki_tarot_complex|tkta_image_backface|'.$n.'|0|value',false,'no' );
        update_option( '_tarokki_tarot_complex|tkta_spread|'.$n.'|0|value','3cards','no' );
        update_option( '_tarokki_tarot_complex|tkta_meanings|'.$n.'|0|value','yes', 'no');
        update_option( '_tarokki_tarot_complex|tkta_1st|'.$n.'|0|value',esc_html__('Present','tarokina-pro'),'no' );
        update_option( '_tarokki_tarot_complex|tkta_2st|'.$n.'|0|value',esc_html__('Past','tarokina-pro'),'no' );
        update_option( '_tarokki_tarot_complex|tkta_3st|'.$n.'|0|value',esc_html__('Future','tarokina-pro'),'no' );
        update_option( '_tarokki_tarot_complex|tkta_4st|'.$n.'|0|value',null, 'no' );
        update_option( '_tarokki_tarot_complex|tkta_5st|'.$n.'|0|value',null, 'no');
        update_option( '_tarokki_tarot_complex|tkta_6st|'.$n.'|0|value',null, 'no' );
        update_option( '_tarokki_tarot_complex|tkta_7st|'.$n.'|0|value',null, 'no' );
        update_option( '_tarokki_tarot_complex|tkta_8st|'.$n.'|0|value',null, 'no' );
        update_option( '_tarokki_tarot_complex|tkta_9st|'.$n.'|0|value',null, 'no' );
        update_option( '_tarokki_tarot_complex|tkta_10st|'.$n.'|0|value',null,'no' );
        update_option( '_tarokki_tarot_complex|tkta_title_spread|'.$n.'|0|value',esc_html__('Tarot title','tarokina-pro'),'no' );
        update_option( '_tarokki_tarot_complex|tkta_subtitle_spread|'.$n.'|0|value',esc_html__('Subtitle','tarokina-pro'),'no' );
        update_option( '_tarokki_tarot_complex|tkta_legend|'.$n.'|0|value',esc_html__('Positions','tarokina-pro'),'no' );
        update_option( '_tarokki_tarot_complex|tkta_pro_text_top_deck2|'.$n.'|0|value',esc_html__('Final card','tarokina-pro'),'no' );
        update_option( '_tarokki_tarot_complex|tkta_pro_text_button|'.$n.'|0|value',esc_html__('Click to start your reading','tarokina-pro'),'no' );
        update_option( '_tarokki_tarot_complex|tkta_pro_text_button_volver|'.$n.'|0|value',esc_html__('New reading','tarokina-pro'),'no' );
        update_option( '_tarokki_tarot_complex|tkta_title_spread_color|'.$n.'|0|value',null,'no' );
        update_option( '_tarokki_tarot_complex|tkta_texto_spread_color|'.$n.'|0|value',null,'no' );
        update_option( '_tarokki_tarot_complex|tkta_background_color|'.$n.'|0|value',null,'no' );
        update_option( '_tarokki_tarot_complex|tkta_image_background|'.$n.'|0|value',false,'no' );
        update_option( '_tarokki_tarot_complex|tkta_image_transparent|'.$n.'|0|value','0','no');
    


    wp_reset_postdata();
