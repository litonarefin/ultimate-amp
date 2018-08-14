<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/8/18
	 */
	
//	require_once UAMP_DIR . '/lib/vendor/amp/includes/templates/class-amp-post-template.php';

	class Ultimate_Template_Loader extends AMP_Post_Template {

		public function __construct( $post ) {

			parent::__construct( $post );
			$this->template_dir = apply_filters( 'amp_post_template_dir', UAMP_DIR . '/templates/template-one' );

//			if ( Ultimate_AMP::is_home_static_page() ) {
//				$post_id = ampforwp_get_frontpage_id();
//			}


//			$this->ID = $post_id;


//			print_r($this->data['placeholder_image_url']);

//			print_r($this);

//			print_r($this->get_template_path('home'));

//			$this->include_file();
			$this->template_loader();
			add_theme_support( 'amp', array( 'template_dir' => 'templates/template-one' ) );

		}


		public function template_loader(){
			global $uamp_options;

			$is_loaded = $uamp_options['uamp_is_amp'];
//
//			require_once UAMP_DIR . '/inc/class-uamp-extend-lib-template-loader.php';
//
//			$template = new Ultimate_AMP_Load_Template_Files();
//
//			require_once UAMP_DIR . '/inc/class-uamp-autoload.php';
//			UltimateAmpAutoload::register();
//
//			print_r($template);
//
//			switch ( $is_loaded == "enable" ) {
//				case is_front_page() && is_home():
//					$template->get_template_part('front-page');
//					break;
//				case is_front_page():
//					$template->get_template_part('front-page');
//					break;
//				case is_home():
//					$template->get_template_part('home');
//					break;
//				case is_single():
//					$template->get_template_part('single');
//					break;
//				case is_page():
//					$template->get_template_part('page');
//					break;
//				case is_category():
//					$template->get_template_part('archive');
//					break;
////			case is_archive():
////				$template->get_template_part('archive');
////				break;
//				case is_404():
//					$template->get_template_part('404');
//					break;
//				case is_search():
//					$template->get_template_part('search');
//					break;
//			}
//





			switch ( $is_loaded == "enable" ) {

				case is_front_page() && is_home():
					$this->load_parts(array( 'front-page'));
					break;

				case is_front_page():
					$this->load_parts(array( 'front-page'));
					break;

				case is_home():
					$this->load_parts(array( 'home'));
					break;

				case is_single():
					$this->load_parts(array( 'single'));
					break;

				case is_page():
					$this->load_parts(array( 'page'));
					break;

				case is_category() || is_archive() || is_tax() || is_date():
					$this->load_parts(array( 'archive'));
					break;

				case is_404():
					$this->load_parts(array( '404'));
					break;

				case is_search():
					$this->load_parts(array( 'search'));
					break;

			}

		}





		function uamp_template_loader() {

			if (!$this->uamp_is_amp_endpoint()) {
				return;
			}


			$templates = new Ultimate_Template_Loader();

			if (function_exists('is_embed') && is_embed() && $template = better_amp_embed_template()) :
			elseif (function_exists('is_woocommerce') && is_woocommerce() && is_page(wc_get_page_id('shop')) && $template = $this->uamp_woocommerce_template()) :
			elseif (is_404() && $template = $this->uamp_404_template()) :
				//        elseif ( is_search() && $template = better_amp_search_template() ) :
			elseif (is_home() && $template = $this->uamp_home_template()) :
				//        elseif ( is_post_type_archink rel="canonical" href=ve() && $template = better_amp_post_type_archive_template() ) :
				//        elseif ( is_tax() && $template = better_amp_taxonomy_template() ) :
				//        elseif ( is_attachment() && $template = better_amp_attachment_template() ) :
				//			remove_filter( 'the_content', 'prepend_attachment' );
			elseif (is_single() && $template = $this->uamp_single_template()) :
			elseif (is_page() && $template = $this->uamp_page_template()) :
			elseif (is_singular() && $template = $this->uamp_single_template()) :
				//        elseif ( is_category() && $template = better_amp_category_template() ) :
				//        elseif ( is_tag() && $template = better_amp_tag_template() ) :
				//        elseif ( is_author() && $template = better_amp_author_template() ) :
				//        elseif ( is_date() && $template = better_amp_date_template() ) :
				//        elseif ( is_archive() && $template = better_amp_archive_template() ) :
				//        elseif ( is_paged() && $template = better_amp_paged_template() ) :
			else :
				$template = $this->better_amp_index_template();
			endif;

			return $template;

		}








		public static function uamp_is_blog(){
			$uamp_blog_details = "";
			$uamp_blog_details = $this->uamp_blog_details();

			return $uamp_blog_details ;
		}


		public function uamp_blog_details( $param = "" ) {

			global $uamp_options;
			$current_url = '';
			$output 	 = '';
			$slug 		 = '';
			$title 		 = '';
			$blog_id 	 = '';
			$current_url_in_pieces = array();
			if(is_home() && get_option('show_on_front') == 'page' ) {
				$current_url = home_url( $GLOBALS['wp']->request );
				$current_url_in_pieces = explode( '/', $current_url );
				$page_for_posts  =  get_option( 'page_for_posts' );
				if( $page_for_posts ){
					$post = get_post($page_for_posts);
					if ( $post ) {
						$slug = $post->post_name;
						$title = $post->post_title;
						$blog_id = $post->ID;
					}
					switch ($param) {
						case 'title':
							$output = $title;
							break;
						case 'name':
							$output = $slug;
							break;
						case 'id':
							$output = $blog_id;
							break;
						default:
							if( in_array( $slug , $current_url_in_pieces , true ) || get_query_var('page_id') == $blog_id ) {
								$output = true;
							}
							else
								$output = false;
							break;
					}
				}
				else
					$output = false;
			}
			return $output;
		}

	}