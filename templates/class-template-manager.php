<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/26/18
	 */

//	namespace Uamp\templates;

	class TemplateManager{
		const SITE_ICON_SIZE = 32;
		const CONTENT_MAX_WIDTH = 600;
		const DEFAULT_NAVBAR_BACKGROUND = '#0a89c0';

		private $template_dir;
		private $data;
		public $ID;
		public $post;

		// Construct
		public function __construct() {
			add_action( 'template_include', array( $this, 'uamp_include_template_file' ), 9999 );
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

			$uamp = new AMP_Post_Template($post_id);

			// Default Template One Includes
//			foreach (glob( UAMP_DIR . '/templates/template-one/*.php') as $filename) {
//				require_once $filename;
//			}

			require_once UAMP_DIR . '/templates/template-one/functions.php';
			require_once UAMP_DIR . '/templates/template-one/index.php';
			require_once UAMP_DIR . '/templates/template-one/home.php';

			if( is_home() || is_front_page()){
//				require_once UAMP_DIR . '/templates/template-one/functions.php';
				require_once UAMP_DIR . '/templates/template-one/home.php';
			}


			// Second Template Files Includes here
		}


		public function template_loader(){
		 	if ( is_singular() && $template = $this->uamp_single_template() )

		 	return $template;
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


//		public function replace_internal_links_with_amp_version( $wp ) {
//
//			if ( empty( $wp->query_vars['amp'] ) ) {
//				return;
//			}
//
//			add_filter( 'nav_menu_link_attributes', array( 'Better_AMP_Content_Sanitizer', 'replace_href_with_amp' ) );
//			add_filter( 'the_content', array( 'Better_AMP_Content_Sanitizer', 'transform_all_links_to_amp' ) );
//
//			add_filter( 'author_link', array( 'Better_AMP_Content_Sanitizer', 'transform_to_amp_url' ) );
//			add_filter( 'term_link', array( 'Better_AMP_Content_Sanitizer', 'transform_to_amp_url' ) );
//
//			add_filter( 'post_link', array( $this, 'transform_post_link_to_amp' ), 20, 2 );
//			add_filter( 'page_link', array( $this, 'transform_post_link_to_amp' ), 20, 2 );
//			add_filter( 'attachment_link', array( 'Better_AMP_Content_Sanitizer', 'transform_to_amp_url' ) );
//			add_filter( 'post_type_link', array( 'Better_AMP_Content_Sanitizer', 'transform_to_amp_url' ) );
//
//		}

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



	}
