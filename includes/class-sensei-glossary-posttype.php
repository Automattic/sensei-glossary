<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Sensei_Glossary_Posttype {

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( ) {

        //register the glossary post type
        add_action( 'init', array( $this , 'register_post_type' ) );

        //add the glossary items to the sensei admin menu
        add_action( 'admin_menu', array( $this ,'add_glossary_to_menu' ) );

	} // End __construct()


    /**
     * Sensei_Glossary_Posttype::register_post_type
     *
     * @since 1.0.0
     * @return void
     */
    public function register_post_type(){

        $labels = array(
            'name'               => _x( 'glossary', 'post type general name', 'sensei-glossary' ),
            'singular_name'      => _x( 'glossary', 'post type singular name', 'sensei-glossary' ),
            'menu_name'          => _x( 'Glossary Items', 'admin menu', 'sensei-glossary' ),
            'name_admin_bar'     => _x( 'Glossary', 'add new on admin bar', 'sensei-glossary' ),
            'add_new'            => _x( 'Add New', 'book', 'sensei-glossary' ),
            'add_new_item'       => __( 'Add New Glossary Item', 'sensei-glossary' ),
            'new_item'           => __( 'New Glossary Item', 'sensei-glossary' ),
            'edit_item'          => __( 'Edit Glossary Item', 'sensei-glossary' ),
            'view_item'          => __( 'View Glossary Item', 'sensei-glossary' ),
            'all_items'          => __( 'All Glossary Items', 'sensei-glossary' ),
            'search_items'       => __( 'Search Glossary Items', 'sensei-glossary' ),
            'parent_item_colon'  => __( 'Parent Glossary Items:', 'sensei-glossary' ),
            'not_found'          => __( 'No glossary items found.', 'sensei-glossary' ),
            'not_found_in_trash' => __( 'No glossary items found in Trash.', 'sensei-glossary' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'glossary-item' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author' )
        );

        register_post_type( 'sensei_glossary', $args );

    }// end register_post_type

}// end load_plugin_classes