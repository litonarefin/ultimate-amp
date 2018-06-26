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



	}