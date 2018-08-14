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




		/*
		 * Body Class
		 */
		public static function uamp_body_class( $class = '' ) {
			echo 'class="' . join( ' ', get_body_class( $class ) ) . '"';
		}


		// Get Comment Link
		public static function uamp_comment_link(){
			global $uamp_options;
			$uamp_is_amp = $uamp_options['uamp_is_amp'];
			$url = AMP_Theme_Support::get_current_canonical_url();

			if ( $uamp_is_amp == "enable" ) {
				$url = add_query_arg( array (
					'desktop-redirect' => '1',
				), $url );
			}

			$comments_url = $url . '#respond';
			echo esc_url( $comments_url );
		}


		// Comments List
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





	}