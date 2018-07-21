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


//16-7-18
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
define( 'AMP_QUERY', 'amp');
//16-7-18

//define( 'UAMP_TMP_DIR', apply_filters( 'ultimate-amp/template/dir-name', 'uamp' ) );


	include_once(ABSPATH . 'wp-admin/includes/plugin.php');

	class Ultimate_AMP {
		/*
         * Ultimate AMP Constants
         */

		const AMP_QUERY    = 'amp';

		/*
		 * Ultimate AMP Version Number
		 */
		public $version = '1.0.0';

		/*
		 * Options variable
		 */
		public $uamp_options;

		public $options;

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
//
//		public function __construct() {
//			$this->uamp_options = $uamp_options;
//		}

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

			//Localization Initialize
			add_action('plugins_loaded', [$this, 'localization_init']);

			// Image Size
			$uamp_width         = get_option( 'uamp_content_width' );
			$uamp_content_width = $uamp_width ? $uamp_width : '600';
			add_image_size( 'uamp-image', $uamp_content_width );
			add_image_size( 'uamp-image-small', ceil( $uamp_content_width / 3 ) );

			// rel=amphtml
			add_action( 'wp_head', [ $this, 'add_rel_info'] );

			//$this->uamp_check_debug_mode();

			add_action( 'init', [ $this, 'uamp_init' ], 99 );
			add_action('init', array( $this, 'uamp_register_menus'));
			add_action('uamp_init',[ $this, 'uamp_auto_add_amp_menu_link_insert'],9999);

		}



		/*
		 * Init Ultimate AMP
		 */

		public function uamp_init() {


			$this->uamp_include_files();

//			$this->uamp_options = new Ultimate_AMP_Options();

			//Ultimate AMP Actions/Hooks
			do_action('uamp_init');

			add_action( 'template_redirect', array ( $this, 'load_amphtml' ) );

			add_rewrite_endpoint( $this->get_endpoint(), EP_ALL );
			add_action( 'pre_get_posts', array ( $this, 'search_filter' ) );
			add_filter( 'do_parse_request', array ( $this, 'parse_request' ), 10, 3 );
			add_filter( 'amphtml_is_mobile_get_redirect_url', array ( $this, 'view_original_redirect' ) );

//          16-7-18
			//Load Frontend Scripts and Styles
//			add_action('init', [$this, 'uamp_enqueue_scripts']);

//          16-7-18
			//Register Ultimate AMP Menus

			//After Theme Setup
//          16-7-18
//			add_action('after_setup_theme', [$this, 'uamp_after_theme_setup'], 5);

			// Activation Hook
			register_activation_hook(__FILE__, [$this, 'uamp_activate']);
			register_deactivation_hook(__FILE__, [$this, 'uamp_deactivate']);

			// Default AMP Plugin
			add_action('plugins_loaded', [$this, 'uamp_deafult_amp_plugin'], 10);

			// Load AMP Template Files
//			add_filter('amp_post_template_file', [$this, 'uamp_custom_template'], 10, 2);

//          16-7-18
//			add_post_type_support('post', AMP_QUERY_VAR);
//			add_filter('request', 'amp_force_query_var_value');
//			add_action('wp', 'amp_maybe_add_actions', 100);

			/**
			 * Triggers while Ultimate AMP Plugin is Active
			 */


			// Rewrite rules for Ultimate AMP
//          16-7-18
//			add_rewrite_endpoint(Ultimate_AMP_Helper:: amp_get_slug(), EP_PERMALINK);
//			add_rewrite_endpoint( $this->uamp_get_endpoint(), EP_ALL );
//
//			add_filter('init', [$this, 'uamp_add_rewrite']);
//			add_filter('init', [$this, 'append_index_rewrite_rule']);
//
//			add_action('pre_get_posts', [$this, 'isolate_pre_get_posts_start'], 1);
//			add_action('pre_get_posts', [$this, 'isolate_pre_get_posts_end'], 100);
//          16-7-18

			// Redirect the old url of amp page to the updated url.
//          16-7-18
//			add_filter('old_slug_redirect_url', [$this, 'amp_redirect_old_slug_to_new_url']);


			// Automatic Redirect Mobile Users
//          16-7-18
//		    add_action( 'template_include',  [$this, 'uamp_include_template_files'], 9999 );
//          add_action('template_redirect', [$this, 'uamp_auto_redirect_to_amp'], 100);
//			add_action('template_redirect', [$this, 'uamp_page_status_check'], 100);


			// Ultimate AMP Scripts/Styles
//          16-7-18
//			add_action('ultimate-amp/template/enqueue-scripts', [$this, 'enqueue_components_scripts']);


			if (class_exists('Jetpack') && !(defined('IS_WPCOM') && IS_WPCOM)) {
				require_once(AMP__DIR__ . '/jetpack-helper.php');
			}

			define('UAMP_QUERY_VAR', apply_filters('amp_query_var', $this->uamp_generate_endpoint()));

			if ( is_admin() ) {
				$this->uamp_admin_init();
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

			$amp_endpoint                    = $this->get_endpoint();
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

			// Ultimate AMP Autoload Class
			require_once UAMP_DIR . '/inc/class-uamp-autoload.php';
			require_once UAMP_DIR . '/inc/uamp-options.php';

			require_once UAMP_DIR . '/inc/class-uamp-template-manager.php';
			require_once UAMP_DIR . '/inc/class-uamp-template.php';
//			require_once UAMP_DIR . '/inc/class-uamp-sanitize.php';
//			require_once UAMP_DIR . '/inc/class-uamp-shortcodes.php';

//			require_once( 'includes/class-amphtml-template-abstract.php' );
//			require_once( 'includes/class-amphtml-template.php' );
//			require_once( 'includes/class-amphtml-options.php' );
//			require_once( 'includes/class-amphtml-update.php' );
//			require_once( 'includes/class-amphtml-no-conflict.php' );



			// Helper Functions
			require_once UAMP_DIR . '/functions/helper.php';

			UltimateAmpAutoload::register();


			//		if ( ! class_exists( 'ReduxFramework' ) && is_admin() ) {
			if ( ! class_exists( 'ReduxFramework' )) {
				require_once UAMP_DIR . '/inc/admin/redux-core/framework.php';
			}

			// Register all the Main Options
			require_once UAMP_DIR.'/inc/admin/admin-options.php';

//			require_once UAMP_DIR . '/templates/template-one/functions.php';



		}



		public function search_filter( $query ) {
			if ( ! is_admin() && $query->is_main_query() ) {
				if ( $query->is_search ) {
					$query->set( 'meta_query', array (
						'relation' => 'OR',
						array (
							'key'     => 'amphtml-exclude',
							'value'   => '',
							'compare' => 'NOT EXISTS'
						),
						array (
							'key'     => 'amphtml-exclude',
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


		public function uamp_custom_template() {
			Ultimate_AMP_Helper::uamp_include_template_file();
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
		 * Scripts and Styles
		 */
		public function uamp_enqueue_scripts() {

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


		public function isolate_pre_get_posts_end(&$wp_query) {

			global $better_amp_isolate_pre_get_posts;

			if (!is_admin() && $wp_query->is_main_query()) {
				if ($better_amp_isolate_pre_get_posts) {
					$wp_query->query_vars = $better_amp_isolate_pre_get_posts;
					unset($better_amp_isolate_pre_get_posts);
				}
			}
		}


		public function isolate_pre_get_posts_start($wp_query) {

			global $better_amp_isolate_pre_get_posts;


			if (!is_admin() && $wp_query->is_main_query()) {
				$better_amp_isolate_pre_get_posts = $wp_query->query_vars;
			}

		}


		public function uamp_include_template_files() {

			$include = $this->template_loader();

			if ($include = apply_filters('ultimate-amp/template/include', $include)) {
				//			return $include;
			} else if (current_user_can('switch_themes')) {
				wp_die(__('Ultimate AMP Theme Was Not Found!', 'uamp'));
			} else {
				return UAMP_DIR . '/no-template.php';
			}

		}


		public function enqueue_components_scripts() { ?>
            <script async src="https://cdn.ampproject.org/v0.js"></script>

            <style amp-boilerplate="">body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate="">body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>



            <script custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"
                    async></script>
            <script custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"
                    async></script>
            <script custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"
                    async></script>
            <script custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js" async></script>
            <script custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"
                    async></script>

		<?php }


		public function better_amp_get_template_directory() {

			if ($theme_info = $this->better_amp_get_template_info()) {
				return $theme_info['TemplateRoot'];
			}

			return '';
		}


		function better_amp_get_template_info() {

			return wp_parse_args(
				apply_filters('ultimate-amp/template/template-one/active-template', []),
				[
					'ScreenShot' => 'screenshot.png',
					'MaxWidth' => 780,
					'view' => 'general'
				]
			);
		}


		public function uamp_single_template() {
			$template = new Ultimate_Template_Loader();

			return $template->get_template_part('single');
		}

		public function uamp_page_template() {
			$template = new Ultimate_Template_Loader();

			return $template->get_template_part('page');
		}

		public function uamp_home_template() {
			$template = new Ultimate_Template_Loader();

			return $template->get_template_part('home');
		}

		public function better_amp_index_template() {
			$template = new Ultimate_Template_Loader();

			return $template->get_template_part('index');
		}

		public function uamp_404_template() {
			$template = new Ultimate_Template_Loader();

			return $template->get_template_part('404');
		}

		public function uamp_woocommerce_template() {
			$template = new Ultimate_Template_Loader();

			return $template->get_template_part('woocommerce.php');
		}

		function template_loader() {

			if (!$this->uamp_is_amp_endpoint()) {
				return;
			}


			$templates = new Ultimate_Template_Loader();

			if (function_exists('is_embed') && is_embed() && $template = better_amp_embed_template()) :
            elseif (function_exists('is_woocommerce') && is_woocommerce() && is_page(wc_get_page_id('shop')) && $template = $this->uamp_woocommerce_template()) :
            elseif (is_404() && $template = $this->uamp_404_template()) :
				//        elseif ( is_search() && $template = better_amp_search_template() ) :
            elseif (is_home() && $template = $this->uamp_home_template()) :
				//        elseif ( is_post_type_archink rel="canonical" href=ve() && $template = better_amp_post_type_archive_template() ) :
				//        elseif ( is_tax() && $template = better_amp_taxonomy_template() ) :
				//        elseif ( is_attachment() && $template = better_amp_attachment_template() ) :
				//			remove_filter( 'the_content', 'prepend_attachment' );
            elseif (is_single() && $template = $this->uamp_single_template()) :
            elseif (is_page() && $template = $this->uamp_page_template()) :
            elseif (is_singular() && $template = $this->uamp_single_template()) :
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
			add_rewrite_rule(self::AMP_QUERY . '/?$', "index.php?amp=index", 'top');
		}


		public function uamp_add_rewrite() {
			$this->uamp_add_rewrite_startpoint('amp', EP_ALL);

			/**
			 * automattic amp compatibility
			 */
			$amp_qv = defined('AMP_QUERY_VAR') ? AMP_QUERY_VAR : 'amp';
			add_rewrite_endpoint($amp_qv, EP_PERMALINK);
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
			$current_location = '';
			$home_url = '';
			$blog_page_id = '';

			$current_location = home_url($wp->request);
			$home_url = get_bloginfo('url');


			if (is_archive()) {
				$redirection_location = add_query_arg('', '', home_url($wp->request));
				$redirection_location = trailingslashit($redirection_location);
				$redirection_location = dirname($redirection_location);
				wp_safe_redirect($redirection_location);
				exit;
			}

			if (is_front_page() && $current_location == $home_url) {
				return;
			}

			if (is_archive()) {
				return;
			}

			if (is_front_page()) {
				$redirection_location = $home_url;
			}

			wp_safe_redirect($redirection_location);
			exit;

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
		 * Generate AMP to HTML
		 */
		public function uamp_generate_amphtml() {
			global $wp, $post;
			$post_id = '';
			$current_url = '';
			$check_endpoints = true;

			$current_archive_url = '';
			$amp_url = '';
			$remove = '';
			$query_arg_array = '';
			$page = '';

			if (is_singular()) {
				$post_id = get_the_ID();
				$request_url = get_permalink(get_queried_object_id());
				$explode_url = explode('/', $request_url);
				$amp_append = 'amp';
				array_splice($explode_url, 4, 0, $amp_append);
				$impode_url = implode('/', $explode_url);
				$amp_url = untrailingslashit($impode_url);
			}


			//			$query_arg_array = $wp->query_vars;

			//			if ((is_home() || is_archive()) && $wp->query_vars['paged'] >= '2') {

			if (is_home() || is_front_page() || is_archive()) {
				global $wp;
				$new_url = home_url($wp->request);
				$explode_path = explode("/", $new_url);
				$inserted = [AMP_QUERY_VAR];
				array_splice($explode_path, 3, 0, $inserted);
				$impode_url = implode('/', $explode_path);
				$amp_url = untrailingslashit($impode_url);

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

                <link rel="canonical"
                      href="<?php echo user_trailingslashit(esc_url(apply_filters('uamp_modify_rel_url', $amp_url))) ?>">
                <link rel="amphtml"
                      href="<?php echo user_trailingslashit(esc_url(apply_filters('uamp_modify_rel_url', $amp_url))) ?>"/>
				<?php

				//				wp_redirect( esc_url( $amp_url )  , 301 );
				//				exit();


				//				}
			}

			if (is_404()) {
				$amp_url = 'https://jeweltheme.com';

				return;
			}


			return $amp_url;
		}


		/*
		 * Automatic Redirect to AMP version of Mobile Users
		 */
		public function uamp_auto_redirect_to_amp() {

			//		if ( ! $this->uamp_is_amp_endpoint() ) {
			//			return;
			//		}


			//		$redirect_url = '';
			$redirect_url = $this->uamp_generate_amphtml();

			//        if(is_home() || is_front_page()){
			//			$redirect_url = $this->uamp_home_template();
			//        }

			$request_url = $this->get_requested_url();


			//print_r( $this->uamp_home_template());
			//		print_r($redirect_url);
			print_r($request_url);

			if ($this->uamp_is_amp_endpoint()) {
				//			print_r('Liton Arefin, This is Homepage');

				if (is_home()) {
					print_r('Liton Arefin, This is Homepage');
				}

				return $this->template_loader();
			}


			//		print_r($redirect_url);

			//		print_r( $this->uamp_home_template());


			//		$is_amp_endpoint = $this->uamp_is_amp_endpoint();

			//        print_r($this->uamp_generate_endpoint());
			//		print_r( $this->uamp_home_template());
			//		print_r( $this->uamp_is_amp_endpoint() );


			//		if($this->uamp_is_amp_endpoint()){
			//			return;
			//		}

			if (wp_is_mobile()) {
				if ($redirect_url) {
					wp_redirect($redirect_url);
					exit();
				}
			}


			return;
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
			if ( 'enable' == $uamp_options['enable_debug_mode'] ) {
				error_reporting( E_ALL );
				ini_set( 'display_errors', 1 );
			} else {
				error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
				ini_set( 'display_errors', 0 );
			}
        }



		public function add_rel_info() {
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

			if ( is_archive() ) {
				$is_excluded = true;
			}

			return $is_excluded;
		}

		public function is_excluded_posts_page() {
			$allowed_pages = is_array( $this->options->get( 'archives' ) ) ? $this->options->get( 'archives' ) : array ();

			return 'posts' == get_option( 'show_on_front' ) && ! array_search( 'show_on_front', $allowed_pages );
		}

		public function is_excluded_post( $id ) {
			$allowed_post_types = is_array( $this->options->get( 'post_types' ) ) ? $this->options->get( 'post_types' ) : array ();
			$is_excluded        = ( "true" === get_post_meta( $id, 'amphtml-exclude', true ) || false == in_array( get_post_type( $id ), $allowed_post_types ) );

			return apply_filters( 'uamp_is_excluded_post', $is_excluded, $id );
		}

		public function get_queried_object_id() {
			global $wp_query, $wp;

			$queried_object_id = $wp_query->get_queried_object_id();

			if ( $wp->request === $this->get_endpoint() ) {
				$queried_object_id = get_option( 'page_on_front' );
			}

			if ( $wp_query->is_archive() ) {
				$queried_object_id = '';
			}

			return $queried_object_id;
		}


		public function load_amphtml() {
			global $wp;

			$queried_object_id = $this->get_queried_object_id();

			do_action( 'before_load_amphtml', $queried_object_id );

			$redirect_url = $this->get_redirect_url( $wp, $queried_object_id );
			if ( $redirect_url ) {
				wp_redirect( $redirect_url );
				exit();
			}

			include_once( 'inc/class-uamp-template.php' );

			$this->template = new Ultimate_AMP_Template( $this->options );

			new Ultimate_AMP_Shortcode( $this->template );

//			print_r($this->template);

			if ( $this->is_amp() ) {

				$this->template->load();

				$this->template = apply_filters( 'amphtml_template_load_after', $this->template );
//
//				print_r( $this->template );
//
				do_action( 'amphtml_before_render', $this->template );
				echo $this->template->render();


				exit();
			}
		}

		public function get_redirect_url( $wp, $queried_object_id ) {
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
					$url = home_url( $wp->request ) . '/' . $this->get_endpoint() . '/';
				} else {
					$args = array ();
					parse_str( $_SERVER['QUERY_STRING'], $args );
					$args[ $this->get_endpoint() ] = 1;
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
				$url = '/' . 'search' . '/' . get_query_var( 's' ) . '/' . $this->get_endpoint() . '/';
			} else {
				$url = '/' . '?s' . '&' . $this->get_endpoint() . '=1';
			}

			return $url;
		}

		public function get_excluded_redirect_url( $wp, $queried_object_id ) {
			$endpoint = $this->get_endpoint();
			if ( $queried_object_id ) {
				$url = get_permalink( $queried_object_id );
			} else if ( '' != get_option( 'permalink_structure' ) ) {
				$url = home_url( rtrim( $wp->request, $endpoint ) );
			} else {
				$url = remove_query_arg( $endpoint );
			}

			return $url;
		}

		public function get_endpoint() {
			$endpoint_opt = get_option( 'amphtml_endpoint' );
			$endpoint     = ( $endpoint_opt ) ? $endpoint_opt : self::AMP_QUERY;

			return $endpoint;
		}


		public function is_mobile() {
//			global $uamp_options;
//			return wp_is_mobile() && $uamp_options['mobile_amp'] == "enable";
			return wp_is_mobile();
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



		/*
		 * Load Default AMP Plugin
		 */
		public function uamp_deafult_amp_plugin() {
			require_once UAMP_DIR . '/inc/amp/amp.php';

//			define('AMP__FILE__', __FILE__);
			if (!defined('AMP__DIR__')) {
				define('AMP__DIR__', plugin_dir_path(__FILE__) . '/inc/amp/');
			}
//			define('AMP__VERSION', '0.7.1');

			require_once(AMP__DIR__ . '/back-compat/back-compat.php');
			require_once(AMP__DIR__ . '/includes/amp-helper-functions.php');
			require_once(AMP__DIR__ . '/includes/admin/functions.php');
			require_once(AMP__DIR__ . '/includes/settings/class-amp-customizer-settings.php');
			require_once(AMP__DIR__ . '/includes/settings/class-amp-customizer-design-settings.php');
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
