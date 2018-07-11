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
define( 'UAMP_TMP_DIR', $uamp->plugin_dir_url().'/templates/');
define( 'UAMP_TD', $uamp->localization_init());  // Ultimate AMP Text Domain
define( 'UAMP_FILE', __FILE__ );
define( 'UAMP_DIR', dirname( __FILE__ ) );
define( 'STARTPOINT', 'amp');

define( 'UAMP_TMP_DIR', apply_filters( 'ultimate-amp/template/dir-name', 'uamp' ) );


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

		// Load AMP Template Files
		add_filter( 'amp_post_template_file',  array( $this, 'uamp_custom_template'), 10, 2 );

    }


    public function uamp_custom_template(){
		TemplateManager::uamp_include_template_file();
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

		require_once UAMP_DIR . '/templates/template-one/functions.php';

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

		add_action( 'init', array( $this, 'uamp_init' ), 0 ); // Must be 0 because widgets_init happens at init priority 1.
	}

	/*
	 * Init Ultimate AMP
	 */

	public function uamp_init(){

	    //Automatic AMP Actions/Hooks
		do_action('amp_init');
		add_post_type_support( 'post', AMP_QUERY_VAR );
		add_filter( 'request', 'amp_force_query_var_value' );
		add_action( 'wp', 'amp_maybe_add_actions', 100 );

		/**
		 * Triggers while Ultimate AMP Plugin is Active
		 */

		// Rewrite rules for Ultimate AMP
		add_rewrite_endpoint( Ultimate_AMP_Helper:: amp_get_slug(), EP_PERMALINK );
		add_filter('init',[$this, 'uamp_add_rewrite']);
		add_filter('init',[$this, 'append_index_rewrite_rule']);

		add_action( 'pre_get_posts', [$this, 'isolate_pre_get_posts_start'], 1 );
		add_action( 'pre_get_posts', [$this, 'isolate_pre_get_posts_end'], 100 );


		// Redirect the old url of amp page to the updated url.
		add_filter( 'old_slug_redirect_url', 'uamp_old_slug_to_new_slug' );



		// Automatic Redirect Mobile Users
		add_action( 'template_include',  [$this, 'uamp_include_template_files'], 9999 );
		add_action( 'template_redirect', [$this, 'uamp_auto_redirect_to_amp' ], 100 );
		add_action( 'template_redirect', [$this, 'uamp_page_status_check'], 100 );


        // Ultimate AMP Scripts/Styles
		add_action( 'ultimate-amp/template/enqueue-scripts', array( $this, 'enqueue_components_scripts' ) );





//		add_rewrite_endpoint( amp_get_slug(), EP_PERMALINK );
//		AMP_Theme_Support::init();
//		AMP_Post_Type_Support::add_post_type_support();
//		add_action( 'admin_init', 'AMP_Options_Manager::register_settings' );
//		add_action( 'wp_loaded', 'amp_post_meta_box' );
//		add_action( 'wp_loaded', 'amp_add_options_menu' );
//		add_action( 'parse_query', 'amp_correct_query_when_is_front_page' );


		if ( class_exists( 'Jetpack' ) && ! (defined( 'IS_WPCOM' ) && IS_WPCOM) ) {
			require_once( AMP__DIR__ . '/jetpack-helper.php' );
		}

		define('UAMP_QUERY_VAR', apply_filters( 'amp_query_var', $this->uamp_generate_endpoint() ) );


	}


	public function isolate_pre_get_posts_end( &$wp_query ) {

		global $better_amp_isolate_pre_get_posts;

		if ( !is_admin() && $wp_query->is_main_query()) {
			if ($better_amp_isolate_pre_get_posts) {
				$wp_query->query_vars = $better_amp_isolate_pre_get_posts;
				unset($better_amp_isolate_pre_get_posts);
			}
		}
	}


	public function isolate_pre_get_posts_start( $wp_query ) {

		global $better_amp_isolate_pre_get_posts;


		if ( ! is_admin() && $wp_query->is_main_query() ) {
			$better_amp_isolate_pre_get_posts = $wp_query->query_vars;
		}

	}



	public function uamp_include_template_files() {

		$include = $this->template_loader();

		if ( $include = apply_filters( 'ultimate-amp/template/include', $include ) ) {
//			return $include;
		} elseif ( current_user_can( 'switch_themes' ) ) {
			wp_die( __( 'Ultimate AMP Theme Was Not Found!', 'uamp' ) );
		} else {
			return UAMP_DIR . '/no-template.php';
		}


	}


	public function enqueue_components_scripts() { ?>
        <script custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"
                async=""></script>
        <script custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"
                async=""></script>
        <script custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"
                async=""></script>
        <script custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js" async=""></script>
        <script custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"
                async=""></script>

    <?php }




	public function better_amp_get_template_directory() {

		if ( $theme_info = $this->better_amp_get_template_info() ) {
			return $theme_info['TemplateRoot'];
		}

		return '';
	}


	function better_amp_get_template_info() {

		return wp_parse_args(
			apply_filters( 'ultimate-amp/template/template-one/active-template', array() ),
			array(
				'ScreenShot' => 'screenshot.png',
				'MaxWidth'   => 780,
				'view'       => 'general'
			)
		);
	}


	public function uamp_single_template() {
		$template = new Ultimate_Template_Loader();
		return $template->get_template_part( 'single' );
	}

	public function uamp_page_template() {
		$template = new Ultimate_Template_Loader();
		return $template->get_template_part( 'page' );
	}

	public function uamp_home_template() {
		$template = new Ultimate_Template_Loader();
		return $template->get_template_part( 'home' );
	}

	public function better_amp_index_template() {
		$template = new Ultimate_Template_Loader();
		return $template->get_template_part( 'index' );
	}

	public function uamp_404_template() {
		$template = new Ultimate_Template_Loader();
		return $template->get_template_part( '404' );
	}

	public function uamp_woocommerce_template() {
		$template = new Ultimate_Template_Loader();
		return $template->get_template_part( 'woocommerce.php' );
	}

	function template_loader() {
		$templates = new Ultimate_Template_Loader();

		if ( function_exists( 'is_embed' ) && is_embed() && $template = better_amp_embed_template() ) :
        elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() && is_page( wc_get_page_id( 'shop' ) ) && $template = $this->uamp_woocommerce_template() ) :
        elseif ( is_404() && $template = $this->uamp_404_template() ) :
