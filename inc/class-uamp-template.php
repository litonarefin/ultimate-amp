<?php
// No Direct Access Sir
if ( ! defined( 'ABSPATH' ) ) { die(); }

require_once 'class-uamp-template-manager.php';
require_once 'class-uamp-sanitize.php';


class Ultimate_AMP_Template extends Ultimate_AMP_Abstract_Template {

	public function __construct( $options ) {
		if ( true === Ultimate_AMP()->is_amp() ) {
			remove_shortcode( 'gallery' );
			add_filter( 'comments_template', array ( $this, 'get_comments_template_path' ) );
			add_filter( 'term_link', array ( $this, 'update_meta_links' ) );
			add_filter( 'wp_setup_nav_menu_item', array ( $this, 'update_menu_item_url' ) );
			add_filter( 'get_pagenum_link', array ( $this, 'update_pagination_link' ) );
		}

		$this->properties   = array ();
		$this->options      = $options;
		$this->sanitizer    = new Ultimate_AMP_Sanitize( $this );
		$this->doc_title    = function_exists( 'wp_get_document_title' ) ? wp_get_document_title() : wp_title( '', false );
		$this->base_url     = home_url() . '/';
//		$this->blog_name    = $this->options->get( 'logo_text' );
//		$this->logo         = $this->options->get( 'logo' );
//		$this->default_logo = $this->options->get( 'default_logo' );
//		$this->favicon      = $this->get_favicon();

		add_action( 'amphtml_template_head', array ( $this, 'page_fonts' ) );
		add_action( 'amphtml_template_css', array ( $this, 'get_custom_css' ) );

		/*
		 * Add extra css
		 */
		add_action( 'amphtml_template_css', array ( $this, 'set_extra_css' ), 100 );
//
//		if ( $this->options->get( 'header_menu' ) ) {
//			$menu_handler = $this->get_menu_handler( $this->options->get( 'header_menu_type' ) );
//			$this->add_embedded_element( $menu_handler );
//		}
		add_action( 'amphtml_before_header', array ( $this, 'remove_term_link_filter' ) );
	}



	public function page_fonts() {
		$used_fonts = array ();

//		foreach ( $this->options->get_tabs()->get( 'appearance' )->get_font_fields( 'fonts' ) as $font ) {
//			$font_name = $this->options->get( $font['id'] );
//			if ( $font_name != $font['default'] && ! in_array( $font_name, $used_fonts ) ) {
//				$additional_styles = apply_filters( 'amphtml_font_styles', ':400,700,400italic,500,500italic' );
//				echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto' . $additional_styles . '">' . PHP_EOL;
//			}
//			$used_fonts[]               = $font_name;
//			$this->fonts[ 'id' ] = str_replace( '+', ' ', 'Roboto' );
//		}


//		$additional_styles = apply_filters( 'amphtml_font_styles', ':400,700,400italic,500,500italic' );
//		echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto' . $additional_styles . '">' . PHP_EOL;
//		$this->fonts[ 'id' ] = str_replace( '+', ' ', 'Roboto' );

	}

	public function remove_term_link_filter() {
		remove_filter( 'term_link', array ( $this, 'update_meta_links' ) );
	}
	public function update_meta_links( $termlink ) {
		return $this->get_amphtml_link( $termlink );
	}
	public function get_amphtml_link( $link, $id = '' ) {
		return $this->options->get_amphtml_link( $link, $id );
	}

	public function get_custom_css() {
//		$content_width      = absint( $this->options->get( 'content_width' ) );
		$content_width      = '600';
		$main_content_width = $content_width + 32;

		echo PHP_EOL . ".amphtml-title div, .footer .inner { max-width: {$content_width}px; margin: 0 auto;}" . "#main .inner { max-width: {$main_content_width}px; } " . $this->get_element_fonts() . $this->get_element_colors();
	}

	public function get_template_name( $element ) {
		$name = '';
		if ( $this->options->get( $element ) ) {
			switch ( $element ) {
				case false !== strpos( $element, '_ad_' ):
					$name = $this->set_ad_data( $element ) ? 'ad' : '';
					break;
				case false !== strpos( $element, 'custom_html' ):
					$name = $this->set_custom_html( $element ) ? 'custom_html' : '';
					break;
				default:
					$template_name = $this->options->get( $element, 'template_name' );
					$name          = $template_name ? $template_name : $element;
					break;
			}
		}

		return apply_filters( 'amphtml_template_name', $name, $element, $this );
	}


