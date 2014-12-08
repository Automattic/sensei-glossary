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

        //register category taxonomy
        add_action( 'init', array( $this , 'register_glossary_category_taxonomy' ) );

        //add the glossary items to the sensei admin menu
        add_action( 'admin_menu', array( $this ,'add_glossary_to_admin_menu' ) );

	} // End __construct()


    /**
     * Sensei_Glossary_Posttype::register_post_type
     *
     * @since 1.0.0
     * @return void
     */
    public function register_post_type(){

        $labels = array(
            'name'               => _x( 'Glossary', 'post type general name', 'sensei-glossary' ),
            'singular_name'      => _x( 'Glossary', 'post type singular name', 'sensei-glossary' ),
            'menu_name'          => _x( 'Glossary Items', 'admin menu', 'sensei-glossary' ),
            'name_admin_bar'     => _x( 'Glossary', 'add new on admin bar', 'sensei-glossary' ),
            'add_new'            => _x( 'Add New', 'book', 'sensei-glossary' ),
            'add_new_item'       => __( 'Add Glossary Item', 'sensei-glossary' ),
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
            'taxonomies'         => array('categories'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author' )
        );

        register_post_type( 'sensei_glossary', $args );

    }// end register_post_type

    /**
     * Sensei_Glossary_Posttype::register_glossary_category_taxonomy
     *
     * @since 1.0.0
     * @return void
     */
    public function register_glossary_category_taxonomy() {

        // Add new taxonomy, make it hierarchical (like categories)
        $labels = array(
        'name'              => _x( 'Categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Glossary Categories' ),
        'all_items'         => __( 'All Glossary Categories' ),
        'parent_item'       => __( 'Parent Category' ),
        'parent_item_colon' => __( 'Parent Category:' ),
        'edit_item'         => __( 'Edit Category' ),
        'update_item'       => __( 'Update Category' ),
        'add_new_item'      => __( 'Add New Glossary Category' ),
        'new_item_name'     => __( 'New Category Name' ),
        'menu_name'         => __( 'Glossary Category' ),
        );

        $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'glossary-category' ),
        );

        register_taxonomy( 'sensei_glossary_category', array( 'sensei_glossary' ), $args );

    }// end register_glossary_category_taxonomy

    /**
     * Sensei_Glossary_Posttype::add_glossary_to_admin_menu
     *
     * Add the glossary menu (add new and view all) to the Sensei Lessons admin menu
     *
     * @since 1.0.0
     * @return void
     */
    public function add_glossary_to_admin_menu(){

        add_submenu_page( 'edit.php?post_type=lesson', 'Add Glossary Item', 'Add Glossary Item', 'edit_lessons', 'post-new.php?post_type=sensei_glossary');
        add_submenu_page( 'edit.php?post_type=lesson', 'View Glossary Items', 'View Glossary Items', 'edit_lessons', 'edit.php?post_type=sensei_glossary');
        add_submenu_page( 'edit.php?post_type=lesson', 'Glossary Categories', 'Glossary Categories', 'edit_lessons', 'edit-tags.php?taxonomy=sensei_glossary_category&post_type=sensei_glossary');

    }//end add_glossary_to_menu

}// end load_plugin_classes