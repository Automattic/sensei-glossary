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

        // use the template inside this extension
        add_action('template_redirect', array( 'Sensei_Glossary_Category' ,'set_extension_template') );

        // add the main content output
        add_action( 'sensei_archive_main_content', array( 'Sensei_Glossary_Category', 'category_content' ) );

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
        $query->set( 'posts_per_page', 5000 );

        return $query;

    }// order_category_items

    /**
     *
     * Output the content on the category taxonomy page
     *
     * This should apply to all taxonomy terms.
     *
     * @since 1.1
     */
    public static function category_content(){
        global $wp_query;

        if ( $wp_query->have_posts() ) { $count = 0;
            ?>
            <div class="fix"></div>
            <?php
            // the heading char variable
            $block_heading_character = '';

            while ( $wp_query->have_posts() ) { the_post(); $count++;

                // if the fir letter differs print out the new heading
                if( $block_heading_character != substr( trim(get_the_title()), 0, 1 ) ){
                    $block_heading_character = substr( trim(get_the_title()), 0, 1 );
                    ?>
                    <div class="header-char title">
                        <h3>
                            <?php echo $block_heading_character  ;?>
                        </h3>
                    </div>

                    <?php
                }

                $glossary_item_title_link = '<a href="'. get_the_permalink()  .'" class="glossary-item" > '. get_the_title() .'</a>';

                ?>
                <p>
                    <?php echo $glossary_item_title_link; ?>
                </p>
            <?php

            } // end while

        } else {
            ?>
                <p><?php echo apply_filters( 'woo_noposts_message', __( 'Sorry, no posts matched your criteria.', 'woothemes-sensei' ) ); ?></p>
            <?php
        }

        return false;

    }// end category content

    /**
     * Setup the template for the archives taxonomy page to use the
     * template included with this extension
     *
     * @since 1.1
     */
    public static function set_extension_template() {

        if( ! is_tax('sensei_glossary_category') ){
         return;
        }

        $archive_template =  dirname( plugin_dir_path(__FILE__) ) . '/templates/taxonomy-sensei_glossary_category.php' ;

        if(file_exists( $archive_template )) {
            include( $archive_template );
            exit;
        }
    }// end set ext template

}// end class