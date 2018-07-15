<?php

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/16/18
	 */


	// If this file is called directly, abort.
	if ( ! defined( 'ABSPATH' ) ) {
		die();
	}

	function uamp_mobile(){
		global $uamp_options;
		echo $uamp_options['uamp_twitter'];

		return "Arefin";
	}



class Ultimate_AMP_Options {

	static $uamp_options = array ();
	static $fields = array ();

	public function get( $id, $attr = '' ) {

		if ( ! isset( self::$fields[ $id ] ) ) {
			return '';
		}
		if ( $attr ) {
			return isset( self::$fields[ $id ][ $attr ] ) ? self::$fields[ $id ][ $attr ] : '';
		}

		return self::$fields[ $id ]['value'];
	}

	public function get_options() {
		return self::$uamp_options;
	}


}
