<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/14/18
	 */

	/*
	 * Check if WordPress AMP
	 */
	if(function_exists('uamp_is_wp_amp')){
		function uamp_is_amp(){
			global $uamp_options;
			$endpoint_opt = $uamp_options['uamp_'];
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