<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Sensei_Glossary {

	/**
	 * The single instance of Sensei_Glossary.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file, $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'sensei-glossary';

		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

        // call all plugin classes and instantiate
        $this->load_plugin_classes();

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Handle localisation
		$this->load_plugin_textdomain ();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	} // End __construct()

    /**
     * Load all the plugin classes
     *
     * @since 1.0.0
     * @return void
     */
    public function load_plugin_classes(){

        // Post Type
        require_once( 'class-sensei-glossary-posttype.php' );
        $this->posttype = new Sensei_Glossary_Posttype();

        // load the shortcode-output
        require_once( 'class-sensei-glossary-shortcode.php' );
        $this->posttype = new Sensei_Glossary_Shortcode();

        // MCE editor plugin
        require_once( 'class-sensei-glossary-editor-mce.php' );
        $this->posttype = new Sensei_Glossary_Editor_MCE();

    } // end load_plugin_classes

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		global $woothemes_sensei;

		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array( $woothemes_sensei->token . '-frontend' ), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles()

	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_scripts () {
		global $woothemes_sensei;

		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );
	} // End enqueue_scripts()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );

        // select2
        wp_register_script( 'select2', esc_url( $this->assets_url ) . 'js/select2.min.js', array( 'jquery' ), $this->_version );
        wp_enqueue_script( 'select2' );
	} // End admin_enqueue_scripts()

	/**
	 * Load plugin localisation.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'sensei-glossary' , false , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation()

	/**
	 * Load plugin textdomain.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'sensei-glossary';

	    $locale = apply_filters( 'plugin_locale' , get_locale() , $domain );

	    load_textdomain( $domain , WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain , FALSE , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain

	/**
	 * Main Sensei_Glossary Instance
	 *
	 * Ensures only one instance of Sensei_Glossary is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Sensei_Glossary()
	 * @return Main Sensei_Glossary instance
	 */
	public static function instance ( $file, $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self( $file, $version );
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	}
}