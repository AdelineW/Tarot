<?php

			$tarots = array();
			$tarotsN = array();
			$tarotsN2 = array();
			$tarotsAll = array();
			$restrict_post_id = array();
	
			// dependiendo desde que pagina se cargue la funcion TkNaP_pro_option
			if ($pageId == 'tarokkina_pro_page_crb_carbon_fields_container_tarots7') {
				$tarots = carbon_get_theme_option('tarokki_tarot_complex7') ?? [];
			}else{
				$tarots = get_option('tarokki_tarotsAll7') ?? [];
			}
	
			foreach ($tarots as $tarot) {
				$deck = $tarot['tkta_barajas'];

	
				unset($tarots);
				unset($tarotsN);
				$nombreSC = 'tarokina_'.tark_pro_x8z_urlText($tarot['tkta_name']);
				$tarots [] = $tarot;
				$tarotsAll[] = $tarot;
				$tarotsN[] = $nombreSC;
				$tarotsN2[] = $nombreSC;
				
				$tkna_shop_id = $tarot['tkna_shop_id'] ?? '';
				$tkta_time_restriction = $tarot['tkta_time_restriction'] ?? '';
				if ( $tkna_shop_id != '') {
					$restrict_post_id[ $tkna_shop_id ] = $tkta_time_restriction;
				}
				
				update_option( $nombreSC, array_fill_keys($tarotsN,$tarots) );

				$args = array(
					'post_type' => 'tarokkina_pro',
					'fields'=>'ids',
					'cache_results' => false,
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
				$Posts_ID = $cardsids->posts ?? [];

					$cards = [];
					$cards_ssl = [];
					$cardsFull = [];
					$cardsFull_ssl = [];
					foreach ($Posts_ID as $Post_ID) {

						$cardID = get_post_thumbnail_id( $Post_ID );
						$cardImg = wp_get_attachment_image_src( $cardID, 'tarokkina_pro-mini');
						$cardImgFull = wp_get_attachment_image_src( $cardID, 'full');
						
						if ($cardImg == false ) {
							$cards[$Post_ID]= plugin_dir_url(dirname( __FILE__,2 ) ).'img/no-card.png';
							$cardsFull[$Post_ID]= plugin_dir_url(dirname( __FILE__,2 ) ).'img/no-card.png';
						}else{
							$cards[$Post_ID]= $cardImg[0];
							$cardsFull[$Post_ID]= $cardImgFull[0];
						
							$cards_ssl[$Post_ID]= str_replace('http://','https://',$cardImg[0]);
							$cardsFull_ssl[$Post_ID]= str_replace('http://','https://', $cardImgFull[0]);
						}

					}

					update_option( $deck .'_deck_tarokki' , $cards);
					update_option( $deck .'_deck_tarokki_full' , $cardsFull);
					update_option( $deck .'_deck_tarokki_ssl' , $cards_ssl);
					update_option( $deck .'_deck_tarokki_full_ssl' , $cardsFull_ssl);
			}

	
			$guadandoNames = (get_option( 'tarokki_tarot_names7')!== false) ? get_option( 'tarokki_tarot_names7') : array() ;
	
			if ($guadandoNames !== array()) {
				$deletes = array_diff($guadandoNames, $tarotsN2);
	
				foreach ($deletes as $delete ) {
					delete_transient( $delete);
				}
			}
			
			update_option( 'tarokki_tarot_names7',$tarotsN2);
			update_option( 'tarokki_tarotsAll7',$tarotsAll);
			update_option( 'tarokki_EDD_products7',$restrict_post_id);


