<?php
/* Plugin Name: Ultimate AMP
 * Description: Ultimate Accelerated Mobile Pages WordPress Plugin
 * Version: 1.0.0
 * Author: Liton Arefin
 * Author URI: https://jeweltheme.com/shop/ultimate-amp/
 * Tags: amp, wp amp, google amp, amp project
 * Text Domain: uamp
 * Domain Path: /languages/
 * License: GPLv2 or later
 */

// No Direct Access Sire !!!!
if ( ! defined( 'ABSPATH' ) ) exit;


/*
 * Ultimate AMP Plugin Constants
 */

$uamp = new Ultimate_AMP();
define( 'UAMP_VERSION', $uamp->version);
define( 'UAMP_PLUGIN_URL', $uamp->plugin_url());
define( 'UAMP_PLUGIN_DIR', $uamp->plugin_path() );
define( 'UAMP_PLUGIN_DIR_URL', $uamp->plugin_dir_url());
define( 'UAMP_IMAGE_DIR', $uamp->plugin_dir_url().'/images');
define( 'UAMP_TD', $uamp->localization_init());  // Ultimate AMP Text Domain
define( 'UAMP_FILE', __FILE__ );
define( 'UAMP_DIR', dirname( __FILE__ ) );
define( 'STARTPOINT', 'amp');


class Ultimate_AMP{

    /*
     * Ultimate AMP Version Number
     */
	public $version = '1.0.0';

	/*
	 * Plugin URL
	 */
    public $plugin_url;

    /*
     * Plugin Path
     */
    public $plugin_path;

    /*
     * Plugin dir URL
     */
    public $plugin_dir_url;

    /*
     * Endpoint of AMP URL
     */
    const STARTPOINT = "amp";


    /*
     * Initialize Ultimate_AMP Class
     * If class not found then create a new instance of Ultimate_AMP
     */
    public static function init(){
		static $instance = false;

		if(!$instance ){
			$instance = new Ultimate_AMP();

			$instance->uamp_plugin_init();
		}

		return $instance;

	}

	/*
	 * Plugin Initialization
	 */
    public function uamp_plugin_init(){

		$this->include_files();

    	//Localization Initialize
		add_action('init', array( $this, 'localization_init'));

    	//Load Frontend Scripts and Styles
        add_action('init', array( $this, 'uamp_enqueue_scripts'));

		//Register Ultimate AMP Menus
		add_action('init', array( $this, 'uamp_register_menus'));

        //After Theme Setup
		add_action( 'after_setup_theme', array( $this, 'uamp_after_theme_setup'), 5 );

		// Activation Hook
		register_activation_hook( __FILE__, array( $this, 'uamp_activate' ));
		register_deactivation_hook( __FILE__, array( $this, 'uamp_deactivate' ));

		// Default AMP Plugin
		add_action('plugins_loaded', array( $this, 'uamp_deafult_amp_plugin'), 10);

    }

	/*
	 * Include Required Files
	 */
	public function include_files(){
		require_once UAMP_DIR . '/inc/ultimate-amp-autoload.php';
		UltimateAmpAutoload::register();

		if ( ! class_exists( 'ReduxFramework' ) ) {
			require_once dirname( __FILE__ ) . '/inc/admin/redux-core/framework.php';
		}


		if ( is_admin() ) {
			// Register all the main options
			require_once dirname( __FILE__ ).'/inc/admin/AdminOptions.php';
		}

	}

	/*
	 * Register Ultimate AMP Menus
	 */
	public function uamp_register_menus(){
		register_nav_menus(
			array(
				'uamp-main-menu' => __( 'Ultimate AMP Main Menu', UAMP_TD ),
			)
		);

		register_nav_menus(
			array(
				'uamp-footer-menu' => __( 'Ultimate AMP Footer Menu', UAMP_TD ),
			)
		);
	}

