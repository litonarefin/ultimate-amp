<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/21/18
	 */

//	namespace Uamp\inc\admin;

//	use Uamp\inc\admin\options\Header;

	if ( ! is_admin() ) {
		return;
	}

if ( ! class_exists( 'UampAdminOptions' ) ) {

	class UampAdminOptions {

		public $args = [];
		public $sections = [];
		public $plugin;
		public $ReduxFramework;

		public function __construct() {
			add_action('plugins_loaded', [$this, 'initSettings'], 10);

		}


		/*
		 * Initialize Settings
		 */
		public function initSettings() {

			// Set the default arguments
			$this->setArguments();

			// Create the sections and fields
			$this->setSections();

			if (!isset($this->args['opt_name'])) { // No errors please
				return;
			}

			$this->ReduxFramework = new ReduxFramework($this->sections, $this->args);

		}

		public function setSections() {
			global $sections;
			$sections = [];

			// ACTUAL DECLARATION OF SECTIONS
//			$this->uamp_admin_options_includes();

			require dirname(__FILE__) . '/options/global.php';
			require dirname(__FILE__) . '/options/Header.php';

//			new Uamp_Header();

			$this->sections = $sections;
		}


		public function setArguments() {
			$this->args = [
				// TYPICAL -> Change these values as you need/desire
				'opt_name' => 'uamp_options',
				// This is where your data is stored in the database and also becomes your global variable name.
				'display_name' => 'Ultimate AMP',
				// Name that appears at the top of your panel
				'display_version' => '1.0.0',
				// Version that appears at the top of your panel
				'menu_type' => 'menu',
				//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu' => true,
				// Show the sections below the admin menu item or not
				'menu_title' => esc_html__('Ultimate AMP', 'uamp'),
				'page_title' => esc_html__('Ultimate AMP', 'uamp'),
				// You will need to generate a Google API key to use this feature.
				// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
				'google_api_key' => 'AIzaSyASx0fO5qLJxb3BXlZbec1CVVZomgPQ37s',
				// Must be defined to add google fonts to the typography module
				'update_notice'         => false,
				'intro_text'            => $uamp_pro,
				'async_typography' 		=> false,
				'show_options_object' 	=> false,
				// Use a asynchronous font on the front end or font string
				'admin_bar' 			=> false,
				// Show the panel pages on the admin bar
				'global_variable' 		=> '',
				// Set a different name for your global variable other than the opt_name
				'dev_mode' 				=> false,
				// Show the time the page took to load, etc
				'customizer' 			=> false,
				'forced_dev_mode_off' 	=> true,
				'disable_save_warn'     => true,
				// Enable basic customizer support

				// OPTIONAL -> Give you extra features
				'page_priority' 		=> null,
				// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
				'page_parent' 			=> 'themes.php',
				// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
				'page_permissions' 		=> 'manage_options',
				// Permissions needed to access the options panel.
				'menu_icon' 			=> 'dashicons-admin-generic',
				// Specify a custom URL to an icon
				'last_tab' 				=> '',
				// Force your panel to always open to a specific tab (by id)
				'page_icon' 			=> 'icon-themes',
				// Icon displayed in the admin panel next to your menu_title
				'page_slug' 			=> 'uamp_options',
				// Page slug used to denote the panel
				'save_defaults' 		=> true,
				// On load save the defaults to DB before user clicks save or not
				'default_show' 			=> false,
				// If true, shows the default value next to each field that is not the default value.
				'default_mark' 			=> '',
				// What to print by the field's title if the value shown is default. Suggested: *
				'show_import_export' 	=> true,
				// Shows the Import/Export panel when not used as a field.

				// CAREFUL -> These options are for advanced use only
				'transient_time' 		=> 60 * MINUTE_IN_SECONDS,
				'output' 				=> false,
				// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
				'output_tag' 			=> false,
				'open_expanded' 		=> false,
				// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
				'footer_credit'     	=> false,                   // Disable the footer credit of Redux. Please leave if you can help it.
				'footer_text'			=> '',

				// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
				'database' 				=> '',
				// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
				'system_info' 			=> false,
				// REMOVE

				// HINTS
				'hints' => [
					'icon' 			=> 'icon-question-sign',
					'icon_position' => 'right',
					'icon_color' 	=> 'lightgray',
					'icon_size' 	=> 'normal',
					'tip_style' 	=> [
								'color' 	=> 'light',
								'shadow' 	=> true,
								'rounded' 	=> false,
								'style' 	=> '',
							],
					'tip_position' 	=> [
								'my' 		=> 'top left',
								'at' 		=> 'bottom right',
					],
					'tip_effect' 	=> [
							'show' 	=> [
								'effect' 	=> 'slide',
								'duration' 	=> '500',
								'event' 	=> 'mouseover',
							],
						'hide' => [
							'effect' => 'slide',
							'duration' => '500',
							'event' => 'click mouseleave',
						],
					],
				]
			];

			// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
			$this->args['share_icons'][] = [
				'url' => 'https://github.com/jeweltheme',
				'title' => 'Visit us on GitHub',
				'icon' => 'el-icon-github'
				//'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
			];
			$this->args['share_icons'][] = [
				'url' => 'https://www.facebook.com/jwthemeltd',
				'title' => 'Like us on Facebook',
				'icon' => 'el-icon-facebook'
			];
			$this->args['share_icons'][] = [
				'url' => 'https://twitter.com/jwthemeltd',
				'title' => 'Follow us on Twitter',
				'icon' => 'el-icon-twitter'
			];

			$uamp_pro = "This is Demo Intro Text";

		}
	}

	global $uampConfig;
	$uampConfig = new UampAdminOptions();

}