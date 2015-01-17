<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Sensei_Glossary_Editor_MCE {

    /**
     * Constructor function.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function __construct ( ) {

        //hook in and add our button
        add_filter('mce_buttons', array( $this, 'register_mce_button' ) );

        // hook in the js file that handles the plugin details
        add_filter('mce_external_plugins', array( $this, 'register_mce_javascript') );

        //localize the plugin assets url
        add_action( 'admin_init', array( $this , 'localize_asset_url' ) );

    }

    /**
     * Sensei_Glossary_Editor_MCE::register_mce_button
     *
     * Add this plugins button to the WordPress editor
     *
     * @param array $buttons
     * @return array  $buttons
     */
    public function register_mce_button( $buttons ) {

        // Check if user have permission
        if ( !current_user_can( 'edit_lessons' ) ) {
            return;
        }

        array_push( $buttons, 'sensei_glossary' );
        return $buttons;

    } // end register_mce_button

    /**
     * Sensei_Glossary_Editor_MCE::register_mce_javascript
     *
     * @param array $plugin_array
     * @return array  $plugin_array
     */
    function register_mce_javascript( $plugin_array ) {

        // Check if user have permission
        if ( !current_user_can( 'edit_lessons' ) ) {
            return;
        }

        $plugin_array['sensei_glossary'] = plugins_url( '../assets/js/tinymce-plugin.js', __FILE__ );
        return $plugin_array;

    }

    /**
     * Sensei_Glossary_Editor_MCE::localize_asset_url
     *
     * Localizes the plugin url so that the mce plugin can get the icon URL
     *
     * @return void
     */
    public function localize_asset_url(){

        $data = array( 'dialog_url' => plugins_url( '../assets/mce_dialog.html', __FILE__ ) );

        wp_localize_script(  'wp-util', 'sensei_glossary', $data );
    }// end localize_asset_url
}// end Sensei_Glossary_Editor_MCE class
