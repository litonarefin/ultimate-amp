<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/24/18
	 */

//	namespace Uamp\inc;

	/*
	 * Ultimate AMP Plugin Helper Class
	 */
	class Ultimate_AMP_Helper {

		const SITE_ICON_SIZE = 32;
		const CONTENT_MAX_WIDTH = 600;
		const NAVBAR_BG = '#0a89c0';

		private $template_dir;
		private $data;
		public $ID;
		public $post;

		public function __construct() {
			//add_action( 'template_include', array( $this, 'uamp_include_template_file' ), 99999 );
		}


		// Ultimate AMP Language Attributes
		public static function uamp_language_attributes() {
			$attributes = array();
			if ( function_exists( 'is_rtl' ) && is_rtl() ) {
				$attributes[] = 'dir="rtl"';
			}
			if ( $lang = get_bloginfo( 'language' ) ) {
				$attributes[] = "lang=\"$lang\"";
			}
			$output = implode( ' ', $attributes );
			echo $output;
		}

		// Include Template Files
		public static function uamp_include_template_file( ){
			global $post_id;

			// $uamp = new AMP_Post_Template($post_id);

			require_once UAMP_DIR . '/templates/template-one/functions.php';

			// Second Template Files Includes here
		}


		public function uamp_home_template(){
			return uamp_locate_template( 'index.php' );
		}

		public function uamp_single_template(){
			return uamp_locate_template( 'singular.php' );
		}

		/*
		 * Body Class
		 */
		public static function uamp_body_class( $class = '' ) {
			echo 'class="' . join( ' ', get_body_class( $class ) ) . '"';
		}


		public static function uamp_comment_link(){
			$comments_url = get_permalink() . '#respond';
			echo esc_url( $comments_url );
		}

		public static function uamp_comments_list( $args = array(), $comment_query_args = array() ) {

			global $wp_query;

			$post_id = get_the_ID();

			$comment_args = array(
				'orderby'       => 'comment_date_gmt',
				'order'         => 'ASC',
				'status'        => 'approve',
				'post_id'       => $post_id,
				'no_found_rows' => FALSE,
			);

			$comments = new WP_Comment_Query( array_merge( $comment_args, $comment_query_args ) );

			$comments_list = apply_filters( 'comments_array', $comments->comments, $post_id );

			$wp_query->comments = $comments_list;

			return wp_list_comments( $args );
		}



		/*
		 * Ultimate AMP Endpoint
		 */
		public function uamp_is_amp_endpoint(){
			if ( $this->uamp_is_non_amp() && ! is_admin()) {
				return $this->uamp_is_non_amp();
			}
			else {
				return false !== get_query_var( 'amp', false );
			}
		}

		/*
		 * Ultimat AMP Get Slug
		 */
		public static function amp_get_slug() {
			if ( defined( 'AMP_QUERY_VAR' ) ) {
				return AMP_QUERY_VAR;
			}

			$query_var = apply_filters( 'amp_query_var', 'amp' );

			define( 'AMP_QUERY_VAR', $query_var );

			return $query_var;
		}

		/*
		 * Ultimate AMP Old Slug to New Slug
		 */
		public static function uamp_old_slug_to_new_slug( $link ){

			if ( $this->uamp_is_amp_endpoint() ) {
				$link = trailingslashit( trailingslashit( $link ) . UAMP_QUERY_VAR );
			}

			return $link;
		}

		/*
		 * Ultimate AMP is non AMP Version
		 */
		public function uamp_is_non_amp( $type="" ) {
			$non_amp = false;
			if ( false !== get_query_var( 'amp', false ) ) {
				return false;
			}

			return $non_amp;
		}



		/**
		 * is_post_type_viewable() for older WordPress versions
		 */



		public function is_post_type_viewable( $post_type ) {
			if ( is_scalar( $post_type ) ) {
				$post_type = get_post_type_object( $post_type );
				if ( ! $post_type ) {
					return false;
				}
			}

			return $post_type->publicly_queryable || ( $post_type->_builtin && $post_type->public );
			}


		/**
		 * Check if AMP page loaded
		 * @return bool
		 */
		public function is_wp_amp() {
			$endpoint_opt = get_option( 'uamp_endpoint' );
			$endpoint     = ( $endpoint_opt ) ? $endpoint_opt : Ultimate_AMP::AMP_QUERY;

			if ( '' == get_option( 'permalink_structure' ) ) {
				parse_str( $_SERVER['QUERY_STRING'], $url );

				return isset( $url[ $endpoint ] );
			}

			$url_parts   = explode( '?', $_SERVER["REQUEST_URI"] );
			$query_parts = explode( '/', $url_parts[0] );

			$is_amp = ( in_array( $endpoint, $query_parts ) );

			return $is_amp;
		}


	}