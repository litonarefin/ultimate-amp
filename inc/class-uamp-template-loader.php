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

			if ( Ultimate_AMP::is_home_static_page() ) {
				$post_id = ampforwp_get_frontpage_id();
			}
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



//			return $template;

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