<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 8/11/18
	 */


	class Ultimate_AMP_Load_Template_Files extends Template_Loader {

		protected $filter_prefix = 'uamp';
		public $uamp_options;
		protected $theme_template_directory = 'templates/template-one';
		protected $plugin_directory = UAMP_DIR;
		protected $plugin_template_directory = 'templates/template-one';
	}