	public function load() {

		$social_share_script = array (
			'slug' => 'amp-social-share',
			'src'  => 'https://cdn.ampproject.org/v0/amp-social-share-0.1.js'
		);

		$social_like_script  = array (
			'slug' => 'amp-facebook-like',
			'src'  => 'https://cdn.ampproject.org/v0/amp-facebook-like-0.1.js'
		);


		$is_loaded = apply_filters( 'amphtml_template_head', false, $this );
//		print_r($is_loaded);

//		print_r(apply_filters( 'uamp_template_load', false, $this ));

		if ( $is_loaded ) {
			return $this;
		}

//		print_r('Liton 1');

		switch ( true ) {
			case is_front_page() && is_home():
				print_r('Liton Home');
//				$this->set_template_content( 'archive' );
//				$this->set_blocks( 'blog' );
//				$this->set_schema_metadata();
				break;
			case is_front_page():
				print_r('Arefin');
//				$this->set_template_content( 'single-content' );
//				$current_post_id = get_option( 'page_on_front' );
//				$this->set_post( $current_post_id );
//				$this->set_blocks( 'pages' );
//				if ( $this->options->get( 'page_social_share' ) ) {
//					$this->add_embedded_element( $social_share_script );
//				}
//				if ( $this->options->get( 'social_like_button' ) ) {
//					$this->add_embedded_element( $social_like_script );
//				}
				break;
			case is_home():
				print_r('Liton Arefin');
//				$this->set_template_content( 'archive' );
//				$this->set_blocks( 'blog' );
//				$this->set_schema_metadata();
				break;
//			case is_single():
//				$this->set_template_content( 'single-content' );
//				$current_post_id = get_the_ID();
//				$this->set_post( $current_post_id );
//				$this->set_blocks( 'posts' );
//				if ( $this->options->get( 'post_social_share' ) ) {
//					$this->add_embedded_element( $social_share_script );
//				}
//				if ( $this->options->get( 'social_like_button' ) ) {
//					$this->add_embedded_element( $social_like_script );
//				}
//				break;
//			case is_page():
//				$this->set_template_content( 'single-content' );
//				$current_post_id = get_the_ID();
//				$this->set_post( $current_post_id );
//				$this->set_blocks( 'pages' );
//				if ( $this->options->get( 'page_social_share' ) ) {
//					$this->add_embedded_element( $social_share_script );
//				}
//				if ( $this->options->get( 'social_like_button' ) ) {
//					$this->add_embedded_element( $social_like_script );
//				}
//				break;
//			case is_archive():
//				$this->set_template_content( 'archive' );
//				$this->set_blocks( 'archives' );
//				$this->title = get_the_archive_title();
//				$this->set_schema_metadata( get_the_archive_description() );
//				break;
			case is_404():
				print_r('404 Not Fount');
				$this->set_template_content( 'single-content' );
//				print_r($this->set_template_content( 'single-content' ));
				$this->set_blocks( '404' );
				break;
//			case is_search():
//				$this->set_template_content( 'archive' );
//				$this->set_blocks( 'search' );
//				$this->title = __( 'Search Results', 'amphtml' );
//				$this->set_schema_metadata();
//				break;
		}
	}



	public function the_template_content() {
		echo $this->render( $this->template_content );
	}

	public function set_template_content( $template ) {
		$this->template_content = $template;
		add_action( 'amphtml_template_content', array ( $this, 'the_template_content' ) );

		return $this;
	}

	public function set_blocks( $type, $default = true ) {
		$this->blocks = $this->get_template_elements( $type, $default );
	}

	public function get_blocks() {
		return $this->blocks;
	}

	public function get_permalink() {
		global $wp;

		return home_url( add_query_arg( array (), $wp->request ) );
	}

	public function set_schema_metadata( $post = null, $description = '' ) {
		global $wp_query, $wp;

		$metadata = array (
			'@context' => 'http://schema.org',
			'@type'    => 'CollectionPage',
			'headline' => $this->title,
			'url'      => home_url( add_query_arg( array (), $wp->request ) ),
		);

		if ( $description ) {
			$metadata['description'] = $description;
		}

		foreach ( $wp_query->posts as $post ) {
			$excerpt               = apply_filters( 'get_the_excerpt', $post->post_excerpt, $post );
			$metadata['hasPart'][] = $this->get_schema_metadata( $post, $excerpt );
		}

		$this->metadata = $metadata;
	}

