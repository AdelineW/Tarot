<?php
/**
 * Registrar un custom post type
 *
 * @see get_post_type_labels().
 */

function TkNaP_pro_post_type() {
    $icon = 'PHN2ZyBpZD0ibWVudSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgMTE5LjgxIDk4LjMzIj48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6I2ZmZjtmaWxsLXJ1bGU6ZXZlbm9kZDt9PC9zdHlsZT48L2RlZnM+PHRpdGxlPnRhcm9raW5hLW1lbnU8L3RpdGxlPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTExNi4xNSw2Ni4zOUM5OS45Myw3NC4yMyw4My42NSw4Miw2Ny41LDkwYTM2LjQyLDM2LjQyLDAsMCwxLTI2LjcsMi43MmMtMTEuMzctMy4wOC0yMi44LTYtMzQuMTctOUE1LjkzLDUuOTMsMCwwLDAsLjgyLDg1YzEuMjYuMzcsMi4yOS42OSwzLjMyLDEsMTUuMzksNCwzMC43OCw4LDQ2LjE1LDEyLjEzYTcsNywwLDAsMCw1LjE3LS41NFE4My42LDgzLjg0LDExMS43OCw3MC4yOGMyLjU0LTEuMjIsNS0yLjQ4LDcuOS0zLjg3QTMuMTMsMy4xMywwLDAsMCwxMTYuMTUsNjYuMzlaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNDIuMjEsNjEuODVjNC44OCwxLjU0LDguNzcsMS4yMywxMy4xNy0xLjQ3LDYtMy42OCwxMi03LjIzLDE2LjYzLTEyLjc3LDcuNjQtOS4xLDEwLjg0LTE5LjUxLDguNDItMzEuNTZhMy4yOCwzLjI4LDAsMCwwLTIuNjEtMi44M0M2My4zLDksNDguOCw0LjcsMzQuMjkuNDNjLTIuNi0uNzYtMi42LS43NC0yLjE5LDJhMzQuMTIsMzQuMTIsMCwwLDEtMi44NiwyMC40MkE1MC4zNyw1MC4zNywwLDAsMSw0LjMxLDQ3LjEzYy0xLjQzLjY2LTIuODcsMS4zLTQuMzEsMS45NWwuMTQuNTNjLjU0LjE3LDEuMDguMzYsMS42My41MkMxNS4yNiw1NCwyOC44Myw1Ny42MSw0Mi4yMSw2MS44NVoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik01MC41Myw3NC42NmE2LjEsNi4xLDAsMCwwLDQuNTctLjQ0UTgwLjE5LDYyLDEwNS4zNSw0OS45MUwxMTkuNTcsNDNjLS41Mi0uMzYtLjY3LS41NC0uODYtLjU5LTEwLjA3LTIuNjctMjAuMTQtNS4zLTMwLjItOEM4NywzNCw4Ni42NSwzNC44Miw4Ni4yNiwzNiw4Mi4xNCw0OC43OSw3My44Nyw1Ny44NSw2Mi44LDY0LjM2Yy0zLjIxLDEuODgtNi41MiwzLjU3LTkuNyw1LjQ5YTcuNCw3LjQsMCwwLDEtNi4zOS44M0MzNC4wOCw2NywyMS4zOSw2My40Nyw4Ljc4LDU5LjY3Yy0zLjEtLjk0LTUuMzYuMS04LjExLDEuODdsMy41LjkzQzE5LjYzLDY2LjUyLDM1LjA5LDcwLjU1LDUwLjUzLDc0LjY2WiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTExMy43Myw1NC45MUM5OC4zMSw2Mi40Miw4Mi44MSw2OS43Myw2Ny40NCw3Ny4zNUEzNi4yMSwzNi4yMSwwLDAsMSw0MSw4MC4wOGMtMTAtMi43LTIwLTUuMDgtMjkuOTUtOC0zLjg0LTEuMTQtNi44My0uMTMtMTAuMjUsMiwuNjkuMjQsMSwuMzksMS4zNi40OHEyNC4yMyw2LjM1LDQ4LjQzLDEyLjczYTYuMDgsNi4wOCwwLDAsMCw0LjU2LS40NVE4My41Myw3My4wNiwxMTIsNTkuMzRjMi40NS0xLjE4LDQuODktMi4zOCw3LjgxLTMuODFDMTE3LjQ0LDU0LjczLDExNS43Nyw1My45MiwxMTMuNzMsNTQuOTFaIi8+PC9zdmc+';
    $labelsPT = array(
        'name'                  => _x( 'Cards', 'General name', 'tarokina-pro' ),
        'singular_name'         => _x( 'Card', 'Singular name', 'tarokina-pro' ),
        'menu_name'             => 'Tarokina Pro',
        'name_admin_bar'        => _x( 'Add Card', 'Add New on Toolbar', 'tarokina-pro' ),
        'add_new'               => __( 'Add Card', 'tarokina-pro' ),
        'add_new_item'          => __( 'Add New Card', 'tarokina-pro' ),
        'new_item'              => __( 'New Card', 'tarokina-pro' ),
        'edit_item'             => __( 'Edit Card', 'tarokina-pro' ),
        'view_item'             => __( 'View Card', 'tarokina-pro' ),
        'all_items'             => __( 'Cards', 'tarokina-pro' ),
        'search_items'          => __( 'Search', 'tarokina-pro' ),
        'not_found'             => __( 'No Cards. Install the demo tarot or add cards to a deck.', 'tarokina-pro' ).'&nbsp;&nbsp;<a style=" text-decoration:underline;font-weight:bold" href="'.admin_url( 'edit.php?post_type=tarokkina_pro&page=crb_carbon_fields_container_tarots.php' ).'">'.__( 'Go to install demo tarot', 'tarokina-pro' ).'</a>',
        'not_found_in_trash'    => __( 'No Cards found in Trash.', 'tarokina-pro' ),
        'featured_image'        => __( 'Tarot card image', 'tarokina-pro' ),
        'set_featured_image'    => __( 'Set cover image', 'tarokina-pro' ),
        'remove_featured_image' => __( 'Remove cover image', 'tarokina-pro' ),
        'use_featured_image'    => __( 'Use as cover image', 'tarokina-pro' ),
        'archives'              => __( 'Card archives', 'tarokina-pro' ),
        'insert_into_item'      => __( 'Insert into Card', 'tarokina-pro' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Card', 'tarokina-pro' ),
        'filter_items_list'     => __( 'Filter Cards list', 'tarokina-pro' ),
        'items_list_navigation' => __( 'Cards list navigation', 'tarokina-pro' ),
        'items_list'            => __( 'Cards list', 'tarokina-pro' )
    );

    $argsPT = array(
        'labels'             => $labelsPT,
        'public'             => false,
        'publicly_queryable' => false,
        'has_archive'        => true,
        'map_meta_cap'       => true,
        '_edit_link'         => 'post.php?post_type=tarokkina_pro&post=%d&i=0',
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'tarokina','with_front' => false),
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'data:image/svg+xml;base64,'.$icon,
        'show_in_rest'       => false,
        'supports'           => array('title','thumbnail')
    );


    if (current_user_can('administrator')) {
      register_post_type( 'tarokkina_pro', $argsPT );
    }



// Taxonomía Categoría
  $labelsCat1 = array(
    'name'              => _x( 'Decks', 'taxonomy general name', 'tarokina-pro' ),
    'singular_name'     => _x( 'Deck', 'taxonomy singular name', 'tarokina-pro' ),
    'search_items'      => __( 'Search', 'tarokina-pro' ),
    'all_items'         => __( 'All Decks', 'tarokina-pro' ),
    'edit_item'         => __( 'Edit deck', 'tarokina-pro' ),
    'update_item'       => __( 'Updated deck', 'tarokina-pro' ),
    'add_new_item'      => __( 'Add new deck', 'tarokina-pro' ),
    'new_item_name'     => __( 'New deck', 'tarokina-pro' ),
    'menu_name'         => __( 'Decks', 'tarokina-pro' ),
    'not_found'         => __( 'No decks found', 'tarokina-pro' )
  );

  $argsCat1 = array(
    'public'             => false,
    'publicly_queryable' => false,
    'hierarchical'      => true,
    'labels'            => $labelsCat1,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'show_in_rest'       => false,
    'rewrite'           => array( 'slug' => 'decks','with_front' => false )
  );

  register_taxonomy( 'tarokkina_pro-cat', array( 'tarokkina_pro' ), $argsCat1 );



// Filtro para taxonomias
function TkNaP_pro_x8z_filtroTable( $post_type, $which ) {
	// Apply this only on a specific post type
	if ( 'tarokkina_pro' !== $post_type )
		return;

	// A list of taxonomy slugs to filter by
	$taxonomies = array( 'tarokkina_pro-cat' );

	foreach ( $taxonomies as $taxonomy_slug ) {

		// Retrieve taxonomy data
		$taxonomy_obj = get_taxonomy( $taxonomy_slug );
		$taxonomy_name = $taxonomy_obj->labels->name;

		// Retrieve taxonomy terms
		$terms = get_terms( $taxonomy_slug );

		// Display filter HTML
		echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
		echo '<option value="">' . sprintf( esc_html__( 'All %s', 'tarokina-pro' ), $taxonomy_name ) . '</option>';
		foreach ( $terms as $term ) {
			printf(
				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
				$term->slug,
				( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
				$term->name,
				$term->count
			);
		}
		echo '</select>';
	}

}
add_action( 'restrict_manage_posts', 'TkNaP_pro_x8z_filtroTable' , 10, 2);

// quitar filtro fecha en posts
add_filter('months_dropdown_results', '__return_empty_array');




// Columas en el post type
add_filter( 'manage_tarokkina_pro_posts_columns', 'TkNaP_pro_filter_posts_columns' );
function TkNaP_pro_filter_posts_columns( $columns ) {
  $columns['image'] = esc_html__( 'Image','tarokina-pro' );
  return $columns;
}



add_filter( 'manage_tarokkina_pro_posts_columns', 'TkNaP_pro_realestate_columns' );
function TkNaP_pro_realestate_columns( $columns ) {

    $columns = array(
      'cb' => $columns['cb'],
      'image' => esc_html__( 'image' ,'tarokina-pro' ),
      'title' => esc_html__( 'Card','tarokina-pro' ),
      'taxonomy-tarokkina_pro-cat' => esc_html__( 'Deck','tarokina-pro' )
    );

  return $columns;
}


// // ordenar columna cartas
// add_action( 'pre_get_posts', 'tarokkina_pro_order_cards' );
// function tarokkina_pro_order_cards( $query ) {
//   if( ! is_admin() || ! $query->is_main_query() ) {
//     return;
//   }
//   $query->set( 'orderby', 'post__in' );
// }


add_action( 'manage_tarokkina_pro_posts_custom_column', 'TkNaP_pro_realestate_column', 10, 2);
function TkNaP_pro_realestate_column( $column, $post_id ) {

      // Image column /  Cambiar tamaño sin imagen
      if ( 'image' === $column ) {
        $imgPT =  get_the_post_thumbnail( $post_id, 'tarokkina_pro-mini');
        $imageT = (!empty($imgPT)) ? $imgPT : '<div class="no-image"></div>';
        $arr = array(
            'img' => array(
                'src' => array(),
                'width' => array(),
                'height' => array()
            ),
            'div' => array(
              'class' => array()
            )
        );
  
         echo '<a href="'.admin_url('post.php?post_type=tarokkina_pro&post='.$post_id.'&i=0&action=edit').'">'.wp_kses( $imageT, $arr ).'</a>';
  
      }
      
}


/**
 * Remove columns from category-cat
 *
 */
add_filter('manage_edit-tarokkina_pro-cat_columns', function ( $columns ) {
  $columns['name'] = esc_html__( 'Decks','tarokina-pro' );
  $columns['posts'] = esc_html__( 'Cards','tarokina-pro' );
  if( isset( $columns['description'] ) || isset( $columns['slug'] ) )
      unset( $columns['description'] );
      unset( $columns['slug'] );
  return $columns;
});



// CSS TABLA
add_action('admin_head', 'TkNaP_pro_cssTable');
function TkNaP_pro_cssTable() {
    global $current_screen;

    if ( 'edit-tarokkina_pro' == $current_screen->id ) {
        ?><style>
        .column-image {width: 56px !important;}
        .column-image img {width: 100% !important; height:auto !important}
        @media only all and (max-width:853px) { 
            .column-taxonomy-tarokkina_pro-cat,
            .column-image {
                display: none !important;
            }
        }
        </style><?php
    }
}


add_action('admin_head', 'TkNaP_pro_cssTable2');
function TkNaP_pro_cssTable2(){
  ?><style>
  .bloq_del_tarots span.cf-complex__group-index::before{
    content: '<?php echo esc_html__( 'Tarot', 'tarokina-pro' )?>';
    margin-right: 8px;
  }
  .content_btnCardIds{
  background-color: #742C7A;
  color: #fff;
  padding: 20px 10px;
}

.btnCardIds{
  background-color: #d63638;
  color: #fff !important;
  padding: 4px 14px;
  border-radius: 4px;
  text-decoration: none;
}
.btnCardIds:hover{
    background-color: #7fb651;
    text-decoration: none;
  }
  </style><?php
}



// // yoast problem
//   add_filter( 'wpseo_primary_term_taxonomies', '__return_empty_array' );


}


// Post Type
add_action( 'init','TkNaP_pro_post_type',1);