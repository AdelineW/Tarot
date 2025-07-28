<?php if ( ! defined( 'WPINC' ) ) {die;}
use Carbon_Fields\Container;use Carbon_Fields\Field;

    Container::make( 'post_meta', '<span  class="t_mode"><span class="spre">Tarot</span></span>Marseille' )
        ->where( 'post_term', '=', array(
            'field' => 'id',
            'value' => 6,
            'taxonomy' => 'tarokkina_pro-cat',
        ) )
        ->add_fields(array(
            Field::make( 'html', 'tkta_texthtml_0_' )
            ->set_html( '
            <div class="cab_carta">
                    <div class="cab_cartaA">  
                        <div class="subT">
                            <div class="textTopMce">'.sprintf( esc_html__( 'Spread %s', 'tarokina-pro' ), '<span>3</span>' ).'</div>
                        </div>
                        <div class="spreadImg" ><img width="200px" src="https://tarot.reactive-com.com/wp-content/plugins/tarokina-pro/img/spreads/3cards.svg"></div>
                    </div>
                    <div class="cab_cartaB">
                        <div>
                        <table>
                            <tr>
                            <td class="td1Box">'.esc_html__( 'Tarot', 'tarokina-pro' ).':</td> <td class="td2Box mode">Marseille</td>
                            </tr>
                            <tr>
                            <td class="td1Box">'.esc_html__( 'Card', 'tarokina-pro' ).':</td> <td class="tdCard">'.$title.'</td>
                            </tr>
                            <tr>
                            <td class="td1Box">'.esc_html__( 'Mode', 'tarokina-pro' ).':</td> <td class="td2Box td_mode">Base inversée</td>
                            </tr>
                            <tr>
                            <td class="td1Box">'.esc_html__( 'Deck', 'tarokina-pro' ).':</td> <td class="td2Box decknameLi"></td>
                            </tr>
                            <tr>
                            <td class="svgTex td1Box">'.esc_html__( 'Texts', 'tarokina-pro' ).':</td> <td class="td2Box">2</td>
                            </tr>
                        </table>
                        </div>
                    </div>
            </div>  
            <div class="bloqTableLegend">
            <div class="text1">
                <div class="BigNum">A</div>
                <img width="83px" src="">
            </div>
            <div  class="table">
                <div class="table-content">
                <span class="referen">'.esc_html__( 'References', 'tarokina-pro' ).':</span>
                 <div class="table-row">
        <div class="table-data">
          <span class="numTitle">1</span>&nbsp;Présent
        </div>
        </div><div class="table-row">
        <div class="table-data">
          <span class="numTitle">2</span>&nbsp;Passé
        </div>
        </div><div class="table-row">
        <div class="table-data">
          <span class="numTitle">3</span>&nbsp;Futur
        </div>
        </div>
                </div>
            </div>
            </div>
            <div class="btnInfo">
            <a id="hide695419929686fd80ca0c7b" href="#hide695419929686fd80ca0c7b" class="hide">'.esc_html__('Info','tarokina-pro').'&nbsp;<span class="dashicons dashicons-arrow-right"></span></a>
            <a id="show695419929686fd80ca0c7b" href="#show695419929686fd80ca0c7b" class="show">'.esc_html__('Info','tarokina-pro').'&nbsp;<span class="dashicons dashicons-arrow-down"></span></a>
            <div class="details">
            <ul class="descriptionText">
            <li>'.esc_html__('Basic Mode: A single text is used for all references in each card. Example: Tarot with 3 spreads: present, past and future. Same text for present, past and future. You will opt for a tarot less precise in the answers but simpler to create. The final quality will depend on the skill you have in creating the texts.','tarokina-pro').'</li>
            <li class="text000">'.esc_html__('Reversed Basic Mode: 2 texts are used for all references on each card (A or B), if the card is reversed, the alternative text B will be used. Example: Tarot with 3 spreads: present, past and future. 1 text (A or B) for present, past and future. The final quality will depend on the skill you have in elaborating the 2 texts.','tarokina-pro').'</li>
            <li>'.esc_html__('Expert Mode: Multiple texts are used per card (1 text per reference in each card). You will get a very precise tarot in the answers. There are many texts but the final result will be very accurate, since each reference will have its own text.','tarokina-pro').'</li>
            <li>'.esc_html__('Reversed expert Mode: Multiple texts are used per card (2 texts per reference on each card). If the card is reversed, the alternative text B will be used on each reference. There are twice as many texts as in Expert mode.','tarokina-pro').'</li>
            </ul>
            </div> 
        </div>
        ' )->set_classes( 'text_F0' ),
        Field::make( 'radio', 'tkta_yesno_695419929686fd80ca0c7bexpert0', '' )->add_options( array(
            'Oui',
            'Non',
        ))->set_classes('yesno _3cards'),
        Field::make( 'rich_text', 'tkta_text_695419929686fd80ca0c7bexpert0', ''.$title.' - Marseille' )
        ->set_classes( 'text_F0' ),
            Field::make( 'html', 'tkta_texthtml_1_' )
            ->set_html( '
            <div class="bloq_BoxSecun"> 
            <div class="boxSecun">
            Annulé
                <div  class="spreadImg"><img width="100px" src="https://tarot.reactive-com.com/wp-content/plugins/tarokina-pro/img/spreads/3cards.svg"></div>
            </div>      
            </div>
            <div class="bloqTableLegend">
            <div class="text1">
                <div class="BigNum">B</div>
                <img style="-webkit-transform: rotate(-180deg);-ms-transform: rotate(-180deg);transform: rotate(-180deg)" width="83px" src="">
            </div>
            <div  class="table">
                <div class="table-content">
                <span class="referen">'.esc_html__( 'References', 'tarokina-pro' ).':</span>
                 <div class="table-row">
        <div class="table-data">
          <span class="numTitle">1</span>&nbsp;Présent
        </div>
        </div><div class="table-row">
        <div class="table-data">
          <span class="numTitle">2</span>&nbsp;Passé
        </div>
        </div><div class="table-row">
        <div class="table-data">
          <span class="numTitle">3</span>&nbsp;Futur
        </div>
        </div>
                </div>
            </div>
            </div>
        ' )->set_classes( 'text_F1' ),
        Field::make( 'radio', 'tkta_yesno_695419929686fd80ca0c7binve_expert0', '' )->add_options( array(
            'Oui',
            'Non',
        ))->set_classes('yesno _3cards text_F1'),
        Field::make( 'rich_text', 'tkta_text_695419929686fd80ca0c7binve_expert0', ''.$title.' - Marseille' )
        ->set_classes( 'text_F1' )
        ) );
