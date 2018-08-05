<?php
/**
 * Class AMP_Vimeo_Embed_Handler
 *
 * @package AMP
 */

/**
 * Class AMP_Vimeo_Embed_Handler
 *
 * Much of this class is borrowed from Jetpack embeds
 */
class AMP_Vimeo_Embed_Handler extends AMP_Base_Embed_Handler {

	const URL_PATTERN = '#https?:\/\/(www\.)?vimeo\.com\/.*#i';

	const RATIO = 0.5625;

	protected $DEFAULT_WIDTH = 600;
	protected $DEFAULT_HEIGHT = 338;

	function __construct( $args = array() ) {
		parent::__construct( $args );

		if ( isset( $this->args['content_max_width'] ) ) {
			$max_width = $this->args['content_max_width'];
			$this->args['width'] = $max_width;
			$this->args['height'] = round( $max_width * self::RATIO );
		}
	}

	function register_embed() {
		wp_embed_register_handler( 'amp-vimeo', self::URL_PATTERN, array( $this, 'oembed' ), -1 );
		add_shortcode( 'vimeo', array( $this, 'shortcode' ) );
		add_filter( 'wp_video_shortcode_override', array( $this, 'video_override' ), 10, 2 );
	}

	public function unregister_embed() {
		wp_embed_unregister_handler( 'amp-vimeo', -1 );
		remove_shortcode( 'vimeo' );
	}

	public function shortcode( $attr ) {
		$video_id = false;

		if ( isset( $attr['id'] ) ) {
			$video_id = $attr['id'];
		} elseif ( isset( $attr['url'] ) ) {
			$video_id = $this->get_video_id_from_url($attr['url']);
		}elseif ( isset( $attr[0] ) ) {
			$video_id = $this->get_video_id_from_url($attr[0]);
		} elseif ( function_exists( 'shortcode_new_to_old_params' ) ) {
			$video_id = shortcode_new_to_old_params( $attr );
		}

		if ( empty( $video_id ) ) {
			return '';
		}

		return $this->render( array(
			'video_id' => $video_id,
		) );
	}

	public function oembed( $matches, $attr, $url, $rawattr ) {
		$video_id = $this->get_video_id_from_url( $url );

		return $this->render( array(
			'url' => $url,
			'video_id' => $video_id,
		) );
	}

	public function render( $args ) {
		$args = wp_parse_args( $args, array(
			'video_id' => false,
		) );

		if ( empty( $args['video_id'] ) ) {
			return AMP_HTML_Utils::build_tag( 'a', array( 'href' => esc_url( $args['url'] ), 'class' => 'amp-wp-embed-fallback' ), esc_html( $args['url'] ) );
		}

		$this->did_convert_elements = true;

		return AMP_HTML_Utils::build_tag(
			'amp-vimeo',
			array(
				'data-videoid' => $args['video_id'],
				'layout' => 'responsive',
				'width' => $this->args['width'],
				'height' => $this->args['height'],
			)
		);
	}

	// get_video_id_from_url()
	// Takes the last component of a Vimeo URL
	// and returns it as the associated video id
	private function get_video_id_from_url( $url ) {
		$parsed_url = parse_url( $url );
		parse_str( $parsed_url['path'], $path );

		$video_id = "";
		if ( $path ) {
			$tok = explode( '/', $parsed_url['path'] );
			$video_id = end($tok);
		}

		return $video_id;
	}

	/**
	 * Override the output of Vimeo videos.
	 *
	 * This overrides the value in wp_video_shortcode().
	 * The pattern matching is copied from WP_Widget_Media_Video::render().
	 *
	 * @param string $html Empty variable to be replaced with shortcode markup.
	 * @param array  $attr The shortcode attributes.
	 * @return string|null $markup The markup to output.
	 */
	public function video_override( $html, $attr ) {
		if ( ! isset( $attr['src'] ) ) {
			return $html;
		}
		$src           = $attr['src'];
		$vimeo_pattern = '#^https?://(.+\.)?vimeo\.com/.*#';
		if ( 1 === preg_match( $vimeo_pattern, $src ) ) {
			return $this->shortcode( array( $src ) );
		}
		return $html;
	}

}
