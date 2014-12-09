<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * This class handles all the shortcode related functionality. It will respond to any shortcodes and
 * output the required UI.
 *
 * @since   1.0.0
 *
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
        $glossary_item_content = $glossary_item->post_content;
        $glossary_item_title = $glossary_item->post_title;

        // alternatively use the content specified
        if( ! empty( $content ) ) {
            $glossary_item_title =  $content;
        }

        /**
         * Filter the glossary item  css classes.
         *
         * @since 1.0.0
         *
         * @param array        $css_classes             Whether or not to parse the request. Default true.
         */
        $item_classes = implode( ' ', apply_filters( 'sensei_glossary_item_css_classes', array( 'sensei-glossary-content', 'glossary' ) ) );

        //build the hidden content
        $glossary_html_content_attributes =  'id="' . $glossary_item_id  . '" class="' . $item_classes . '" ';
        $glossary_html_content = '<span ' . $glossary_html_content_attributes . ' ><span>' . $glossary_item_content  . '</span></span>';

        /**
         * Filter the glossary link css classes.
         *
         * @since 1.0.0
         *
         * @param array        $css_classes             Whether or not to parse the request. Default true.
         */
        $link_classes = implode( ' ', apply_filters( 'sensei_glossary_link_css_classes', array( 'thickbox', 'sensei-glossary' ) ) );

        // create the html out to be returned
        $output = '';

        /**
         * Filter the glossary link href for all elements
         *
         * @since 1.0.0
         *
         * @param string  $glossary_link_href     the href applied to all glossary links
         */
        $glossary_link_href = apply_filters( 'sensei_glossary_link_href', '#TB_inline' );

        /**
         * Filter the glossary link href for a specific glossary ID
         *
         * @since 1.0.0
         *
         * @param string  $glossary_link_href     the href applied to a specif glossary ID
         */
        $glossary_link_href = apply_filters( 'sensei_glossary_link_href_'.$glossary_item_id,  $glossary_link_href . '?inlineId=' . $glossary_item_id  );

        $output .= '<a class="'. $link_classes .'" title="' . $glossary_item_title . '"href="'. $glossary_link_href .'" data-glossary-id="' . $glossary_item_id . '" >' . $glossary_item_title .'</a>';

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