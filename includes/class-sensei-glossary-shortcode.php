<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * This class handles all the shortcode related functionality. It will respond to any shortcodes and
 * output the required UI.
 *
 * @since   1.0.0
 * @todo make sure paragraphs load with a p tag
 */

class Sensei_Glossary_Shortcode {

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( ) {

        add_shortcode( 'sensei_glossary', array( $this , 'show_glossary_item' ) );

	} // End __construct()

    /**
     * Sensei_Glossary_Shortcode::show_glossary_item
     *
     * @access public
     * @since 1.0.0
     * @param $atts
     * @param $content
     * @return void
     */
    public function show_glossary_item(  $atts, $content  ){

        // exit early if these conditions are not met
        if( ! isset( $atts['id'] ) || empty( $atts['id'] ) ){
            return;
        }

        // query WordPress for the glossary item
        $glossary_item = get_post( $atts['id']  );

        // exit if a no volid post is returned
        if( empty( $glossary_item ) ){
            return;
        }

        // setup the  need values before bulding the html output
        $glossary_item_id = $glossary_item->ID;
        $glossary_item_title = $glossary_item->post_title;

        // we use the title by default but if shortcode content is supplied we
        // use the content instead
        if( ! empty( $content ) ) {
            $glossary_item_title =  $content;
        }

        /**
         * Filter the glossary item  css classes. sensei_glossary_popup_css_classes
         *
         * @since 1.0.0
         *
         * @param array        $css_classes             Whether or not to parse the request. Default true.
         */
        $glossary_popup_classes = implode( ' ', apply_filters( 'sensei_glossary_popup_css_classes', array( 'sensei-glossary-content', 'glossary' ) ) );

        //convert raw html into a storage friendly text stream
        $glossary_content = htmlentities( $glossary_item->post_content, ENT_QUOTES);

        /**
         * Filter the glossary link css classes.
         *
         * @since 1.0.0
         *
         * @param array        $css_classes             Whether or not to parse the request. Default true.
         */
        $link_classes = implode( ' ', apply_filters( 'sensei_glossary_link_css_classes', array(  'sensei-glossary' ) ) );

        // create the html out to be returned
        $output = '';

        /**
         * Filter the glossary link rel attribute
         *
         * @since 1.0.0
         *
         * @param string  $glossary_link_rel_att     the rel applied to all glossary links
         */
        $rel = apply_filters( 'sensei-glossary-link-rel'  , 'sensei-glossary' );

        $output .= '<a class="' . $link_classes . '" title="' . $glossary_item_title . '" href="#" rel="'. $rel .'" '
                . ' data-glossary-id="' . $glossary_item_id . '" data-glossary-popup-classes="' . $glossary_popup_classes
                . '" data-glossary-content="' . $glossary_content
                . '" >' . $glossary_item_title .'</a>';

        /**
         * Filter the glossary hidden item html content
         *
         * @since 1.0.0
         *
         * @param array     $hidden_content             Whether or not to parse the request. Default true.
         */
        $output .= apply_filters( 'sensei_glossary_item_html_content',  $glossary_html_content );

        return $output;

    } //end show_glossary_item

}// end Sensei_Glossary_Shortcode