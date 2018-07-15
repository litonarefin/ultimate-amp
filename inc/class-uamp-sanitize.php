<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! function_exists( 'file_get_html' ) ) {
	include_once AMPHTML()->get_amphtml_path() . '/vendor/simple_html_dom.php';
}
include_once AMPHTML()->get_amphtml_path() . '/vendor/Fastimage.php';

class AMPHTML_Sanitize {

	const YT_PATTERN = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
	const VIMEO_PATTERN = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/';
	const VINE_PATTERN = '/https?:\/\/?(www\.)?vine\.co\/.\/([\w\d]*)/';
	const SOUNDCLOUD_PATTERN = '/(\.soundcloud\.com\/).*\/(\d{8,})/';

	/**
	 * Current image element
	 * @var int
	 */
	static $img_el_position = 0;

	/**
	 * @var AMPHTML_Template
	 */
	public $template;

	/**
	 * @var simple_html_dom
	 */
	protected $html_dom;

	protected $content;

	public function __construct( $template ) {
		$this->template = $template;
		$this->html_dom = new simple_html_dom();
		$this->html_dom->set_callback( array ( $this, 'sanitize_elements' ) );
		$this->element_default_height = $this->template->get_option( 'element_height' );
		$this->element_default_width  = $this->template->get_option( 'content_width' );

		if ( AMPHTML()->is_amp() ) {
			add_filter( 'term_description', array ( $this, 'term_description' ) );
		}
	}

	/**
	 * Sanitize archives description
	 *
	 * @param string $description
	 *
	 * @return string
	 */
	public function term_description( $description ) {
		$description = $this->sanitize_content( $description );

		return do_shortcode( $description->save() );
	}

	public function sanitize_content( $content ) { //todo move all methods to sanitize element
		$this->load_content( $content )->sanitize_youtube()->sanitize_vimeo()->sanitize_vine()->sanitize_audio()->sanitize_soundcloud()->sanitize_iframe();

		return $this->get_content();
	}

	public function remove_inline_styles( $el ) {
		$el->style   = null;
		$el->onclick = null;
		$el->border  = null;
		$el->bgcolor = null;
		$el->color   = null;
		$el->size    = null;
		$el->summary = null;
		$el->setAttribute( 'xml:lang', null );
	}

	public function sanitize_elements( $el ) {
		$this->remove_inline_styles( $el );

		switch ( $el->tag ) {
			case 'a':
				$this->sanitize_a( $el );
				break;
			case 'img':
				$this->sanitize_image( $el );
				break;
			case 'form':
				$this->sanitize_form( $el );
				break;
			case 'video':
				$this->sanitize_video( $el );
				break;
			case 'blockquote':
				$this->sanitize_blockquote( $el );
				break;
			case 'div':
				$this->sanitize_div( $el );
				break;
			case 'amp-ad':
				$this->sanitize_ad( $el );
				break;
		}
	}

	public function sanitize_div( $div ) {
		return apply_filters( 'amphtml_sanitize_div', $div );
	}

	public function sanitize_ad( $ad ) {
		$this->template->add_embedded_element( array (
				'slug' => 'amp-ad',
				'src'  => 'https://cdn.ampproject.org/v0/amp-ad-0.1.js'
			) );
	}

	public function sanitize_iframe() {
		if ( ! $this->content->find( 'iframe' ) ) {
			return $this;
		}

		$this->template->add_embedded_element( array (
			'slug' => 'amp-iframe',
			'src'  => 'https://cdn.ampproject.org/v0/amp-iframe-0.1.js'
		) );

		$allowed_attributes = array (
			'width',
			'height',
			'frameborder',
			'src',
			'layout',
			'sandbox'
		);

		foreach ( $this->content->find( 'iframe' ) as $iframe ) {
			// facebook video
			if ( strpos( $iframe->src, 'facebook.com' ) !== false && strpos( $iframe->src, 'video' ) !== false ) {
				parse_str( $iframe->src, $params );
				$this->sanitize_facebook( $iframe, array_shift( $params ), 'video' );

			} // facebook post
			elseif ( strpos( $iframe->src, 'facebook.com' ) !== false ) {
				parse_str( $iframe->src, $params );
				$this->sanitize_facebook( $iframe, array_shift( $params ) );

			} // iframe
			else {
				$iframe            = $this->validate_attributes( $iframe, $allowed_attributes );
				$iframe->tag       = 'amp-iframe';
				$iframe->layout    = "responsive";
				$iframe->sandbox   = "allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox";
				$iframe->innertext = '<div placeholder="" class="amphtml-iframe-placeholder"></div>';
				$iframe->width     = $iframe->width ? $iframe->width : $this->element_default_width;
				$iframe->height    = $iframe->height ? $iframe->height : $this->element_default_height;
			}
		}

		return $this;
	}

