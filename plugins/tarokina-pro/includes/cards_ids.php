<?php
// Crear los ids de las cartas por baraja
$term_query = new WP_Term_Query( array( 
    'taxonomy' => 'tarokkina_pro-cat',
    'orderby'                => 'name',
    'order'                  => 'ASC',
    'child_of'               => 0,
    'parent'                 => 0,
    'fields'                 => 'all',
    'hide_empty'             => false,
) );
$terms_ids = $term_query->terms ?? [];
foreach ($terms_ids as $id) {
    $deck = $id->term_id;

    $args = array(
        'post_type' => 'tarokkina_pro',
        'fields'=>'ids',
        'tax_query' => array(
            array(
                'taxonomy' => 'tarokkina_pro-cat',
                'field' => 'term_id',
                'terms' => $deck,
                'include_children' => true,
                )
            ),
        'post_status' => 'publish',
        'posts_per_page' => -1
        );
    
    $cardsids = new WP_Query( $args );
    update_option( $deck. '_tarokki_cardsids', $cardsids->posts );
    wp_reset_postdata();
};