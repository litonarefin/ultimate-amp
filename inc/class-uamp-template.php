<?php
// No Direct Access Sir
if ( ! defined( 'ABSPATH' ) ) { die(); }

require_once 'class-uamp-template-manager.php';
require_once 'class-uamp-template-loader.php';
require_once 'class-uamp-sanitize.php';
if ( ! empty( $active_plugins ) && in_array( 'amp/amp.php', $active_plugins ) ) {
	new AMP_Post_Template();
}
require_once UAMP_DIR . '/templates/template-one/functions.php';
include_once UAMP_DIR . '/lib/simple_html_dom.php';



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

		add_action( 'uamp_before_header', array ( $this, 'remove_term_link_filter' ) );
	}

	public function update_menu_item_url( $item ) {
		$avoid_amp_class = apply_filters( 'uamp_no_amp_menu_link', 'no-amp' );

		if ( 'custom' != $item->object && false === array_search( $avoid_amp_class, $item->classes ) ) {
			$id        = ( $item->type == 'taxonomy' ) ? '' : $item->object_id;
			$item->url = $this->get_uamp_link( $item->url, $id );
		}

		return $item;
	}



	public function remove_term_link_filter() {
		remove_filter( 'term_link', array ( $this, 'update_meta_links' ) );
	}

	public function update_meta_links( $termlink ) {
		return $this->get_uamp_link( $termlink );
	}
	public function get_uamp_link( $link, $id = '' ) {
		return '';
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

		return apply_filters( 'uamp_template_name', $name, $element, $this );
	}


	public function load() {
		global $post_id;

		$social_share_script = array (
			'slug' => 'amp-social-share',
			'src'  => 'https://cdn.ampproject.org/v0/amp-social-share-0.1.js'
		);
		$social_like_script  = array (
			'slug' => 'amp-facebook-like',
			'src'  => 'https://cdn.ampproject.org/v0/amp-facebook-like-0.1.js'
		);


		$is_loaded = apply_filters( 'uamp_template_head', false, $this );

		$template = new Ultimate_Template_Loader();


//		$active_plugins = (array) get_option( 'active_plugins', array() );
//		if ( !empty( $active_plugins ) && in_array( 'amp/amp.php', $active_plugins ) ) {

			// Ultimate AMP Autoload Class
			require_once UAMP_DIR . '/inc/class-uamp-autoload.php';
			UltimateAmpAutoload::register();

//
//		} else {
//			echo esc_html( 'Please Activate Default AMP for WordPress Plugin.', 'uamp' );
//		}


		if ( $is_loaded ) {
			return $template;
		}


		switch ( $is_loaded = true ) {

			case is_front_page() && is_home():
				$template->get_template_part('front-page');
				break;

			case is_front_page():
				$template->get_template_part('front-page');
				break;

			case is_home():
				$template->get_template_part('home');
				break;

			case is_single():
				$template->get_template_part('single');
				break;

			case is_page():
				$template->get_template_part('page');
				break;

			case is_category():
				$template->get_template_part('archive');
				break;

//			case is_archive():
//				$template->get_template_part('archive');
//				break;

			case is_404():
				$template->get_template_part('404');
				break;

			case is_search():
				$template->get_template_part('search');
				break;
		}
	}


	public function set_post( $id, $set_meta = true ) {
		// Image gallery just for single post
		add_shortcode( 'gallery', array ( $this, 'gallery_shortcode' ) );

		$this->post               = get_post( $id );
		$this->ID                 = $this->post->ID;
		$this->title              = $this->get_title( $this->ID );
		$this->publish_timestamp  = get_the_date( 'U', $this->ID );
		$this->modified_timestamp = get_post_modified_time( 'U', false, $this->post );
		$this->author             = get_userdata( $this->post->post_author );
		$this->content            = $this->get_content( $this->post );
		$this->content            = apply_filters( 'uamp_single_content', $this->content );
		$this->content            = $this->sanitizer->sanitize_content( $this->content );
		$this->content            = $this->multipage_content( $this->content );
		$this->featured_image     = $this->get_featured_image();

		if ( $set_meta ) {
			$this->metadata = $this->get_schema_metadata( $this->post, $this->get_post_excerpt_by_id( $id ) );
		}
	}



	public function get_content( $post ) {
		$content = $post->post_content;

		if ( get_post_meta( $post->ID, "uamp-override-content", true ) ) {
			$content = get_post_meta( $post->ID, "uamp-custom-content", true );
			remove_filter( 'the_content', 'siteorigin_panels_filter_content' );
		}
//		if ( $this->options->get( 'default_the_content' ) ) {
			$this->remove_custom_the_content_hooks();
//		}

		return apply_filters( 'the_content', $content );
	}


	public function remove_custom_the_content_hooks() {
		global $wp_filter;

		$hooks    = $wp_filter['the_content'];
		$defaults = $this->get_default_the_content_hooks();

		if ( class_exists( 'WP_Hook' ) ) {
			$hooks = $hooks->callbacks;
		}

		foreach ( $hooks as $priority => $functions ) {

			foreach ( $functions as $name => $function ) {

				$function_name = ( is_array( $function['function'] ) ) ? $function['function'][1] : $function['function'];

				if ( ! isset( $defaults[ $priority ] ) || ! in_array( $function_name, $defaults[ $priority ] ) ) {
					if ( isset( $wp_filter['the_content'] ) ) {
						if ( class_exists( 'WP_Hook' ) ) {
							unset( $wp_filter['the_content']->callbacks[ $priority ][ $name ] );
						} else {
							unset( $wp_filter['the_content'][ $priority ][ $name ] );
						}
					}
				}

			}

			if ( ! count( $wp_filter['the_content'][ $priority ] ) ) {
				unset( $wp_filter['the_content'][ $priority ] );
			}

		}
	}


	public function get_default_the_content_hooks() {
		return apply_filters( 'uamp_the_content', array (
			'11' => array ( 'capital_P_dangit', 'do_shortcode' ),
			'10' => array (
				'wptexturize',
				'convert_smilies',
				'wpautop',
				'shortcode_unautop',
				'prepend_attachment',
				'wp_make_content_images_responsive',
				'uamp_shortcode_fix',
				'uamp_content_ads',
			),
			'8'  => array ( 'run_shortcode', 'autoembed' ),
		) );
	}


	public function the_template_content() {
		echo $this->render( $this->template_content );

	}

	public function set_template_content( $template ) {

		require_once UAMP_DIR . '/inc/class-uamp-template-loader.php';
		$lit = new Ultimate_Template_Loader();

//		$this->template_content = $template;
		$this->template_content = $lit->get_template_part( $template );
//		print_r($this->template_content);

		add_action( 'uamp_template_content', array ( $this, 'the_template_content' ) );

		return $this;
	}

	public function set_blocks( $type, $default = true ) {


//		require_once UAMP_DIR . '/inc/class-uamp-template-loader.php';
//		$lit = new Ultimate_Template_Loader();

//		print_r( $lit->get_template_part('header'));
//		return $lit->get_template_part('header');
//		echo $lit->get_template_part('single');


		$this->blocks = $this->get_template_elements( $type, $default );
//		$this->blocks = $lit->get_template_part( $type );
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
			'@type'            => apply_filters( 'uamp_schema_type', 'WebPage', $this ),
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

		return apply_filters( 'uamp_metadata', $metadata, $post );
	}


	public function get_template_elements( $type, $return_default = true ) {
//
//		if ( false === ( $order = get_transient( 'uamp_template_blocks_order' ) ) ) {
//			$order = get_option( 'uamp_template_blocks_order' );
//			$order = maybe_unserialize( $order );
//			set_transient( 'uamp_template_blocks_order', $order );
//		}
//
//		if ( isset( $order[ $type ] ) ) {
//			return $order[ $type ];
//		} else if ( $return_default ) {
//			return $this->type;
//		} else {
//			return false;
//		}



		if ( $return_default ) {
			return $this->type;
		} else {
			return false;
		}
//		print_r('LI');

		return $this->type;
	}

	public function get_title( $id ) {
		return ( get_post_meta( $id, "uamp-override-title", true ) ) ? get_post_meta( $id, "uamp-custom-title", true ) : get_the_title( $id );
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
		$thumbnail_id = get_post_meta( $post_id, 'uamp_featured_image_id', true );

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
