<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/21/18
	 */


	spl_autoload_register( 'uamp_autoload_class' );


	function uamp_autoload_class( $class, $dir = null ) {


//		echo UAMP_PLUGIN_URL;

		if ( is_null( $dir ) )
			$dir = 'ultimate-amp';

		foreach ( scandir( $dir ) as $file ) {

			// directory?
			if ( is_dir( $dir.$file ) && substr( $file, 0, 1 ) !== '.' )
				autoload( $class, $dir.$file.'/' );

			// php file?
			if ( substr( $file, 0, 2 ) !== '._' && preg_match( "/.php$/i" , $file ) ) {

				// filename matches class?
				if ( str_replace( '.php', '', $file ) == $class || str_replace( '.class.php', '', $file ) == $class ) {

					include $dir . $file;
				}
			}
		}
	}
