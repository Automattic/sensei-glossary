<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Sensei_Extension_Template {
	private $dir;
	private $file;
	private $assets_dir;
	private $assets_url;
	private $version;
	private $token;
	private $script_suffix;

	public function __construct( $file, $version = '1.0.0' ) {
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );
		$this->version = $version;
		$this->token = 'sensei_extension_template';
		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Register activation hook to run functions on plugin activation
		register_activation_hook( $this->file, array( $this, 'activation' ) );

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	}

	/**
	 * Runs on plugin activation
	 * @return void
	 */
	public function activation() {
		// Activation functions
	}

	/**
	 * Load frontend CSS
	 * @return void
	 */
	public function enqueue_styles() {
		global $woothemes_sensei;

		wp_register_style( $this->token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array( $woothemes_sensei->token . '-frontend' ), $this->version );
		wp_enqueue_style( $this->token . '-frontend' );
	}

	/**
	 * Load frontend Javascript
	 * @return void
	 */
	public function enqueue_scripts() {
		global $woothemes_sensei;

		wp_register_script( $this->token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->token . '-frontend' );
	}

	/**
	 * Load admin CSS
	 * @return void
	 */
	public function admin_enqueue_styles( $hook ) {
		wp_register_style( $this->token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->version );
		wp_enqueue_style( $this->token . '-admin' );
	}

	/**
	 * Load admin Javascript
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook ) {
		wp_register_script( $this->token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->token . '-admin' );
	}

	/**
	 * Load plugin localisation
	 * @return void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'sensei-extension-template' , false , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	/**
	 * Load plugin textdomain
	 * @return void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'sensei-extension-template';

	    $locale = apply_filters( 'plugin_locale' , get_locale() , $domain );

	    load_textdomain( $domain , WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain , FALSE , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

}