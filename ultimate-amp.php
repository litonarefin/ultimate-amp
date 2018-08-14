<?php
/* Plugin Name: Ultimate AMP
 * Description: Ultimate Accelerated Mobile Pages WordPress Plugin
 * Version: 1.0.211
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
define( 'UAMP', $uamp->plugin_name);
define( 'UAMP_VERSION', $uamp->version);
define( 'UAMP_PLUGIN_URL', $uamp->plugin_url());
define( 'UAMP_PLUGIN_DIR', $uamp->plugin_path() );
define( 'UAMP_PLUGIN_DIR_URL', $uamp->plugin_dir_url());
define( 'UAMP_IMAGE_DIR', $uamp->plugin_dir_url().'/images');
define( 'UAMP_TMP_DIR', $uamp->plugin_dir_url().'/templates/');
define( 'UAMP_TD', $uamp->localization_init());  // Ultimate AMP Text Domain
define( 'UAMP_FILE', __FILE__ );
define( 'UAMP_DIR', dirname( __FILE__ ) );
define( 'AMP_QUERY', 'amp');


class Ultimate_AMP {


	/*
	 * Ultimate AMP Version Number
	 */
	public $version = '1.0.21';


	/*
	 * Ultimate AMP Constants
	 */

    const AMP_QUERY    = 'amp';


    /*
     * Ultimate AMP name
     */
    public $plugin_name = 'Ultimate AMP';


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

    public $template;


    /*
     * Initialize Ultimate_AMP Class
     * If class not found then create a new instance of Ultimate_AMP
     */
    public static function init() {
        static $instance = false;

        if (!$instance) {
            $instance = new Ultimate_AMP();

            $instance->uamp_plugin_init();
        }

        return $instance;

    }
    

    /*
     * Plugin Initialization
     */
    public function uamp_plugin_init() {

	    //Ultimate AMP Actions/Hooks
	    do_action('uamp_init');


        //Localization Initialize
        add_action('plugins_loaded', [$this, 'localization_init']);
        add_action('plugins_loaded', [$this, 'uamp_bundle_core_files'],8);

        // Welcome Page
	    add_action('admin_menu', [$this, 'uamp_welcome_screen_page'], 9);
	    add_action('activated_plugin', [$this, 'uamp_welcome_redirect']);


	    // Image Size
        $uamp_width         = get_option( 'uamp_content_width' );
        $uamp_content_width = $uamp_width ? $uamp_width : '600';
        add_image_size( 'uamp-image', $uamp_content_width );
        add_image_size( 'uamp-image-small', ceil( $uamp_content_width / 3 ) );


        add_action( 'wp_head', [ $this, 'uamp_add_rel_info'] );

        $this->uamp_check_debug_mode();

        add_action( 'init', [ $this, 'uamp_init' ], 99 );
        add_action( 'init', array( $this, 'uamp_register_menus'));
        add_action( 'uamp_init',[ $this, 'uamp_auto_add_amp_menu_link_insert'],9999);


        //Footer Menu Class
        add_filter( 'nav_menu_link_attributes', [$this, 'uamp_add_menu_link_class'], 10, 3 );
        add_filter( 'nav_menu_css_class', [$this, 'uamp_menu_link_list_classes'], 1, 3);

        // WP AMP Hooks
//	    add_filter( 'request', 'amp_force_query_var_value' );
//	    add_action( 'wp', 'amp_maybe_add_actions' );


	    if ( ! class_exists( 'ReduxFramework' ) ) {
            // Redux Framework
            require_once UAMP_DIR . '/inc/admin/redux-core/framework.php';
            //Ultimate AMP Options
            require_once UAMP_DIR . '/inc/admin/admin-options.php';
        }

        if ( file_exists( UAMP_DIR . '/inc/uamp-options.php' ) ) {
            require_once UAMP_DIR . '/inc/uamp-options.php';
        }


    }



    /*
     * Init Ultimate AMP
     */

    public function uamp_init() {

        $this->uamp_include_files();

        add_action( 'template_redirect', array ( $this, 'uamp_load_template' ) );

        add_rewrite_endpoint( $this->uamp_get_endpoint(), EP_ALL );
        add_action( 'pre_get_posts', array ( $this, 'search_filter' ) );
        add_filter( 'uamp_is_mobile_get_redirect_url', array ( $this, 'view_original_redirect' ) );

        /**
         * Activation Hook
         * Triggers while Ultimate AMP Plugin is Active
         */
        register_activation_hook(__FILE__, [$this, 'uamp_activate']);

	    /*
	     * Activation Hook - Deactivate Other Plugins those will
	     * conflict with Ultimate AMP Plugin
	     */
	    register_activation_hook( __FILE__, [$this, 'uamp_deactivate_ampbywp']);
	    register_activation_hook( __FILE__, [$this, 'uamp_deactivate_ampforwp']);
	    register_activation_hook( __FILE__, [$this, 'uamp_deactivate_better_amp']);
	    register_activation_hook( __FILE__, [$this, 'uamp_deactivate_wp_amp']);

	    /*
	     * Deactivation Hook
	     */
	    register_deactivation_hook(__FILE__, [$this, 'uamp_deactivate']);


        // Redirect the old url of amp page to the updated url.
        add_filter('old_slug_redirect_url', [$this, 'amp_redirect_old_slug_to_new_url']);


        if (class_exists('Jetpack') && !(defined('IS_WPCOM') && IS_WPCOM)) {
            require_once( UAMP_DIR . '/inc/jetpack-helper.php');
        }

        define('UAMP_QUERY_VAR', apply_filters('amp_query_var', $this->uamp_generate_endpoint()));

        if ( is_admin() ) {
            $this->uamp_admin_init();
        }



//        add_filter( 'do_parse_request', array ( $this, 'parse_request' ), 10, 3 );


	    // Default AMP Plugin

	    if ( false === apply_filters( 'amp_is_enabled', true ) ) {
		    return;
	    }
	    if( ! defined('AMP_QUERY_VAR')){
		    define( 'AMP_QUERY_VAR', apply_filters( 'amp_query_var', 'amp' ) );
	    }

	    if ( ! defined('AMP__DIR__') ) {
		    define( 'AMP__DIR__', UAMP_DIR . '/lib/vendor/amp/' );
	    }




    }

    public function parse_request( $is_parse, $wp, $extra_query_vars ) {
        if ( $this->is_amp() ) {
            $is_parse = false;
            $this->_parse_request( $wp, $extra_query_vars );
        }

        return $is_parse;
    }

    protected function _parse_request( $wp, $extra_query_vars ) {
        global $wp_rewrite;

        $wp->query_vars       = array ();
        $post_type_query_vars = array ();

        $amp_endpoint                    = $this->uamp_get_endpoint();
        $wp->query_vars[ $amp_endpoint ] = '';

        if ( is_array( $extra_query_vars ) ) {
            $wp->extra_query_vars = &$extra_query_vars;
        } elseif ( ! empty( $extra_query_vars ) ) {
            parse_str( $extra_query_vars, $wp->extra_query_vars );
        }
        // Process PATH_INFO, REQUEST_URI, and 404 for permalinks.

        // Fetch the rewrite rules.
        $rewrite = $wp_rewrite->wp_rewrite_rules();

        if ( ! empty( $rewrite ) ) {
            // If we match a rewrite rule, this will be cleared.
            $error             = '404';
            $wp->did_permalink = true;

            $pathinfo = isset( $_SERVER['PATH_INFO'] ) ? $_SERVER['PATH_INFO'] : '';
            list( $pathinfo ) = explode( '?', $pathinfo );
            $pathinfo = str_replace( "%", "%25", $pathinfo );

            list( $req_uri ) = explode( '?', $_SERVER['REQUEST_URI'] );
            $self            = $_SERVER['PHP_SELF'];
            $home_path       = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
            $home_path_regex = sprintf( '|^%s|i', preg_quote( $home_path, '|' ) );

            // Trim path info from the end and the leading home path from the
            // front. For path info requests, this leaves us with the requesting
            // filename, if any. For 404 requests, this leaves us with the
            // requested permalink.
            $req_uri  = str_replace( $pathinfo, '', $req_uri );
            $req_uri  = trim( $req_uri, '/' );
            $req_uri  = preg_replace( $home_path_regex, '', $req_uri );
            $req_uri  = trim( $req_uri, '/' );
            $pathinfo = trim( $pathinfo, '/' );
            $pathinfo = preg_replace( $home_path_regex, '', $pathinfo );
            $pathinfo = trim( $pathinfo, '/' );
            $self     = trim( $self, '/' );
            $self     = preg_replace( $home_path_regex, '', $self );
            $self     = trim( $self, '/' );

            // The requested permalink is in $pathinfo for path info requests and
            //  $req_uri for other requests.
            if ( ! empty( $pathinfo ) && ! preg_match( '|^.*' . $wp_rewrite->index . '$|', $pathinfo ) ) {
                $requested_path = $pathinfo;
            } else {
                // If the request uri is the index, blank it out so that we don't try to match it against a rule.
                if ( $req_uri == $wp_rewrite->index ) {
                    $req_uri = '';
                }
                $requested_path = $req_uri;
            }
            $requested_file = $req_uri;

            $wp->request = $requested_path;

            // Look for matches.
            $endpoint      = sprintf( '/\/%s(\/)?$/', $amp_endpoint );
            $request_match = ( $requested_path == $amp_endpoint ) ? $requested_path : preg_replace( $endpoint, '', $requested_path );

            if ( empty( $request_match ) ) {
                // An empty request could only match against ^$ regex
                if ( isset( $rewrite['$'] ) ) {
                    $wp->matched_rule = '$';
                    $query            = $rewrite['$'];
                    $matches          = array ( '' );
                }
            } else {
                foreach ( (array) $rewrite as $match => $query ) {
                    // If the requested file is the anchor of the match, prepend it to the path info.
                    if ( ! empty( $requested_file ) && strpos( $match, $requested_file ) === 0 && $requested_file != $requested_path ) {
                        $request_match = $requested_file . '/' . $requested_path;
                    }

                    if ( preg_match( "#^$match#", $request_match, $matches ) || preg_match( "#^$match#", urldecode( $request_match ), $matches ) ) {

                        if ( $wp_rewrite->use_verbose_page_rules && preg_match( '/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch ) ) {
                            // This is a verbose page match, let's check to be sure about it.
                            $page = get_page_by_path( $matches[ $varmatch[1] ] );
                            if ( ! $page ) {
                                continue;
                            }

                            $post_status_obj = get_post_status_object( $page->post_status );
                            if ( ! $post_status_obj->public && ! $post_status_obj->protected && ! $post_status_obj->private && $post_status_obj->exclude_from_search ) {
                                continue;
                            }
                        }

                        // Got a match.
                        $wp->matched_rule = $match;
                        break;
                    }
                }
            }

            if ( isset( $wp->matched_rule ) ) {
                // Trim the query of everything up to the '?'.
                $query = preg_replace( "!^.+\?!", '', $query );

                // Substitute the substring matches into the query.
                $query = addslashes( WP_MatchesMapRegex::apply( $query, $matches ) );

                $wp->matched_query = $query;

                // Parse the query.
                parse_str( $query, $perma_query_vars );

                // If we're processing a 404 request, clear the error var since we found something.
                if ( '404' == $error ) {
                    unset( $error, $_GET['error'] );
                }
            }

            // If req_uri is empty or if it is a request for ourself, unset error.
            if ( empty( $requested_path ) || $requested_file == $self || strpos( $_SERVER['PHP_SELF'], 'wp-admin/' ) !== false ) {
                unset( $error, $_GET['error'] );

                if ( isset( $perma_query_vars ) && strpos( $_SERVER['PHP_SELF'], 'wp-admin/' ) !== false ) {
                    unset( $perma_query_vars );
                }

                $wp->did_permalink = false;
            }
        }

        /**
         * Filters the query variables whitelist before processing.
         *
         * Allows (publicly allowed) query vars to be added, removed, or changed prior
         * to executing the query. Needed to allow custom rewrite rules using your own arguments
         * to work, or any other custom query variables you want to be publicly available.
         *
         * @since 1.5.0
         *
         * @param array $public_query_vars The array of whitelisted query variables.
         */
        $wp->public_query_vars = apply_filters( 'query_vars', $wp->public_query_vars );

        foreach ( get_post_types( array (), 'objects' ) as $post_type => $t ) {
            if ( is_post_type_viewable( $t ) && $t->query_var ) {
                $post_type_query_vars[ $t->query_var ] = $post_type;
            }
        }

        foreach ( $wp->public_query_vars as $wpvar ) {
            if ( isset( $wp->extra_query_vars[ $wpvar ] ) ) {
                $wp->query_vars[ $wpvar ] = $wp->extra_query_vars[ $wpvar ];
            } elseif ( isset( $_POST[ $wpvar ] ) ) {
                $wp->query_vars[ $wpvar ] = $_POST[ $wpvar ];
            } elseif ( isset( $_GET[ $wpvar ] ) ) {
                $wp->query_vars[ $wpvar ] = $_GET[ $wpvar ];
            } elseif ( isset( $perma_query_vars[ $wpvar ] ) ) {
                $wp->query_vars[ $wpvar ] = $perma_query_vars[ $wpvar ];
            }

            if ( ! empty( $wp->query_vars[ $wpvar ] ) ) {
                if ( ! is_array( $wp->query_vars[ $wpvar ] ) ) {
                    $wp->query_vars[ $wpvar ] = (string) $wp->query_vars[ $wpvar ];
                } else {
                    foreach ( $wp->query_vars[ $wpvar ] as $vkey => $v ) {
                        if ( ! is_object( $v ) ) {
                            $wp->query_vars[ $wpvar ][ $vkey ] = (string) $v;
                        }
                    }
                }

                if ( isset( $post_type_query_vars[ $wpvar ] ) ) {
                    $wp->query_vars['post_type'] = $post_type_query_vars[ $wpvar ];
                    $wp->query_vars['name']      = $wp->query_vars[ $wpvar ];
                }
            }
        }

        // Convert urldecoded spaces back into +
        foreach ( get_taxonomies( array (), 'objects' ) as $taxonomy => $t ) {
            if ( $t->query_var && isset( $wp->query_vars[ $t->query_var ] ) ) {
                $wp->query_vars[ $t->query_var ] = str_replace( ' ', '+', $wp->query_vars[ $t->query_var ] );
            }
        }

        // Don't allow non-publicly queryable taxonomies to be queried from the front end.
        if ( ! is_admin() ) {
            foreach ( get_taxonomies( array ( 'publicly_queryable' => false ), 'objects' ) as $taxonomy => $t ) {
                /*
                 * Disallow when set to the 'taxonomy' query var.
                 * Non-publicly queryable taxonomies cannot register custom query vars. See register_taxonomy().
                 */
                if ( isset( $wp->query_vars['taxonomy'] ) && $taxonomy === $wp->query_vars['taxonomy'] ) {
                    unset( $wp->query_vars['taxonomy'], $wp->query_vars['term'] );
                }
            }
        }

        // Limit publicly queried post_types to those that are publicly_queryable
        if ( isset( $wp->query_vars['post_type'] ) ) {
            $queryable_post_types = get_post_types( array ( 'publicly_queryable' => true ) );
            if ( ! is_array( $wp->query_vars['post_type'] ) ) {
                if ( ! in_array( $wp->query_vars['post_type'], $queryable_post_types ) ) {
                    unset( $wp->query_vars['post_type'] );
                }
            } else {
                $wp->query_vars['post_type'] = array_intersect( $wp->query_vars['post_type'], $queryable_post_types );
            }
        }

        // Resolve conflicts between posts with numeric slugs and date archive queries.
        $wp->query_vars = wp_resolve_numeric_slug_conflicts( $wp->query_vars );

        foreach ( (array) $wp->private_query_vars as $var ) {
            if ( isset( $wp->extra_query_vars[ $var ] ) ) {
                $wp->query_vars[ $var ] = $wp->extra_query_vars[ $var ];
            }
        }

        if ( isset( $error ) ) {
            $wp->query_vars['error'] = $error;
        }

        /**
         * Filters the array of parsed query variables.
         *
         * @since 2.1.0
         *
         * @param array $query_vars The array of requested query variables.
         */
        $wp->query_vars = apply_filters( 'request', $wp->query_vars );

        /**
         * Fires once all query variables for the current request have been parsed.
         *
         * @since 2.1.0
         *
         * @param WP &$wp Current WordPress environment instance (passed by reference).
         */
        unset( $wp->query_vars[ $amp_endpoint ] );
        do_action_ref_array( 'parse_request', array ( &$wp ) );
    }



    /*
     * Include Required Files
     */
    public function uamp_include_files() {

		// Helper Functions
		require_once UAMP_DIR . '/functions/helper.php';

		require_once UAMP_DIR . '/inc/class-uamp-template-manager.php';
		require_once UAMP_DIR . '/inc/class-uamp-template.php';

		// Ultimate AMP Autoload Class
		require_once UAMP_DIR . '/inc/class-uamp-autoload.php';
	    UltimateAmpAutoload::register();


    }


	public function uamp_bundle_core_files(){

		require_once UAMP_DIR .'/lib/vendor/amp/amp.php';

		define( 'AMP__FILE__', __FILE__ );
		if ( ! defined('AMP__DIR__') ) {
			define( 'AMP__DIR__', plugin_dir_path(__FILE__) . 'lib/vendor/amp/' );
		}
		if ( ! defined('AMP_QUERY_VAR') ){
			define('AMP_QUERY_VAR', 'amp');
		}

		define( 'AMP__VERSION', '0.7.2' );

		require_once AMP__DIR__ . '/includes/class-amp-autoloader.php';
		AMP_Autoloader::register();

		require_once AMP__DIR__ . '/back-compat/back-compat.php';
		require_once AMP__DIR__ . '/includes/amp-helper-functions.php';
		require_once AMP__DIR__ . '/includes/admin/functions.php';
	}




	public function uamp_welcome_screen_page(){
		add_dashboard_page(
			esc_html__('Welcome Ultimate AMP','uamp'),
			esc_html__('Welcome to Ultimate AMP','uamp'),
			'read',
			'uamp-welcome-page',
			[$this, 'uamp_welcome_page']
		);
	}


	public function uamp_welcome_page(){
		require_once UAMP_DIR . '/inc/Welcome.php';

	}

	public function uamp_welcome_redirect($plugin){
		if($plugin=='ultimate-amp/ultimate-amp.php') {
			wp_redirect(admin_url('admin.php?page=uamp-welcome-page'));
			die();
		}
	}

	public function uamp_remove_menu_entry(){
		remove_submenu_page( 'index.php', 'uamp-welcome-page' );
	}





	public function uamp_add_menu_link_class( $atts, $item, $args ) {
        if($args->theme_location == 'uamp-footer-menu') {
            $class = 'text-decoration-none ampstart-label';
            $atts['class'] = $class;
        }
        return $atts;
    }


    public function uamp_menu_link_list_classes($classes, $item, $args) {
        if($args->theme_location == 'uamp-footer-menu') {
            $classes[] = 'px1';
        }
        return $classes;
    }


    public function search_filter( $query ) {
        if ( ! is_admin() && $query->is_main_query() ) {
            if ( $query->is_search ) {
                $query->set( 'meta_query', array (
                    'relation' => 'OR',
						array (
							'key'     => 'uamp-exclude',
							'value'   => '',
							'compare' => 'NOT EXISTS'
						),
						array (
							'key'     => 'uamp-exclude',
							'value'   => 'true',
							'compare' => '!='
						),
                ) );
            }
        }
    }


    /*
     * After Theme Setup
     */
    public function uamp_after_theme_setup() {
//			Ultimate_AMP_Helper:: amp_get_slug(); // Ensure AMP_QUERY_VAR is set.

        if (false === apply_filters('amp_is_enabled', true)) {
            return;
        }

        add_action('init', [$this, 'uamp_init'], 0); // Must be 0 because widgets_init happens at init priority 1.
    }


    public function get_basename() {
        return plugin_basename( __FILE__ );
    }

    public function uamp_admin_init(){
        add_filter( 'plugin_action_links_' . $this->get_basename(), [ $this, 'uamp_action_links' ] );
    }


    public function uamp_action_links( $links ) {
        $settings_link = '<a href="admin.php?page=uamp_options">Settings</a>';
        array_push( $links, $settings_link );
        return $links;
    }

    /*
    * Register Activation Hook
    */
    public function uamp_activate() {
        $this->uamp_after_theme_setup();
        if (!did_action('uamp_init')) {
            $this->uamp_init();
        }
        flush_rewrite_rules();
    }




// AMP by WP
		function uamp_deactivate_ampbywp(){
			$dependent = 'amp/amp.php';
			if( is_plugin_active($dependent) ){
				add_action('update_option_active_plugins', 'uamp_deactivate_ampbywp_independent');
			}
		}
		function uamp_deactivate_ampbywp_independent(){
			$dependent = 'amp/amp.php';
			deactivate_plugins($dependent);
		}


// AMP for WP
		function uamp_deactivate_ampforwp(){
			$dependent = 'accelerated-mobile-pages/accelerated-moblie-pages.php';
			if( is_plugin_active($dependent) ){
				add_action('update_option_active_plugins', 'uamp_deactivate_ampforwp_independent');
			}
		}
		function uamp_deactivate_ampforwp_independent(){
			$dependent = 'accelerated-mobile-pages/accelerated-moblie-pages.php';
			deactivate_plugins($dependent);
		}


// Better AMP
		function uamp_deactivate_better_amp(){
			$dependent = 'better-amp/better-amp.php';
			if( is_plugin_active($dependent) ){
				add_action('update_option_active_plugins', 'uamp_deactivate_better_amp_independent');
			}
		}
		function uamp_deactivate_better_amp_independent(){
			$dependent = 'better-amp/better-amp.php';
			deactivate_plugins($dependent);
		}


// Better AMP
		function uamp_deactivate_wp_amp(){
			$dependent = 'wp-amp/wp-amp.php';
			if( is_plugin_active($dependent) ){
				add_action('update_option_active_plugins', 'uamp_deactivate_wp_amp_independent');
			}
		}
		function uamp_deactivate_wp_amp_independent(){
			$dependent = 'wp-amp/wp-amp.php';
			deactivate_plugins($dependent);
		}





		/*
		 * Register Ultimate AMP Menus
		 */
    public function uamp_register_menus() {
        register_nav_menus(
            array(
                'uamp-main-menu' => __('Ultimate AMP Main Menu', UAMP_TD),
            )
        );

        register_nav_menus(
            array(
                'uamp-footer-menu' => __('Ultimate AMP Footer Menu', UAMP_TD),
            )
        );
    }

    /*
     * Setup Localization
     */
    public function localization_init() {
        load_plugin_textdomain('uamp', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }


    /*
     * Plugin URL
     */
    public function plugin_url() {
        if ($this->plugin_url) return $this->plugin_url;

        return $this->plugin_url = untrailingslashit(plugins_url('/', __FILE__));
    }


    /*
     * Plugin Directory
     */
    public function plugin_path() {
        if ($this->plugin_path) return $this->plugin_path;

        return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
    }

    /*
     * Plugin Directory URL
     */
    public function plugin_dir_url() {
        if ($this->plugin_dir_url) return $this->plugin_dir_url;

        return $this->plugin_dir_url = untrailingslashit(plugin_dir_url(__FILE__));
    }


    /*
     * Register DeActivation Hook
     */
    public function uamp_deactivate() {
        global $wp_rewrite;
        // We need to manually remove the amp endpoint
        global $wp_rewrite;
        foreach ($wp_rewrite->endpoints as $index => $endpoint) {
            if (Ultimate_AMP_Helper:: amp_get_slug() === $endpoint[1]) {
                unset($wp_rewrite->endpoints[$index]);
                break;
            }
        }

        flush_rewrite_rules();
    }

    /*
     * Check Ultimate AMP is AMP URL Structure
     */
    public function is_amp() {
        $endpoint = $this->uamp_get_endpoint();

        if ( '' == get_option( 'permalink_structure' ) ) {
            parse_str( $_SERVER['QUERY_STRING'], $url );

            return isset( $url[ $endpoint ] );
        }

        $url_parts   = explode( '?', $_SERVER["REQUEST_URI"] );
        $query_parts = explode( '/', $url_parts[0] );

        $is_amp = ( in_array( $endpoint, $query_parts ) );
        do_action( 'uamp_is_amp', $is_amp );

        return $is_amp;
    }



    public function uamp_get_endpoint() {
        global $uamp_options;
        $uamp_endpoint = $uamp_options['uamp_endpoint']; //Feature needs to be added


        $endpoint_opt = get_option( 'uamp_endpoint' );
        $endpoint     = ( $endpoint_opt ) ? $endpoint_opt : self::AMP_QUERY;

        return $endpoint;
    }


    function amp_redirect_old_slug_to_new_url($link) {

        if (is_amp_endpoint()) {
            $link = trailingslashit(trailingslashit($link) . amp_get_slug());
        }

        return $link;
    }



    public function add_startpint($name, $places, $query_var = true, $single_match = true) {

        global $wp;

        // For backward compatibility, if null has explicitly been passed as `$query_var`, assume `true`.
        if (true === $query_var || null === func_get_arg(2)) {
            $query_var = $name;
        }

        $this->startpints[] = [$places, $name, $query_var, $single_match];

        if ($query_var) {
            $wp->add_query_var($query_var);
        }

    }


    function uamp_add_rewrite_startpoint($name, $places, $query_var = true, $single_match = true) {
        $this->add_startpint($name, $places, $query_var, $single_match);
    }




    /*
     * Generate Endpoints
     */
    public function uamp_generate_endpoint() {

        $uamp_slug = self::AMP_QUERY;

        return $uamp_slug;
    }

    /*
     * Get Request URL
     */
    public static function get_requested_url() {

        if (isset($_SERVER['HTTP_HOST'])) {
            $requested_url = is_ssl() ? 'https://' : 'http://';
            $requested_url .= $_SERVER['HTTP_HOST'];
            $requested_url .= $_SERVER['REQUEST_URI'];

            return $requested_url;
        }

        return '';
    }


    /*
     * Check URL Endpoints for AMP
     */
    public function uamp_is_amp_endpoint() {
        if ($this->uamp_is_non_amp() && !is_admin()) {
            return $this->uamp_is_non_amp();
        } else {
            return false !== get_query_var('amp', false);
        }
    }

    public function uamp_is_non_amp() {
        $not_amp = true;

        return $not_amp;
    }


    public function uamp_check_debug_mode(){
        global $uamp_options;
        if ( $uamp_options['enable_debug_mode'] == 'enable') {
            error_reporting( E_ALL );
            ini_set( 'display_errors', 1 );
        } else {
            error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
            ini_set( 'display_errors', 0 );
        }
    }



    public function uamp_add_rel_info() {
        $url = $this->uamp_get_rel_info();
        if ( ! empty( $url ) ) {
            echo "<link rel='amphtml' href='$url' />";
        }
    }


    public function uamp_get_rel_info() {
        global $wp, $wp_query;

        if ( $this->is_excluded( $wp_query->get_queried_object_id() ) ) {
            return '';
        }

        if ( get_option( 'permalink_structure' ) ) {
            $url = user_trailingslashit( home_url( $wp->request . '/' . Ultimate_AMP()->uamp_get_endpoint() ) );
        } else {
            $url = home_url( add_query_arg( Ultimate_AMP()->uamp_get_endpoint(), '1' ) );
        }

        if ( is_search() AND isset( $_GET['s'] ) ) {
            $url = user_trailingslashit( trailingslashit( get_search_link() ) . Ultimate_AMP()->uamp_get_endpoint() );
        }

        return apply_filters( 'uamp_rel_link_url', $url, $this );
    }


    public function is_excluded( $id ) {
        global $wp_query;
        $is_excluded   = false;
//			$allowed_pages = is_array( $this->options->get( 'archives' ) ) ? $this->options->get( 'archives' ) : array ();
//			if ( is_archive() && false == $this->is_allowed_page( $allowed_pages ) || ! is_archive() && $id && $this->is_excluded_post( $id ) || $this->is_excluded_posts_page() && $wp_query->is_home() || is_search() && false == $this->is_allowed_page( $allowed_pages ) ) {

//        if ( is_archive() ) {
//            $is_excluded = true;
//        }

        return $is_excluded;
    }



    public function is_excluded_posts_page() {
	    global $uamp_options;
	    $archives = $uamp_options['archives'];
	    $allowed_pages = is_array( $archives ) ? $archives : array ();

//        $allowed_pages = is_array( $this->options->get( 'archives' ) ) ? $this->options->get( 'archives' ) : array ();

        return 'posts' == get_option( 'show_on_front' ) && ! array_search( 'show_on_front', $allowed_pages );
    }

    public function is_excluded_post( $id ) {
        $allowed_post_types = is_array( $this->options->get( 'post_types' ) ) ? $this->options->get( 'post_types' ) : array ();
        $is_excluded        = ( "true" === get_post_meta( $id, 'uamp-exclude', true ) || false == in_array( get_post_type( $id ), $allowed_post_types ) );

        return apply_filters( 'uamp_is_excluded_post', $is_excluded, $id );
    }


	public function is_home_posts_page() {
		return ( is_home() && 'posts' == get_option( 'show_on_front' ) );
	}


	public static function is_home_static_page() {
		return ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_on_front' ) && is_page( get_option( 'page_on_front' ) ) );
	}


	public function is_posts_page() {
		return ( is_home() && 'page' == get_option( 'show_on_front' ) );
	}

    public function get_queried_object_id() {
        global $wp_query, $wp;

        $queried_object_id = $wp_query->get_queried_object_id();

        if ( $wp->request === $this->uamp_get_endpoint() ) {
            $queried_object_id = get_option( 'page_on_front' );
        }

        if ( $wp_query->is_archive() ) {
            $queried_object_id = '';
        }

        return $queried_object_id;

    }




	public function amp_add_post_template_actions() {

		include_once( 'templates/template-one/functions.php' );

		uamp_post_template_init_hooks();
	}




    public function uamp_load_template($post) {
        global $wp, $uamp_options;

        $queried_object_id = $this->get_queried_object_id();

        do_action( 'before_uamp_load_template', $queried_object_id );

        $redirect_url = $this->uamp_get_redirect_url( $wp, $queried_object_id );

        if ( $redirect_url ) {
            wp_redirect( $redirect_url );
            exit();
        }


        if ( $this->is_amp() && $uamp_options['uamp_is_amp'] == "enable") {

            $this->amp_add_post_template_actions();

	        $template = new Ultimate_Template_Loader( $post_id );

            apply_filters( 'after_uamp_load_template', $template );

	        do_action( 'before_uamp_load_template', $template );


            exit();
        }


    }


    public function uamp_get_redirect_url( $wp, $queried_object_id ) {
        $url       = '';
        $post      = get_post( $queried_object_id );

        $is_mobile = $this->is_mobile() && false == $this->is_excluded( $queried_object_id );

        $post_id   = is_object( $post ) ? $post->ID : '';


        if ( $this->is_amp() ) {
            if ( isset( $_GET['is_amp'] ) && sanitize_text_field( $_GET['is_amp'] ) && isset( $_GET['s'] ) ) {
                $url = $this->get_search_redirect_url();
            } elseif ( $this->is_excluded( $queried_object_id ) && ! is_404() ) {
                $url = $this->get_excluded_redirect_url( $wp, $queried_object_id );
            } elseif ( is_singular( array ( 'post', 'page' ) ) && post_password_required( $post_id ) ) {
                // redirect to original password form from amp url

                $url = get_permalink( $post_id );
            }
        } else if ( apply_filters( 'uamp_is_mobile_get_redirect_url', $is_mobile ) && ! post_password_required( $post_id ) ) {
            if ( '' != get_option( 'permalink_structure' ) ) {
                $url = home_url( $wp->request ) . '/' . $this->uamp_get_endpoint() . '/';
            } else {
                $args = array ();
                parse_str( $_SERVER['QUERY_STRING'], $args );
                $args[ $this->uamp_get_endpoint() ] = 1;
                $url                           = add_query_arg( $args );
            }
        }

        return $url;
    }



    public function view_original_redirect( $is_mobile ) {
        return $is_mobile && false == isset( $_GET['view-original-redirect'] );
    }

    public function get_search_redirect_url() {
        if ( get_query_var( 's' ) ) {
            $url = '/' . 'search' . '/' . get_query_var( 's' ) . '/' . $this->uamp_get_endpoint() . '/';
        } else {
            $url = '/' . '?s' . '&' . $this->uamp_get_endpoint() . '=1';
        }

        return $url;
    }

    public function get_excluded_redirect_url( $wp, $queried_object_id ) {
        $endpoint = $this->uamp_get_endpoint();
        if ( $queried_object_id ) {
            $url = get_permalink( $queried_object_id );
        } else if ( '' != get_option( 'permalink_structure' ) ) {
            $url = home_url( rtrim( $wp->request, $endpoint ) );
        } else {
            $url = remove_query_arg( $endpoint );
        }

        return $url;
    }


    public function is_mobile() {
			global $uamp_options;
			return wp_is_mobile() && $uamp_options['uamp_is_amp'] == "enable";
    }

    public function get_plugin_folder_name() {
        $names = explode( '/', self::get_basename() );

        return $names[0];
    }






    public function uamp_auto_add_amp_menu_link_insert() {
        add_action( 'wp', [$this, 'uamp_auto_add_amp_in_link_check'] );
    }

    public function uamp_auto_add_amp_in_link_check() {
        if($this->is_amp()){
            add_filter( 'nav_menu_link_attributes',[$this, 'uamp_auto_add_amp_in_menu_link'], 10, 3 );
        }
    }


    public function uamp_auto_add_amp_in_menu_link( $atts, $item, $args ) {
        if( 'page' == $item->object ){
            $id = $item->object_id;
            $id = get_post_meta( $item->ID, '_menu_item_object_id', true );
            $link = get_permalink($id);
            $atts['href'] = user_trailingslashit(trailingslashit( $link ) . UAMP_QUERY_VAR);
        }
        return $atts;
    }


}



/*
 * Initialize Ultimate_AMP Plugin
 */
function ultimate_amp() {
    return Ultimate_AMP::init();
}

// Let's kick it
ultimate_amp();