	public function sanitize_audio() {
		if ( ! $this->content->find( 'audio' ) ) {
			return $this;
		}

		$this->template->add_embedded_element( array (
			'slug' => 'amp-audio',
			'src'  => 'https://cdn.ampproject.org/v0/amp-audio-0.1.js'
		) );

		foreach ( $this->content->find( 'audio' ) as $iframe ) {
			$iframe->tag = 'amp-audio';
			$this->validate_attributes( $iframe, array (
				'src',
				'autoplay',
				'controls',
				'loop',
				'class',
				'width',
				'height',
				'id'
			) );
		}

		return $this;
	}

	public function sanitize_video( $video ) {
		$video->tag    = 'amp-video';
		$video->layout = "responsive";
	}

	public function sanitize_image( $img ) { //todo refactoring
		$allowed_attributes = array ( 'src', 'alt', 'sizes', 'srcset', 'width', 'height', 'class', 'layout' );

		if ( $img->src ) {
			if ( ! $img->width || ! $img->height ) {
				$image = new FastImage( $img->src );
				if ( $image->getHandle() && $image->getSize() ) {
					list( $img->width, $img->height ) = $image->getSize();
				} else {
					// if allow_url_fopen = Off
					$img->width  = $this->template->get_option( 'content_width' );
					$img->height = $this->element_default_height;
					$img->class .= ' wp-amp-unknown-size';
				}
			}
		} else {
			$img->outertext = '';
		}

		$width = ( (int) $img->width > (int) $this->template->get_option( 'content_width' ) ) ? $this->template->get_option( 'content_width' ) : $img->width;

		$img->sizes = "(min-width: {$width}px) {$width}px,  calc(100vw - 32px)";

		if ( ! empty( $img->outertext ) ) {
			self::$img_el_position += 1;
			$img = $this->validate_attributes( $img, $allowed_attributes );
			if ( $this->is_gif_url( $img->src ) ) {
				$this->template->add_embedded_element( array (
						'slug' => 'amp-anim',
						'src'  => 'https://cdn.ampproject.org/v0/amp-anim-0.1.js'
					) );
				$img->tag = 'amp-anim';
			} else {
				$img->tag = 'amp-img';
			}
			$img->_[ HDOM_INFO_END ] = strlen( $img->tag );

			$img->outertext = str_replace( '/></amp-img>', '></amp-img>', $img->outertext );

			$img = apply_filters( 'amphtml_sanitize_image', $img, $this );
		}
	}

	/**
	 * Currently supports get action only
	 *
	 * @param element $form
	 */
	public function sanitize_form( $form ) {
		if ( $this->template->get_option( 'is_hidden_forms' ) ) {
			$form->outertext = $this->template->render('no-form');
		} else {
			$this->template->add_embedded_element( array (
				'slug' => 'amp-form',
				'src'  => 'https://cdn.ampproject.org/v0/amp-form-0.1.js'
			) );

			if ( $form->method !== 'post' ) {
				$form->action = $this->get_amp_action( $form->action );
			}

		}

		// check target
		if ( ! $form->target ) {
			$form->setAttribute( 'target', '_blank' );
		}
	}