//        elseif ( is_search() && $template = better_amp_search_template() ) :
        elseif ( is_home() && $template = $this->uamp_home_template() ) :
//        elseif ( is_post_type_archink rel="canonical" href=ve() && $template = better_amp_post_type_archive_template() ) :
//        elseif ( is_tax() && $template = better_amp_taxonomy_template() ) :
//        elseif ( is_attachment() && $template = better_amp_attachment_template() ) :
//			remove_filter( 'the_content', 'prepend_attachment' );
        elseif ( is_single() && $template = $this->uamp_single_template() ) :
        elseif ( is_page() && $template = $this->uamp_page_template() ) :
//        elseif ( is_singular() && $template = $this->uamp_single_template() ) :
//        elseif ( is_category() && $template = better_amp_category_template() ) :
//        elseif ( is_tag() && $template = better_amp_tag_template() ) :
//        elseif ( is_author() && $template = better_amp_author_template() ) :
//        elseif ( is_date() && $template = better_amp_date_template() ) :
//        elseif ( is_archive() && $template = better_amp_archive_template() ) :
//        elseif ( is_paged() && $template = better_amp_paged_template() ) :
		else :
			$template = $this->better_amp_index_template();
		endif;

		return $template;

	}


	public function append_index_rewrite_rule() {
		add_rewrite_rule( self::STARTPOINT . '/?$', "index.php?amp=index", 'top' );
	}



	public function uamp_add_rewrite(){
		$this->uamp_add_rewrite_startpoint( 'amp', EP_ALL );

		/**
		 * automattic amp compatibility
		 */
		$amp_qv = defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR : 'amp';
		add_rewrite_endpoint( $amp_qv, EP_PERMALINK );
    }


	public function add_startpint( $name, $places, $query_var = TRUE, $single_match = TRUE ) {

		global $wp;

		// For backward compatibility, if null has explicitly been passed as `$query_var`, assume `true`.
		if ( TRUE === $query_var || NULL === func_get_arg( 2 ) ) {
			$query_var = $name;
		}

		$this->startpints[] = array( $places, $name, $query_var, $single_match );

		if ( $query_var ) {
			$wp->add_query_var( $query_var );
		}

	}



	function uamp_add_rewrite_startpoint( $name, $places, $query_var = TRUE, $single_match = TRUE ) {
		$this->add_startpint( $name, $places, $query_var, $single_match );
	}


