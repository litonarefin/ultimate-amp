<?php
////AMP
//add_rewrite_endpoint( AMP_QUERY_VAR, EP_PERMALINK );
//add_post_type_support( 'post', AMP_QUERY_VAR );
//add_filter( 'request', 'amp_force_query_var_value' );
//add_action( 'wp', 'amp_maybe_add_actions' );
//

// Make sure the `amp` query var has an explicit value.
// Avoids issues when filtering the deprecated `query_string` hook.
function amp_force_query_var_value( $query_vars ) {
	if ( isset( $query_vars[ amp_get_slug() ] ) && '' === $query_vars[ amp_get_slug() ] ) {
		$query_vars[ amp_get_slug() ] = 1;
	}
	return $query_vars;
}

/**
 * Conditionally add AMP actions or render the 'paired mode' template(s).
 *
 * If the request is for an AMP page and this is in 'canonical mode,' redirect to the non-AMP page.
 * It won't need this plugin's template system, nor the frontend actions like the 'rel' link.
 *
 * @global WP_Query $wp_query
 * @since 0.2
 * @return void
 */
function amp_maybe_add_actions() {


	// Short-circuit when theme supports AMP, as everything is handled by AMP_Theme_Support.
	if ( current_theme_supports( 'amp' ) ) {
		return;
	}

	// The remaining logic here is for paired mode running in themes that don't support AMP, the template system in AMP<=0.6.
	global $wp_query;
	if ( ! ( is_singular() || $wp_query->is_posts_page ) || is_feed() ) {
		return;
	}

	$is_amp_endpoint = is_amp_endpoint();

	/**
	 * Queried post object.
	 *
	 * @var WP_Post $post
	 */
	$post = get_queried_object();
	if ( ! post_supports_amp( $post ) ) {
		if ( $is_amp_endpoint ) {
			wp_safe_redirect( get_permalink( $post->ID ), 302 ); // Temporary redirect because AMP may be supported in future.
			exit;
		}
		return;
	}

	if ( $is_amp_endpoint ) {
		amp_prepare_render();
	} else {
		amp_add_frontend_actions();
	}
}

/**
 * Fix up WP_Query for front page when amp query var is present.
 *
 * Normally the front page would not get served if a query var is present other than preview, page, paged, and cpage.
 *
 * @since 0.6
 * @see WP_Query::parse_query()
 * @link https://github.com/WordPress/wordpress-develop/blob/0baa8ae85c670d338e78e408f8d6e301c6410c86/src/wp-includes/class-wp-query.php#L951-L971
 *
 * @param WP_Query $query Query.
 */
function amp_correct_query_when_is_front_page( WP_Query $query ) {
	$is_front_page_query = (
		$query->is_main_query()
		&&
		$query->is_home()
		&&
		// Is AMP endpoint.
		false !== $query->get( amp_get_slug(), false )
		&&
		// Is query not yet fixed uo up to be front page.
		! $query->is_front_page()
		&&
		// Is showing pages on front.
		'page' === get_option( 'show_on_front' )
		&&
		// Has page on front set.
		get_option( 'page_on_front' )
		&&
		// See line in WP_Query::parse_query() at <https://github.com/WordPress/wordpress-develop/blob/0baa8ae/src/wp-includes/class-wp-query.php#L961>.
		0 === count( array_diff( array_keys( wp_parse_args( $query->query ) ), array( amp_get_slug(), 'preview', 'page', 'paged', 'cpage' ) ) )
	);
	if ( $is_front_page_query ) {
		$query->is_home     = false;
		$query->is_page     = true;
		$query->is_singular = true;
		$query->set( 'page_id', get_option( 'page_on_front' ) );
	}
}

/**
 * Whether this is in 'canonical mode.'
 *
 * Themes can register support for this with `add_theme_support( 'amp' )`.
 * Then, this will change the plugin from 'paired mode,' and it won't use its own templates.
 * Nor output frontend markup like the 'rel' link. If the theme registers support for AMP with:
 * `add_theme_support( 'amp', array( 'template_dir' => 'my-amp-templates' ) )`
 * it will retain 'paired mode.
 *
 * @return boolean Whether this is in AMP 'canonical mode'.
 */
function amp_is_canonical() {
	$support = get_theme_support( 'amp' );
	if ( true === $support ) {
		return true;
	}
	if ( is_array( $support ) ) {
		$args = array_shift( $support );
		if ( empty( $args['template_dir'] ) ) {
			return true;
		}
	}
	return false;
}

function amp_load_classes() {
	_deprecated_function( __FUNCTION__, '0.6' );
}

function amp_add_frontend_actions() {
	require_once AMP__DIR__ . 'includes/amp-frontend-actions.php';
}

function amp_add_post_template_actions() {
	require_once AMP__DIR__ . 'includes/amp-post-template-actions.php';

	require_once AMP__DIR__ . 'includes/amp-post-template-functions.php';
	amp_post_template_init_hooks();
}

function amp_prepare_render() {
	add_action( 'template_redirect', 'amp_render' );
}

/**
 * Render AMP for queried post.
 *
 * @since 0.1
 */
function amp_render() {
	// Note that queried object is used instead of the ID so that the_preview for the queried post can apply.
	$post = get_queried_object();
	if ( $post instanceof WP_Post ) {
		amp_render_post( $post );
		exit;
	}
}

/**
 * Render AMP post template.
 *
 * @since 0.5
 * @param WP_Post|int $post Post.
 * @global WP_Query $wp_query
 */
function amp_render_post( $post ) {
	global $wp_query;

	if ( ! ( $post instanceof WP_Post ) ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}
	}
	$post_id = $post->ID;

	/*
	 * If amp_render_post is called directly outside of the standard endpoint, is_amp_endpoint() will return false,
	 * which is not ideal for any code that expects to run in an AMP context.
	 * Let's force the value to be true while we render AMP.
	 */
	$was_set = isset( $wp_query->query_vars[ amp_get_slug() ] );
	if ( ! $was_set ) {
		$wp_query->query_vars[ amp_get_slug() ] = true;
	}

	if ( extension_loaded( 'newrelic' ) ) {
		newrelic_disable_autorum();
	}

	do_action( 'pre_amp_render_post', $post_id );

	amp_add_post_template_actions();
	$template = new AMP_Post_Template( $post );
	$template->load();

	if ( ! $was_set ) {
		unset( $wp_query->query_vars[ amp_get_slug() ] );
	}
}


function _amp_bootstrap_customizer() {
	add_action( 'after_setup_theme', 'amp_init_customizer', 12 );
}
add_action( 'plugins_loaded', '_amp_bootstrap_customizer', 9 ); // Should be hooked before priority 10 on 'plugins_loaded' to properly unhook core panels.


/**
 * Redirects the old AMP URL to the new AMP URL.
 * If post slug is updated the amp page with old post slug will be redirected to the updated url.
 *
 * @param  string $link New URL of the post.
 *
 * @return string $link URL to be redirected.
 */
function amp_redirect_old_slug_to_new_url( $link ) {

	if ( is_amp_endpoint() ) {
		$link = trailingslashit( trailingslashit( $link ) . amp_get_slug() );
	}

	return $link;
}