	/**
	 * Sanitize action url for amp-form
	 *
	 * @global object $post
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	private function get_amp_action( $url ) {
		global $post;

		if ( empty( $url ) ) {
			// no action = submit on self
			return $this->get_amp_action( get_the_permalink( $post->ID ) );
		}

		if ( strpos( $url, 'https://' ) === 0 || strpos( $url, '//' ) === 0 ) {
			return $url;
		} else {
			if ( strpos( $url, 'http://' ) === 0 ) {
				return str_replace( 'http://', '//', $url );
			} else {
				return '//' . ltrim( $url, '/' );
			}
		}

		return $url;
	}

	public function sanitize_vimeo() {
		if ( ! $this->content->find( 'iframe' ) ) {
			return $this;
		}

		$allowed_attributes = array (
			'data-videoid',
			'layout',
			'width',
			'height'
		);

		foreach ( $this->content->find( 'iframe' ) as $iframe ) {
			if ( preg_match( self::VIMEO_PATTERN, $iframe->src, $match ) ) {

				$this->template->add_embedded_element( array (
					'slug' => 'amp-vimeo',
					'src'  => 'https://cdn.ampproject.org/v0/amp-vimeo-0.1.js'
				) );

				$iframe                   = $this->validate_attributes( $iframe, $allowed_attributes );
				$iframe->{'data-videoid'} = $match[5];
				$iframe->tag              = 'amp-vimeo';
				$iframe->layout           = "responsive";
			}
		}

		return $this;
	}

	public function sanitize_vine() {
		if ( ! $this->content->find( 'iframe' ) ) {
			return $this;
		}

		$allowed_attributes = array (
			'data-vineid',
			'layout',
			'width',
			'height'
		);

		foreach ( $this->content->find( 'iframe' ) as $iframe ) {
			if ( preg_match( self::VINE_PATTERN, $iframe->src, $match ) ) {

				$this->template->add_embedded_element( array (
					'slug' => 'amp-vine',
					'src'  => 'https://cdn.ampproject.org/v0/amp-vine-0.1.js'
				) );
				$iframe                  = $this->validate_attributes( $iframe, $allowed_attributes );
				$iframe->{'data-vineid'} = $match[2];
				$iframe->tag             = 'amp-vine';
				$iframe->layout          = "responsive";
				$iframe->width           = "1";
				$iframe->height          = "1";
			}
		}

		return $this;
	}

	public function sanitize_soundcloud() {
		if ( ! $this->content->find( 'iframe' ) ) {
			return $this;
		}

		$allowed_attributes = array (
			'data-trackid',
			'data-secret-token',
			'data-visual',
			'data-color',
			'layout',
			'width',
			'height'
		);

		foreach ( $this->content->find( 'iframe' ) as $iframe ) {
			if ( preg_match( self::SOUNDCLOUD_PATTERN, $iframe->src, $match ) ) {

				$this->template->add_embedded_element( array (
					'slug' => 'amp-soundcloud',
					'src'  => 'https://cdn.ampproject.org/v0/amp-soundcloud-0.1.js'
				) );

				$iframe                   = $this->validate_attributes( $iframe, $allowed_attributes );
				$iframe->tag              = 'amp-soundcloud';
				$iframe->{'data-trackid'} = $match[2];
				$iframe->{'data-visual'}  = 'true';
				$iframe->layout           = 'fixed-height';
				$iframe->height           = $this->element_default_height;
				$iframe->width            = null;
			}
		}

		return $this;
	}

	public function sanitize_youtube() {
		if ( ! $this->content->find( 'iframe' ) ) {
			return $this;
		}

		$allowed_attributes = array (
			'data-videoid',
			'width',
			'height'
		);


		foreach ( $this->content->find( 'iframe' ) as $iframe ) {
			if ( preg_match( self::YT_PATTERN, $iframe->src, $match ) ) {

				$this->template->add_embedded_element( array (
					'slug' => 'amp-youtube',
					'src'  => 'https://cdn.ampproject.org/v0/amp-youtube-0.1.js'
				) );

				$iframe                   = $this->validate_attributes( $iframe, $allowed_attributes );
				$iframe->{'data-videoid'} = $match[1];
				$iframe->tag              = 'amp-youtube';
				$iframe->layout           = "responsive";
			}
		}

		return $this;
	}

	public function load_content( $content ) {
		$this->content = $this->html_dom->load( $content );

		return $this;
	}

	public function get_content() {
		foreach ( $this->content->find( 'font' ) as $tag ) {
			$tag->outertext = $tag->innertext;
		}

		$illegal_tags = implode( ',', apply_filters( 'amphtml_illegal_tags', array ( 'script, noscript, style, link' ) ) );

		foreach ( $this->content->find( $illegal_tags ) as $tag ) {
			$tag->outertext = "";
		}

		return $this->content;
	}

	public function set_content( $content ) {
		$this->content = $content;
	}

	public function get_amp_images( $size ) {
		$this->template->add_embedded_element( array (
			'slug' => 'amp-carousel',
			'src'  => 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js'
		) );

		$images = '';
		foreach ( $this->content->find( 'img' ) as $img ) {
			$img->outertext = $this->template->render_element( 'carousel-image', array (
				$img->src,
				$size['width'],
				$size['height'],
				'alt' => $img->alt
			) );
			$img->class     = '';
			$images .= $img;
		}

		return $images;
	}

	public function get_dom_model() {
		return new simple_html_dom();
	}

	protected function validate_attributes( $element, $allowed_attributes ) {
		foreach ( $element->attr as $attr => $value ) {

			//unset disallowed attributes
			if ( ! in_array( $attr, $allowed_attributes ) ) {
				$element->attr[ $attr ] = null;
			}
			//convert relative width and height to absolute values
			if ( in_array( $attr, array ( 'width', 'height' ) ) && false !== strpos( $value, '%' ) ) {
				$value                  = (int) rtrim( $value, '%' );
				$element->attr[ $attr ] = ( $value / 100 ) * $this->element_default_width;
			}
			//check if frameborder is allowed
			if ( in_array( 'frameborder', $allowed_attributes ) ) {
				if ( 'frameborder' == $attr ) {
					//convert frameborder string value to int
					$element->attr[ $attr ] = ( 'yes' === $value || 1 === ( int ) $value ) ? 1 : 0;
				}
			}
		}

		return $element;
	}

	public function sanitize_a( $a ) {
		$a->rev = null;

		if ( strpos( $a->href, 'javascript:' ) !== false ) {
			$a->href = '#';
		}

		if ( $a->target && '_blank' === $a->target || '_new' === $a->target ) {
			$a->target = '_blank';
		} else {
			$a->target = null;
		}
	}

	private function is_gif_url( $url ) {
		$ext  = '.gif';
		$path = parse_url( $url, PHP_URL_PATH );

		return $ext === substr( $path, - strlen( $ext ) );
	}

	protected function sanitize_twitter( $el ) {
		$this->template->add_embedded_element( array (
				'slug' => 'amp-twitter',
				'src'  => 'https://cdn.ampproject.org/v0/amp-twitter-0.1.js'
			) );

		foreach ( $el->find( 'a' ) as $link ) {
			if ( strpos( $link->href, 'status' ) ) {
				$url_parts     = explode( '/', $link->href );
				$el->outertext = sprintf( "<amp-twitter width='1' height='1' layout='responsive' data-tweetid=%s></amp-twitter>", end( $url_parts ) );
			}
		}
	}

	/**
	 * Instagram media sanitize,
	 *
	 * @param object $el
	 */
	protected function sanitize_instagram( $el ) {
		$this->template->add_embedded_element( array (
				'slug' => 'amp-instagram',
				'src'  => 'https://cdn.ampproject.org/v0/amp-instagram-0.1.js'
			) );

		if( ! empty( $el->attr['data-instgrm-permalink'] ) ){
			$url_parts = explode( '/', untrailingslashit( $el->attr['data-instgrm-permalink'] ) );
		} else {
			foreach ( $el->find( 'a' ) as $link ) {
				if( ! empty( $link->href ) ) {
					$url_parts = explode( '/', untrailingslashit( $link->href ) );
					break;
				}
			}
		}

		if( ! empty( $url_parts ) ) {
			$el->outertext = sprintf( "<amp-instagram width='400' height='400' layout='responsive' data-shortcode='%s'></amp-instagram>", end( $url_parts ) );
		}
	}

