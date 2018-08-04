<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/7/18
	 */

//	namespace Uamp\inc;


class Uamp_Scripts extends WP_Scripts {

	public function __construct() {
	}

	public function add( $handle, $src, $deps = array(), $ver = FALSE, $args = NULL ) {

		if ( isset( $this->registered[ $handle ] ) ) {
			return FALSE;
		}

		$this->registered[ $handle ] = new _WP_Dependency( $handle, $src, $deps, '', $args );

		return TRUE;
	}

	public function all_deps( $handles, $recursion = FALSE, $group = FALSE ) {
		return WP_Dependencies::all_deps( $handles, $recursion, $group );
	}
}


add_filter( 'script_loader_tag', 'uamp_handle_scripts_tag_attrs', 99, 2 );

function uamp_handle_scripts_tag_attrs( $tag, $handle ) {

	$scripts = uamp_scripts();

	if ( isset( $scripts->registered[ $handle ] ) ) {

		$handle = esc_attr( $handle );

		$attrs = '';

		if ( substr( $handle, 0, 4 ) === 'amp-' ) {
			$attrs .= " custom-element=$handle";
		}

		$tag = str_replace( ' src=', "$attrs async src=", $tag );
	}

	return $tag;
}


add_filter( 'script_loader_src', 'uamp_handle_scripts_tag_src', 99, 2 );

/**
 * @param $src       The source of the enqueued script.
 * @param $handle    script's registered handle.
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function uamp_handle_scripts_tag_src( $src, $handle ) {

	$scripts = uamp_scripts();

	if ( isset( $scripts->registered[ $handle ] ) ) {
		$src = remove_query_arg( 'ver', $src );
	}

	return $src;
}
