<?php
    // restar los 2 que estan el la variable Top
    $numLoop = $spread_N - 2;

    $Table_Row_expert = [];
    for ($i=1; $i <= $spread_N ; $i++) { 
        $the_card = (isset($tarot['tkta_'.esc_html($i).'st'])) ? str_replace("'", "’", $tarot['tkta_'.esc_html($i).'st'])
 : '' ;
        $Table_Row_expert [] = '<div class="table-row">
        <div class="table-data">
          <span class="numTitle">'.esc_html($i).'</span>&nbsp;'.esc_html($the_card) .'
        </div>
        </div>';
    };

    $TOP_EXPERT = "
    Container::make( 'post_meta', '<span $style class=\"t_mode\"><span class=\"spre\">".esc_html__('Tarot','tarokina-pro')."</span></span>".esc_html($tirada)."' )
        ->where( 'post_term', '=', array(
            'field' => 'id',
            'value' => $deks,
            'taxonomy' => 'tarokkina_pro-cat',
        ) )
        ->add_fields(array(
            Field::make( 'html', 'tkta_texthtml_0_' )
            ->set_html( '
            <div class=\"cab_carta\">
                    <div class=\"cab_cartaA\">  
                        <div class=\"subT\">
                            <div class=\"textTopMce\">'.sprintf( esc_html__( 'Spread %s', 'tarokina-pro' ), '<span>".esc_html($spread_N)."</span>' ).'</div>
                        </div>
                        <div class=\"spreadImg\"><img width=\"200px\" src=\"".esc_url($image_Spread)."\"></div>
                    </div>
                    <div class=\"cab_cartaB\">
                        <div>
                        <table>
                            <tr>
                            <td class=\"td1Box\">'.esc_html__( 'Tarot', 'tarokina-pro' ).':</td> <td class=\"td2Box mode\">".esc_html($tirada)."</td>
                            </tr>
                            <tr>
                            <td class=\"td1Box\">'.esc_html__( 'Card', 'tarokina-pro' ).':</td> <td class=\"tdCard\">'.\$title.'</td>
                            </tr>
                            <tr>
                            <td class=\"td1Box\">'.esc_html__( 'Mode', 'tarokina-pro' ).':</td> <td class=\"td2Box td_mode\">".esc_html($mode_name)."</td>
                            </tr>
                            <tr>
                            <td class=\"td1Box\">'.esc_html__( 'Deck', 'tarokina-pro' ).':</td> <td class=\"td2Box decknameLi\"></td>
                            </tr>
                            <tr>
                            <td class=\"svgTex td1Box\">'.esc_html__( 'Texts', 'tarokina-pro' ).':</td> <td class=\"td2Box\">".esc_html($spread_N)."</td>
                            </tr>
                        </table>
                        </div>
                    </div>
            </div>  
            <div class=\"bloqTableLegend\">
            <div class=\"text1\">
                <div class=\"BigNum\">1</div>
                <img width=\"83px\" src=\"".esc_url($imgPost)."\">
            </div>
            <div class=\"table\">
                <div class=\"table-content\">
                $Table_Row_expert[0]       
                </div>
            </div>
            </div>
            <div class=\"btnInfo\">
            <a id=\"hide$id_unique\" href=\"#hide$id_unique\" class=\"hide\">'.esc_html__('Info','tarokina-pro').'&nbsp;<span class=\"dashicons dashicons-arrow-right\"></span></a>
            <a id=\"show$id_unique\" href=\"#show$id_unique\" class=\"show\">'.esc_html__('Info','tarokina-pro').'&nbsp;<span class=\"dashicons dashicons-arrow-down\"></span></a>
            <div class=\"details\">
            <ul class=\"descriptionText\">
            <li>'.esc_html__('Basic Mode: A single text is used for all references in each card. Example: Tarot with 3 spreads: present, past and future. Same text for present, past and future. You will opt for a tarot less precise in the answers but simpler to create. The final quality will depend on the skill you have in creating the texts.','tarokina-pro').'</li>
            <li>'.esc_html__('Reversed Basic Mode: 2 texts are used for all references on each card (A or B), if the card is reversed, the alternative text B will be used. Example: Tarot with 3 spreads: present, past and future. 1 text (A or B) for present, past and future. The final quality will depend on the skill you have in elaborating the 2 texts.','tarokina-pro').'</li>
            <li class=\"text000\">'.esc_html__('Expert Mode: Multiple texts are used per card (1 text per reference in each card). You will get a very precise tarot in the answers. There are many texts but the final result will be very accurate, since each reference will have its own text.','tarokina-pro').'</li>
            <li>'.esc_html__('Reversed expert Mode: Multiple texts are used per card (2 texts per reference on each card). If the card is reversed, the alternative text B will be used on each reference. There are twice as many texts as in Expert mode.','tarokina-pro').'</li>
            </ul>
            </div>   
        </div>
        ' )->set_classes( 'text_F0' ),
        Field::make( 'rich_text', 'tkta_text_".$id_unique.$modes."0', $tabTitle )
        ->set_classes( 'text_F0' ),
            Field::make( 'html', 'tarokki_texthtml_1' )
            ->set_html( '
            <div class=\"bloq_BoxSecun\"> 
            <div class=\"boxSecun\">
                <div class=\"spreadImg\"><img width=\"100px\" src=\"".esc_url($image_Spread)."\"></div>
            </div>      
            </div>
            <div class=\"bloqTableLegend\">
            <div class=\"text1\">
                <div class=\"BigNum\">2</div>
                <img width=\"83px\" src=\"".esc_url($imgPost)."\">
            </div>
            <div class=\"table\">
                <div class=\"table-content\">
                    $Table_Row_expert[1]
                </div>
            </div>
            </div>
        ' )->set_classes( 'text_F1' ),
        Field::make( 'rich_text', 'tkta_text_".$id_unique.$modes."1', $tabTitle )
        ->set_classes( 'text_F1' )";


    $FIELDS_EXPERT = [];
    for ($i=0; $i < $numLoop ; $i++) { 

        $num_html = 2 + $i;
        $class_F = ($num_html % 2 == 0) ? 0 : 1 ;
        $BigNum = 3 + $i;


        $FIELDS_EXPERT [] = ",
        Field::make( 'html', 'tarokki_texthtml_".$num_html."' )
        ->set_html( '
        <div class=\"bloq_BoxSecun\"> 
        <div class=\"boxSecun\">
            <div class=\"spreadImg\"><img width=\"100px\" src=\"".esc_url($image_Spread)."\"></div>
        </div>      
        </div>
        <div class=\"bloqTableLegend\">
        <div class=\"text1\">
            <div class=\"BigNum\">".esc_html($BigNum)."</div>
            <img width=\"83px\" src=\"".esc_url($imgPost)."\">
        </div>
            <div class=\"table\">
                <div class=\"table-content\">
                    $Table_Row_expert[$num_html]
                </div>
            </div>
        </div>
        ' )->set_classes( 'text_F".$class_F."' ),
        Field::make( 'rich_text', 'tkta_text_".$id_unique.$modes."$num_html', $tabTitle )
        ->set_classes( 'text_F".$class_F."' )";
    }


    $FOOTER_EXPERT = ") );";

    $FIELDS_EXPERT = implode($FIELDS_EXPERT );
    $secciones_carta_expert = [$TOP_EXPERT,$FIELDS_EXPERT,$FOOTER_EXPERT];
    $vista_carta_expert = implode($secciones_carta_expert);
    $escribir_expert = fopen( TAROKINA_ADMIN_PATH. 'cartas/carta_vista.php', "a+" );
    fwrite($escribir_expert, $vista_carta_expert . PHP_EOL);
    fclose($escribir_expert);
