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

class Ultimate_AMP_Shortcode {

	protected $template;

	protected $available_ads = array ( 'adsense', 'doubleclick' );

	public function __construct( $template = null ) {
		$this->template = $template;
		add_shortcode( 'wp-amp-ad', array ( $this, 'ad' ) );
		add_shortcode( 'wp-amp-related', array ( $this, 'related' ) );
		add_shortcode( 'wp-amp-recent', array ( $this, 'recent' ) );
		add_shortcode( 'wp-amp-share', array ( $this, 'share' ) );
		add_shortcode( 'wp-amp-switcher', array ( $this, 'do_switch' ) );
		add_shortcode( 'wp-amp-accordion', array ( $this, 'accordion' ) );
		add_shortcode( 'wp-amp-accordion-section', array ( $this, 'accordion_section' ) );
		add_filter( 'the_content', array ( $this, 'uamp_shortcode_fix' ) );
	}

	public function uamp_shortcode_fix( $content ) {
		// array of custom shortcodes requiring the fix
		$block = join( "|", array ( "wp-amp-accordion", "wp-amp-accordion-section" ) );
		// opening tag
		$rep = preg_replace( "/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content );
		// closing tag
		$rep = preg_replace( "/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep );

		return $rep;
	}

	public function ad( $atts ) {
		if ( $this->template === null ) {
			return '';
		}

		$options = shortcode_atts( array (
			'type'           => false,
			'width'          => 150,
			'height'         => 50,
			'layout'         => 'fixed',
			'data-slot'      => false,
			'data-ad-client' => false,
			'data-ad-slot'   => false
		), $atts );

		$this->template->shortcode_atts = $options;
		if ( in_array( $options['type'], $this->available_ads ) && ( ( $options['data-ad-client'] && $options['data-ad-slot'] ) || $options['data-slot'] ) ) {
			return $this->template->render( 'ad-shortcode' );
		}

		return '';
	}

	public function related( $atts ) {
		if ( $this->template === null ) {
			return '';
		}

		$this->template->related_atts = shortcode_atts( array (
			'title' => __( 'You May Also Like', 'uamp' ),
			'count' => 3,
		), $atts );

		return $this->template->render( 'related-shortcode' );
	}

	public function recent( $atts ) {
		if ( $this->template === null ) {
			return '';
		}

		$this->template->recent_atts = shortcode_atts( array (
			'title' => __( 'Latest blog posts', 'uamp' ),
			'count' => 3,
		), $atts );

		return $this->template->render( 'recent-shortcode' );
	}

	public function share( $atts ) {
		if ( $this->template === null ) {
			return '';
		}
		// prevent script embed on archive pages
		if ( ! is_single() && ! is_page() ) {
			return '';
		}

		$available_types = array ( 'facebook', 'twitter', 'pinterest', 'linkedin', 'gplus', 'email', 'whatsapp', );

		if ( isset( $atts['types'] ) ) {
			$atts['types'] = $atts_back = explode( ',', $atts['types'] );
			// sanitize atts
			$atts['types'] = array_intersect( $available_types, $atts['types'] );
			// sort atts by user order
			$atts['types'] = array_intersect( $atts_back, $atts['types'] );
		}

		$this->template->share_atts = shortcode_atts( array (
			'types'  => array ( 'facebook', 'twitter', 'linkedin', 'email' ),
			'width'  => '60',
			'height' => '44'
		), $atts );

		$this->template->add_embedded_element( array (
			'slug' => 'amp-social-share',
			'src'  => 'https://cdn.ampproject.org/v0/amp-social-share-0.1.js'
		) );

		return $this->template->render( 'social-share-shortcode' );
	}

	// [wp-amp-switcher title='Switch default version'][/wp-amp-switcher]
	public function do_switch( $atts ) {
		if ( $this->template === null ) {
			return '';
		}

		$atts = shortcode_atts( array (
			'title' => __( 'Switch to default version', 'uamp' )
		), $atts );

		return sprintf( "<a href=%s>%s</a>", $this->template->get_canonical_url(), $atts['title'] );
	}

	public function accordion( $atts = array (), $content = null ) {
		// add accordion script
		$this->template->add_embedded_element( array (
			'slug' => 'amp-accordion',
			'src'  => 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js'
		) );
		// begin accordion markup
		$_content = '<amp-accordion disable-session-states>';
		// run shortcode parser recursively
		$_content .= do_shortcode( trim( $content ) );
		// end accordion markup
		$_content .= '</amp-accordion>';

		return $_content;
	}

	public function accordion_section( $atts = array (), $content = null ) {
		// attributes
		$atts = shortcode_atts( array (
			'expanded' => '',
		), $atts );
		// begin section markup
		$_content = '<section ' . $atts['expanded'] . '>';
		// run shortcode parser recursively
		$_content .= do_shortcode( trim( $content ) );
		// end section markup
		$_content .= '</section>';

		return $_content;
	}

}