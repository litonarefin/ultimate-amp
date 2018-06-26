<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/26/18
	 */

//	namespace Uamp\templates;


	class TemplateManager{
		const SITE_ICON_SIZE = 32;
		const CONTENT_MAX_WIDTH = 600;
		const DEFAULT_NAVBAR_BACKGROUND = '#0a89c0';

		private $template_dir;
		private $data;
		public $ID;
		public $post;

		public function __construct( $post ) {
//			$this->template_dir = apply_filters( 'amp_post_template_dir', UAMP_DIR . '/templates/template-one' );
			add_filter( 'amp_post_template_dir', UAMP_DIR . '/templates/template-one', 100 );

			add_action('pre_amp_render_post', array( $this, 'uamp_template_design'), 12 );
		}

		public function uamp_template_design(){
			return "Liton Arefin";
		}

		/**
		 * Load and print the template parts for the given post.
		 */
		public function load() {
			global $wp_query;
			$template = is_page() || $wp_query->is_posts_page ? 'page' : 'single';
			$this->load_parts( array( $template ) );
		}

		/**
		 * Load template parts.
		 *
		 * @param string[] $templates Templates.
		 */
		public function load_parts( $templates ) {
			foreach ( $templates as $template ) {
				$file = $this->get_template_path( $template );
				$this->verify_and_include( $file, $template );
			}
		}

		/**
		 * Get template path.
		 *
		 * @param string $template Template name.
		 * @return string Template path.
		 */
		private function get_template_path( $template ) {
			return sprintf( '%s/%s.php', $this->    template_dir, $template );
		}

	}