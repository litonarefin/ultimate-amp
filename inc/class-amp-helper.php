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
			$endpoint     = ( $endpoint_opt ) ? $endpoint_opt : AMPHTML::AMP_QUERY;

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