    /*
     * Setup Localization
     */
    public function localization_init(){
		load_plugin_textdomain( 'uamp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

    /*
     * Scripts and Styles
     */
    public function uamp_enqueue_scripts(){

    }


	/*
	 * Plugin URL
	 */
	public function plugin_url(){
		if( $this->plugin_url ) return $this->plugin_url;

		return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
	}


	/*
	 * Plugin Directory
	 */
	public function plugin_path(){
		if( $this->plugin_path ) return $this->plugin_path;

		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/*
	 * Plugin Directory URL
	 */
	public function plugin_dir_url(){
		if( $this->plugin_dir_url ) return $this->plugin_dir_url;

		return $this->plugin_dir_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/*
	* Register Activation Hook
	*/
	public function uamp_activate(){
		$this->uamp_after_theme_setup();
		if ( ! did_action( 'uamp_init' ) ) {
			$this->uamp_init();
		}
		flush_rewrite_rules();
	}

	/*
	 * Register DeActivation Hook
	 */
	public function uamp_deactivate(){
		global $wp_rewrite;
		// We need to manually remove the amp endpoint
		global $wp_rewrite;
		foreach ( $wp_rewrite->endpoints as $index => $endpoint ) {
			if ( Ultimate_AMP_Helper:: amp_get_slug() === $endpoint[1] ) {
				unset( $wp_rewrite->endpoints[ $index ] );
				break;
			}
		}

		flush_rewrite_rules();
	}

	/*
	 * After Theme Setup
	 */
	public function uamp_after_theme_setup() {
		Ultimate_AMP_Helper:: amp_get_slug(); // Ensure AMP_QUERY_VAR is set.

		if ( false === apply_filters( 'amp_is_enabled', true ) ) {
			return;
		}

		define( 'AMP_QUERY_VAR', apply_filters( 'amp_query_var', 'amp' ) );

		add_action( 'init', array( $this, 'uamp_init' ), 0 ); // Must be 0 because widgets_init happens at init priority 1.
	}


	/*
	 * Init Ultimate AMP
	 */

	public function uamp_init(){
		/**
		 * Triggers while Ultimate AMP Plugin is Active
		 */
		do_action('amp_init');

		add_rewrite_endpoint( Ultimate_AMP_Helper:: amp_get_slug(), EP_PERMALINK );

		add_post_type_support( 'post', AMP_QUERY_VAR );

		add_filter( 'request', 'amp_force_query_var_value' );

		add_filter( 'request', 'amp_force_query_var_value' );
		add_action( 'wp', 'amp_maybe_add_actions' );

		// Automatic Redirect Mobile Users
		add_action( 'template_redirect', array( $this, 'uamp_auto_redirect_to_amp' ), 100 );

		// Redirect the old url of amp page to the updated url.
		add_filter( 'old_slug_redirect_url', 'uamp_old_slug_to_new_slug' );

		if ( class_exists( 'Jetpack' ) && ! (defined( 'IS_WPCOM' ) && IS_WPCOM) ) {
			require_once( AMP__DIR__ . '/jetpack-helper.php' );
		}

		define('UAMP_QUERY_VAR', apply_filters( 'amp_query_var', $this->uamp_generate_endpoint() ) );

	}


	/*
	 * Generate Endpoints
	 */
	public function uamp_generate_endpoint(){
		$uamp_slug = STARTPOINT;

		return $uamp_slug;
	}

	/*
	 * Get Request URL
	 */
	public static function get_requested_url() {

		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$requested_url = is_ssl() ? 'https://' : 'http://';
			$requested_url .= $_SERVER['HTTP_HOST'];
			$requested_url .= $_SERVER['REQUEST_URI'];

			return $requested_url;
		}

		return '';
	}

	/*
	 * Generate AMP to HTML
	 */
	public function uamp_generate_amphtml(){
		global $wp, $post;
		$post_id = '';
		$chck_endpoints = true;
		if( is_singular()){
			$post_id 	 = get_the_ID();
			$request_url = get_permalink( get_queried_object_id() );
			$explode_url = explode('/', $request_url);
			$amp_append = 'amp';
			array_splice( $explode_url, 4, 0, $amp_append );
			$impode_url = implode('/', $explode_url);
			$amp_url = untrailingslashit($impode_url);
		}

		return $amp_url;
	}


	/*
	 * Automatic Redirect to AMP version of Mobile Users
	 */
	public function uamp_auto_redirect_to_amp(){
		$redirect_url = '';
		$redirect_url = $this->uamp_generate_amphtml();
		$request_url = $this->get_requested_url();

//		if($this->uamp_is_amp_endpoint()){
//			return;
//		}

		if ( wp_is_mobile() ) {
			if($redirect_url){
				wp_redirect( $redirect_url );
				exit();
			}
		}
		return;
	}


	/*
	 * Check URL Endpoints for AMP
	 */
	public function uamp_is_amp_endpoint() {
		if ( $this->uamp_is_non_amp() && ! is_admin()) {
			return $this->uamp_is_non_amp();
		}
		else {
			return false !== get_query_var( 'amp', false );
		}
	}

	public function uamp_is_non_amp(){
		$not_amp = true;
		return $not_amp;
	}

	/*
	 * Load Default AMP Plugin
	 */
	public function uamp_deafult_amp_plugin(){
		require_once UAMP_DIR .'/inc/amp/amp.php';

		define( 'AMP__FILE__', __FILE__ );
		if ( ! defined('AMP__DIR__') ) {
			define( 'AMP__DIR__', plugin_dir_path(__FILE__) . 'inc/amp/' );
		}
		define( 'AMP__VERSION', '0.7.1' );

		require_once( AMP__DIR__ . '/back-compat/back-compat.php' );
		require_once( AMP__DIR__ . '/includes/amp-helper-functions.php' );
		require_once( AMP__DIR__ . '/includes/admin/functions.php' );
		require_once( AMP__DIR__ . '/includes/settings/class-amp-customizer-settings.php' );
		require_once( AMP__DIR__ . '/includes/settings/class-amp-customizer-design-settings.php' );
	}

}





/*
 * Initialize Ultimate_AMP Plugin
 */
function ultimate_amp(){
	return Ultimate_AMP::init();
}

// Let's kick it
ultimate_amp();