	public function get_schema_metadata( $post, $description = '' ) {
		$author        = get_userdata( $post->post_author );
		$post_image_id = $this->get_post_image_id( $post->ID );
		$logo          = $this->default_logo;

		if ( empty( $logo ) ) {
			$logo = $this->logo;
		}

		$metadata = array (
			'@context'         => 'http://schema.org',
			'@type'            => apply_filters( 'amphtml_schema_type', 'WebPage', $this ),
			'headline'         => $this->get_title( $post->ID ),
			'url'              => get_permalink( $post->ID ),
			'datePublished'    => $post ? date( 'c', get_the_date( 'U', $post->ID ) ) : date( 'c' ),
			'dateModified'     => $post ? date( 'c', get_post_modified_time( 'U', false, $post ) ) : date( 'c' ),
			'mainEntityOfPage' => array (
				'@type' => 'WebPage',
				'@id'   => $post ? get_permalink( $post->ID ) : get_bloginfo( 'url' ),
			),
			'publisher'        => array (
				'@type' => 'Organization',
				'name'  => $this->blog_name,
			),
			'author'           => array (
				'@type' => 'Person',
				'name'  => $post ? $author->display_name : 'admin',
			),
			'image'            => $post ? $this->get_schema_images( $post_image_id ) : ''
		);

		if ( $description ) {
			$metadata['description'] = wp_strip_all_tags( $description );
		}

		if ( $logo ) {
			if ( $img_obj = json_decode( $logo ) ) {
				$metadata['publisher']['logo'] = array (
					'@type'  => 'ImageObject',
					'url'    => $img_obj->url,
					'height' => $img_obj->height,
					'width'  => $img_obj->width,
				);
			} else {
				$attachment_id = $this->get_attachment_id_from_url( $logo );
				$logo_arr      = wp_get_attachment_metadata( $attachment_id );
				if ( ! is_array( $logo_arr ) ) {
					$height = 60;
					$width  = 600;
				} else {
					$height = $logo_arr['height'];
					$width  = $logo_arr['width'];
				}
				$metadata['publisher']['logo'] = array (
					'@type'  => 'ImageObject',
					'url'    => $logo,
					'height' => $height,
					'width'  => $width,
				);
			}
		}

		return apply_filters( 'amphtml_metadata', $metadata, $post );
	}


	public function get_template_elements( $type, $return_default = true ) {
//
//		if ( false === ( $order = get_transient( 'amphtml_template_blocks_order' ) ) ) {
//			$order = get_option( 'amphtml_template_blocks_order' );
//			$order = maybe_unserialize( $order );
//			set_transient( 'amphtml_template_blocks_order', $order );
//		}
//
//		if ( isset( $order[ $type ] ) ) {
//			return $order[ $type ];
//		} else if ( $return_default ) {
//			return $this->type;
//		} else {
//			return false;
//		}

		return $this->type;
	}

	public function get_title( $id ) {
		return ( get_post_meta( $id, "amphtml-override-title", true ) ) ? get_post_meta( $id, "amphtml-custom-title", true ) : get_the_title( $id );
	}


	/**
	 * Get attachment ID from URL
	 *
	 * @global type $wpdb
	 *
	 * @param string $attachment_url
	 *
	 * @return int
	 */
	function get_attachment_id_from_url( $attachment_url = '' ) {
		global $wpdb;
		$attachment_id = false;
		if ( '' == $attachment_url ) {
			return;
		}
		$upload_dir_paths = wp_upload_dir();
		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
			$attachment_id  = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
		}

		return $attachment_id;
	}


	public function get_attachment_id_from_src( $image_src ) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";

		return $wpdb->get_var( $query );
	}

	public function get_post_image_id( $post_id ) {
		$post_image_id = $this->get_post_thumbnail_id( $post_id );

		if ( $post_image_id ) {
			return $post_image_id;
		}

		$image_ids = get_posts( array (
			'post_parent'      => $post_id,
			'post_type'        => 'attachment',
			'post_mime_type'   => 'image',
			'posts_per_page'   => 1,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'fields'           => 'ids',
			'suppress_filters' => false,
		) );

		if ( count( $image_ids ) ) {
			$post_image_id = current( $image_ids );
		} else {
			// default image
//			$logo = $this->options->get( 'default_image' );
			$logo = '';
			if ( $img_obj = json_decode( $logo ) ) {
				$post_image_id = $img_obj->id;
			} else {
				$post_image_id = $this->get_attachment_id_from_src( $logo );
			}
		}

		return $post_image_id;
	}

	public function get_post_thumbnail_id( $post_id ) {
		$thumbnail_id = get_post_meta( $post_id, 'amphtml_featured_image_id', true );

		if ( ! $thumbnail_id ) {
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		}

		return $thumbnail_id;
	}

	public function get_schema_images( $post_image_id ) {
		$post_image_src = wp_get_attachment_image_src( $post_image_id, 'full' );

		if ( is_array( $post_image_src ) ) {
			return array (
				'@type'  => 'ImageObject',
				'url'    => $post_image_src[0],
				'width'  => ( $post_image_src[1] > self::SCHEMA_IMG_MIN_WIDTH ) ? $post_image_src[1] : self::SCHEMA_IMG_MIN_WIDTH,
				'height' => $post_image_src[2],
			);
		}

		return array (
			'@type' => 'ImageObject'
		);
	}



}
