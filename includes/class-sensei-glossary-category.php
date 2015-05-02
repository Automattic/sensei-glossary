<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Sensei_Glossary_Category
 *
 * This class controls the output of the glossary gallery page.
 *
 * This class is hooked into WP in class-sensei-glossary.php
 *
 * @since 1.1
 */
class Sensei_Glossary_Category {

    public static function wp_hooks(){

        // hook into the post query to order alphabetical
        add_action('pre_get_posts', array( 'Sensei_Glossary_Category', 'order_category_items' ) );

    }// wp_hooks

    /**
     * Hook into the post query and ensure that it is ordered
     * alphabetically.
     *
     * @since 1.1
     * @param array $args
     * @return array $args
     */
    public static function order_category_items( $query ){
        if( ! is_tax('sensei_glossary_category') ){
            return $query;
        }

        //change order to alphabetical
        $query->set('order', 'ASC');
        $query->set('orderby', 'title');

        return $query;

    }// order_category_items

}// end class