	/**
	 * Facebook video and post sanitize,
	 *
	 * @param object $el
	 */
	public function sanitize_facebook( $el, $src = false, $type = false ) {
		$this->template->add_embedded_element( array (
				'slug' => 'amp-facebook',
				'src'  => 'https://cdn.ampproject.org/v0/amp-facebook-0.1.js'
			) );

		if ( $src ) {
			if ( $type == 'video' ) {
				$el->outertext = sprintf( "<amp-facebook height='400' width='400' layout='responsive' data-embed-as='video' data-href=%s></amp-facebook>", $src );
			} else {
				$el->outertext = sprintf( "<amp-facebook height='400' width='400' layout='responsive' data-href=%s></amp-facebook>", $src );
			}

			return;
		}

		// for link
		$class = $el->parent()->class;

		if ( $class === "fb-post" ) {
			$link          = $el->cite;
			$el->outertext = sprintf( "<amp-facebook height='400' width='400' layout='responsive' data-href=%s></amp-facebook>", $link );
		} elseif ( $class === "fb-video" ) {
			$anchors       = $el->find( 'a' );
			$anchor        = array_shift( $anchors );
			$link          = $anchor->href;
			$el->outertext = sprintf( "<amp-facebook height='400' width='400' layout='responsive' data-embed-as='video' data-href=%s></amp-facebook>", $link );
		}
	}

	public function sanitize_blockquote( $el ) {
		switch ( $el->class ) {
			case 'twitter-tweet':
				$this->sanitize_twitter( $el );
				break;
			case 'instagram-media':
				$this->sanitize_instagram( $el );
				break;
			case 'fb-xfbml-parse-ignore':
				$this->sanitize_facebook( $el );
				break;
		}
	}

}