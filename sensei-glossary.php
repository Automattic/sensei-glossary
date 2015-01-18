<?php
/*
 * Plugin Name: Sensei Glossary
 * Version: 1.0.0
 * Plugin URI: http://www.woothemes.com/
 * Description:  Add glossary popups to your Sensei lessons.
 * Author: WooThemes
 * Author URI: http://www.woothemes.com/
 * Requires at least: 3.8
 * Tested up to: 3.8.1
 *
 * @package WordPress
 * @author WooThemes
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'product_key', 'product_id' );

/**
 * Functions used by plugins
 */
if ( ! class_exists( 'WooThemes_Sensei_Dependencies' ) ) {
	require_once 'woo-includes/class-woothemes-sensei-dependencies.php';
}

/**
 * Sensei Detection
 */
if ( ! function_exists( 'is_sensei_active' ) ) {
  function is_sensei_active() {
    return WooThemes_Sensei_Dependencies::sensei_active_check();
  }
}

if( is_sensei_active() ) {

    /**
     * Load the main plugin class
     */
	require_once( 'includes/class-sensei-glossary.php' );

	/**
	 * Returns the main instance of Sensei_Glossary to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return object Sensei_Glossary
	 */
	function Sensei_Glossary() {
		return Sensei_Glossary::instance( __FILE__, '1.0.0' );
	}

	Sensei_Glossary();
}