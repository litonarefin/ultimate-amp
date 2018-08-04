<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/8/18
	 */
	
	require_once UAMP_DIR . '/lib/class-uamp-template-loader.php';

	class Ultimate_Template_Loader extends Template_Loader {

		/**
		 * Prefix for filter names.
		 *
		 * @since 1.0.0
		 * @type string
		 */
		protected $filter_prefix = 'uamp';

		public $uamp_options;

		/**
		 * Directory name where custom templates for this plugin should be found in the theme.
		 *
		 * @since 1.0.0
		 * @type string
		 */
		protected $theme_template_directory = 'templates/template-one';

		/**
		 * Reference to the root directory path of this plugin.
		 *
		 * @since 1.0.0
		 * @type string
		 */
		protected $plugin_directory = UAMP_DIR;

		/**
		 * Directory name where templates are found in this plugin.
		 *
		 * Can either be a defined constant, or a relative reference from where the subclass lives.
		 *
		 * e.g. 'templates' or 'includes/templates', etc.
		 *
		 * @since 1.1.0
		 *
		 * @var string
		 */
		protected $plugin_template_directory = 'templates/template-one';

	}