//
	function uamp_page_status_check() {
		global $wp;
//		$ampforwp_404_url   = add_query_arg( '', '', home_url( $wp->request ) );
//		$ampforwp_404_url = trailingslashit($ampforwp_404_url );
//		$ampforwp_404_url = dirname($ampforwp_404_url);
////		print_r($ampforwp_404_url);
//		wp_redirect( esc_url( $ampforwp_404_url )  , 301 );
//		exit();

		$redirection_location = '';
		$current_location     = '';
		$home_url             = '';
		$blog_page_id         = '';

		$current_location     = home_url( $wp->request);
		$home_url             = get_bloginfo('url');


		if ( is_archive() ) {
			$redirection_location = add_query_arg( '', '', home_url( $wp->request ) );
			$redirection_location = trailingslashit($redirection_location );
			$redirection_location = dirname($redirection_location);
			wp_safe_redirect( $redirection_location );
			exit;
		}

		if ( is_front_page() && $current_location == $home_url ) {
			return;
		}

		if ( is_archive() ) {
			return;
		}

		if ( is_front_page() ) {
			$redirection_location = $home_url;
		}

		wp_safe_redirect( $redirection_location );
		exit;

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
		$current_url = '';
		$check_endpoints = true;

		$current_archive_url 	= '';
		$amp_url				= '';
		$remove					= '';
		$query_arg_array 		= '';
		$page                   = '' ;

		if( is_singular()){
			$post_id 	 = get_the_ID();
			$request_url = get_permalink( get_queried_object_id() );
			$explode_url = explode('/', $request_url);
			$amp_append = 'amp';
			array_splice( $explode_url, 4, 0, $amp_append );
			$impode_url = implode('/', $explode_url);
			$amp_url = untrailingslashit($impode_url);
		}




//			$query_arg_array = $wp->query_vars;

//			if ((is_home() || is_archive()) && $wp->query_vars['paged'] >= '2') {

			if (is_home() || is_front_page() || is_archive()) {
				global $wp;
				$new_url = home_url( $wp->request );
				$explode_path = explode("/", $new_url);
				$inserted = [AMP_QUERY_VAR];
				array_splice($explode_path, 3, 0, $inserted);
				$impode_url = implode('/', $explode_path);
				$amp_url = untrailingslashit( $impode_url );

//				print_r($amp_url);
				

//				return $this->uamp_home_template();

//				$query_arg_array = $wp->query_vars;
//				print_r($query_arg_array);

//				if( array_key_exists( "page" , $query_arg_array  ) ) {
//					$page = $wp->query_vars['page'];
//					print_r($page);
//
//				}

//				if ( $page >= '2') {
//					$amp_url = trailingslashit( $amp_url  . '?page=' . $page);
//				} ?>

				<link rel="canonical" href="<?php echo user_trailingslashit( esc_url( apply_filters('uamp_modify_rel_url', $amp_url ) ) ) ?>">
                <link rel="amphtml" href="<?php echo user_trailingslashit( esc_url( apply_filters('uamp_modify_rel_url', $amp_url ) ) ) ?>" />
<?php
//				wp_redirect( esc_url( $amp_url )  , 301 );
//				exit();


//				}
			}

            if(is_404()){
//				$amp_url = 'https://jeweltheme.com';
                return;
            }

//			$post_id 	 = get_the_ID();
//			$request_url = get_permalink( get_queried_object_id() );
//			$explode_url = explode('/', $request_url);
//			$amp_append = 'amp';
//			array_splice( $explode_url, 4, 0, $amp_append );
//			$impode_url = implode('/', $explode_url);
//			$amp_url = untrailingslashit($impode_url);
//

//			$current_url = home_url( $wp->request );
//			$explode_path = explode("/", $current_url);
//			$amp_append = [AMP_QUERY_VAR];
//			array_splice($explode_path, -2, 0, $amp_append);
//			$impode_url = implode('/', $explode_path);
//			$amp_url = untrailingslashit($impode_url);;

//			print_r( $amp_append );
//			print_r( $amp_urls );
//			print_r( $amp_url );


//			$post_id 	 = get_the_ID();
//			$request_url = get_permalink( get_queried_object_id() );
//			$explode_url = explode('/', $request_url);
//			$amp_append = 'amp';
//			array_splice( $explode_url, 4, 0, $amp_append );
//			$impode_url = implode('/', $explode_url);
//			$amp_url = untrailingslashit($impode_url);


		return $amp_url;
	}


	/*
	 * Automatic Redirect to AMP version of Mobile Users
	 */
	public function uamp_auto_redirect_to_amp(){
//		if ( ! is_amp_endpoint() ) {
//			return;
//		}


//		$redirect_url = '';
		$redirect_url = $this->uamp_generate_amphtml();
		$request_url = $this->get_requested_url();


		print_r($redirect_url);
		print_r($request_url);

//		$is_amp_endpoint = $this->uamp_is_amp_endpoint();

//        print_r($this->uamp_generate_endpoint());
//		print_r( $this->uamp_home_template());
//		print_r( $this->uamp_is_amp_endpoint() );


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
