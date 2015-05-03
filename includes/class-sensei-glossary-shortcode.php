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
        add_filter( 'wp_kses_allowed_html', array( $this, 'add_data_kses'  ) );

	} // End __construct()

    /**
     * Filter the pre_kses so that the glossary popup data will always be shown on the lesson / course pages
     *
     * @param $allowed_html_tags
     * @return $allowed_html_tags
     */
    public function add_data_kses( $allowed_html_tags ){

        $allowed_html_tags['a']['data-glossary-id'] = true;
        $allowed_html_tags['a']['data-glossary-title'] = true;
        $allowed_html_tags['a']['data-glossary-content'] = true;
        $allowed_html_tags['a']['data-glossary-popup-classes'] = true;

        return $allowed_html_tags;

    }// end add_data_kses

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
        global $post;
        $glossary_item = get_post( $atts['id']  );
        $post = $glossary_item;
        setup_postdata( $post );

        // exit if a no volid post is returned
        if( empty( $glossary_item ) ){
            return;
        }

        // setup the  need values before bulding the html output
        $glossary_item_id = $glossary_item->ID;
        $glossary_item_title = get_the_title();

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
        $content = apply_filters( 'the_content', get_the_content() );
        $glossary_content =  htmlentities( str_replace( ']]>', ']]&gt;', $content ) , ENT_QUOTES )  ;

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


        /**
         * Filter the glossary hidden item html content
         *
         * @since 1.0.0
         *
         * @param array     $hidden_content             Whether or not to parse the request. Default true.
         */
        $output .= apply_filters( 'sensei_glossary_item_html_content',  $glossary_content );


        $output .= '<a class="' . $link_classes

            . '" title="' . substr( strip_tags( $content ) , 0, 50) . '...'
                . '" data-glossary-id="' . $glossary_item_id
                . '" data-glossary-popup-classes="' . $glossary_popup_classes
                . '" data-glossary-content="' . $glossary_content
                . '" data-glossary-title="' . $glossary_item_title
                . '" href="#" rel="'. $rel .'" '
                . '" >' . trim( $glossary_item_title ) .'</a>';



        // set the post data back to the original state
        wp_reset_postdata();

        return $output;

    } //end show_glossary_item

}// end Sensei_Glossary_Shortcode