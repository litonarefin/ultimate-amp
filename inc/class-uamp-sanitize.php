<?php

	// No Direct Access Sir
	if ( ! defined( 'ABSPATH' ) ) { die(); }


	if ( ! function_exists( 'file_get_html' ) ) {
		include_once Ultimate_AMP()->plugin_path() . '/lib/simple_html_dom.php';
	}

	include_once Ultimate_AMP()->plugin_path() . '/lib/Fastimage.php';

	class Ultimate_AMP_Sanitize {

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
		 * @var Ultimate_AMP_Template
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
//			$this->element_default_height = $this->template->get_option( 'element_height' );
//			$this->element_default_width  = $this->template->get_option( 'content_width' );

			if ( Ultimate_AMP()->is_amp() ) {
				add_filter( 'term_description', array ( $this, 'term_description' ) );
			}
		}

	}
