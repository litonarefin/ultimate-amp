<?php

	if ( ! class_exists( 'Redux' ) ) {
		return;
	}

	$opt_name = "uamp_options";


//	$theme = get_plugin_data(__FILE__); // For use with some settings. Not necessary.

	$args = array(
		// TYPICAL -> Change these values as you need/desire
		'opt_name'             => $opt_name,
		// This is where your data is stored in the database and also becomes your global variable name.
		'display_name'         => UAMP,
		// Name that appears at the top of your panel
		'display_version'      => UAMP_VERSION,
		// Version that appears at the top of your panel
		'menu_type'            => 'menu',
		//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
		'allow_sub_menu'       => true,
		// Show the sections below the admin menu item or not
		'menu_title'           => __( UAMP, 'uamp' ),
		'page_title'           => __( UAMP, 'uamp' ),
		// You will need to generate a Google API key to use this feature.
		// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
		'google_api_key'       => '',
		// Set it you want google fonts to update weekly. A google_api_key value is required.
		'google_update_weekly' => false,
		'show_options_object'   => false,
		// Must be defined to add google fonts to the typography module
		'async_typography'     => true,
		// Use a asynchronous font on the front end or font string
		//'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
		'admin_bar'            => false,
		// Show the panel pages on the admin bar
		'admin_bar_icon'       => 'dashicons-portfolio',
		// Choose an icon for the admin bar menu
		'admin_bar_priority'   => 50,
		// Choose an priority for the admin bar menu
		'global_variable'      => '',
		// Set a different name for your global variable other than the opt_name
		'dev_mode'             => false,
		// Show the time the page took to load, etc
		'update_notice'        => false,
		// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
		'customizer'           => false,
		// Enable basic customizer support
		//'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
		//'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

		// OPTIONAL -> Give you extra features
		'page_priority'        => null,
		// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
		'page_parent'          => 'themes.php',
		// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
		'page_permissions'     => 'manage_options',
		// Permissions needed to access the options panel.
		'menu_icon'            => UAMP_PLUGIN_URL . "/images/amp.png",
		// Specify a custom URL to an icon
		'last_tab'             => '',
		// Force your panel to always open to a specific tab (by id)
		'page_icon'            => 'icon-themes',
		// Icon displayed in the admin panel next to your menu_title
		'page_slug'            => 'uamp_options',
		// Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
		'save_defaults'        => true,
		'forced_dev_mode_off'  => true,
		// On load save the defaults to DB before user clicks save or not
		'default_show'         => false,
		// If true, shows the default value next to each field that is not the default value.
		'default_mark'         => '',
		// What to print by the field's title if the value shown is default. Suggested: *
		'show_import_export'   => false,
		// Shows the Import/Export panel when not used as a field.

		// CAREFUL -> These options are for advanced use only
		'transient_time'       => 60 * MINUTE_IN_SECONDS,
		'output'               => true,
		// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
		'output_tag'           => true,
		// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
		// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

		// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
		'database'             => '',
		// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
		'use_cdn'              => true,
		// If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

		// HINTS
		'hints'                => array(
			'icon'          => 'el el-question-sign',
			'icon_position' => 'right',
			'icon_color'    => 'lightgray',
			'icon_size'     => 'normal',
			'tip_style'     => array(
				'color'   => 'red',
				'shadow'  => true,
				'rounded' => false,
				'style'   => '',
			),
			'tip_position'  => array(
				'my' => 'top left',
				'at' => 'bottom right',
			),
			'tip_effect'    => array(
				'show' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'mouseover',
				),
				'hide' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'click mouseleave',
				),
			),
		)
	);

	// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
	$args['share_icons'][] = array(
		'url'   => 'https://wordpress.org/plugins/ultimate-amp/',
		'title' => 'Visit on WordPress.org',
		'icon'  => 'el el-wordpress'
		//'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
	);
	$args['share_icons'][] = array(
		'url'   => 'https://github.com/litonarefin/ultimate-amp',
		'title' => 'Visit us on GitHub',
		'icon'  => 'el el-github'
		//'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
	);
	$args['share_icons'][] = array(
		'url'   => 'https://www.facebook.com/jwthemeltd/',
		'title' => 'Like us on Facebook',
		'icon'  => 'el el-facebook'
	);
	$args['share_icons'][] = array(
		'url'   => 'https://twitter.com/jwthemeltd',
		'title' => 'Follow us on Twitter',
		'icon'  => 'el el-twitter'
	);

	// Add content after the form.
	//$args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more
	// info is always better! The footer_text field accepts all HTML.</p>', 'uamp' );

	Redux::setArgs( $opt_name, $args );

	/*
	 * ---> END ARGUMENTS
	 */



	// Set the help sidebar
	$content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'uamp' );
	Redux::setHelpSidebar( $opt_name, $content );



	// -> START General Settings
	Redux::setSection( $opt_name, array(
		'title'            => __( 'Global Settings', 'uamp' ),
		'id'               => 'global',
		'customizer_width' => '400px',
		'icon'             => 'el el-globe',

	) );


	Redux::setSection( $opt_name, array(
		'title'      => __( 'General', 'uamp' ),
		'id'         => 'global-general',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'uamp_is_amp',
				'type'     => 'button_set',
				'title'    => __( 'Redirect to AMP Version?', 'uamp' ),
				'subtitle' => __( 'Do you want to redirect your website to AMP Version?', 'uamp' ),
				'desc'     => __( 'Enable/Disable to force website to AMP Version', 'uamp' ),
				'options'  => array(
					'enable' => 'Enable',
					'disable' => 'Disable'
				),
				'default'  => 'enable'
			),

			array(
				'id' => 'uamp_favicon',
				'type' => 'media',
				'title' => esc_html__('Favicon Icon', 'uamp'),
				'default' => array("url" => esc_url( UAMP_PLUGIN_URL . "/images/amp.png" )),
				'preview' => true,
				"url" => true
			),

			array(
				'id'       => 'enable_debug_mode',
				'type'     => 'button_set',
				'title'    => __( 'Enable Debug Mode', 'uamp' ),
				'subtitle' => __( 'Do you want to Enable Debug Mode?', 'uamp' ),
				'desc'     => __( 'Enable/Disable Enable Debug Mode', 'uamp' ),
				'options'  => array(
					'enable' => 'Enable',
					'disable' => 'Disable'
				),
				'default'  => 'disable'
			)




		)
	) );



	// Header
	Redux::setSection( $opt_name, array(
		'title'            => __( 'Header', 'uamp' ),
		'id'               => 'global-header',
		'subsection'       => true,
		'fields'           => array(


			array(
				'id'       => 'uamp_logo_type',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Logo Type', 'uamp' ),
				'subtitle' => esc_html__( 'Choose Logo Type Image/Text', 'uamp' ),
				'options'  => array(
					'image'     	=> esc_html__( 'Image', 'uamp'),
					'text'    		=> esc_html__( 'Text', 'uamp'),
					'text_image'    => esc_html__( 'Image & Text', 'uamp'),
				),
				'default'  => 'text_image'
			),

			array(
				'id' => 'uamp_logo_text',
				'type' => 'text',
				'title' => esc_html__('Logo Text', 'uamp'),
				'default' => esc_html__('Ultimate AMP', 'uamp'),
				'required' => array( 'uamp_logo_type', '=', array( 'text','text_image'))
			),

			array(
				'id' => 'uamp_logo_image',
				'type' => 'media',
				'title' => esc_html__('Logo Image', 'uamp'),
				'default' => array("url" => esc_url( UAMP_PLUGIN_URL . "/images/amp.png" )),
				'required' => array( 'uamp_logo_type', '=', array( 'image','text_image')),
				'preview' => true,
				"url" => true
			),
			array(
				'id' => 'uamp_logo_width',
				'type' => 'slider',
				'title' => __('Logo Width', 'uamp'),
				'subtitle' => __('Logo Width in Pixels', 'uamp'),
				"default" => 32,
				"min" => 32,
				"step" => 1,
				"max" => 200,
				'resolution' => 1
			),
			array(
				'id' => 'uamp_logo_height',
				'type' => 'slider',
				'title' => __('Logo Height', 'uamp'),
				'subtitle' => __('Logo Height in Pixels', 'uamp'),
				"default" => 32,
				"min" => 32,
				"step" => 1,
				"max" => 90,
				'resolution' => 1
			)

		)
	) );


	// Footer
	Redux::setSection( $opt_name, array(
		'title'            => __( 'Footer', 'uamp' ),
		'id'               => 'global-footer',
		'subsection'       => true,
		'fields'           => array(

			array(
				'id'       => 'uamp_copyright_text',
				'type'     => 'textarea',
				'title'    => __( 'Copyright Text', 'uamp' ),
				'default'  => __('', 'uamp' )
			),

		)
	) );




	// Templates
	Redux::setSection( $opt_name, array(
		'title'            => __( 'Templates', 'uamp' ),
		'id'               => 'templates',
		'customizer_width' => '400px',
		'icon'             => 'el el-globe',

	) );


	// Posts
	Redux::setSection( $opt_name, array(
		'title'            => __( 'Posts', 'uamp' ),
		'id'               => 'templates-posts',
		'subsection'       => true,
		'fields'           => array(


			array(
				'id' => 'uamp_posts_comment_btn_text',
				'type' => 'text',
				'title' => esc_html__('Comment Button', 'uamp'),
				'default' => esc_html__('Add a Comment', 'uamp'),
			),

			array(
				'id'       => 'uamp_posts_ajax_comment',
				'type'     => 'button_set',
				'title'    => __( 'Ajax Comment?', 'uamp' ),
				'subtitle' => __( 'Do you want to enable Ajax Comment ON?', 'uamp' ),
				'desc'     => __( 'Enable/Disable Ajax Commenting on AMP Version', 'uamp' ),
				'options'  => array(
					'enable' => 'Enable',
					'disable' => 'Disable'
				),
				'default'  => 'enable'
			),

			array(
				'id' => 'uamp_desktop_text',
				'type' => 'text',
				'title' => esc_html__('View Desktop', 'uamp'),
				'subtitle' => __('Redirect to Original URL message "View Desktop"', 'uamp'),
				'default' => esc_html__('View Desktop Version', 'uamp'),
			),

			array(
				'id' => 'uamp_desktop_text',
				'type' => 'text',
				'title' => esc_html__('View Desktop', 'uamp'),
				'subtitle' => __('Redirect to Original URL message "View Desktop"', 'uamp'),
				'default' => esc_html__('View Desktop Version', 'uamp'),
			),



		)
	) );


	// Pages
	Redux::setSection( $opt_name, array(
		'title'            => __( 'Pages', 'uamp' ),
		'id'               => 'templates-pages',
		'subsection'       => true,
		'fields'           => array(

			array(
				'id' => 'uamp_pages_comment_btn_text',
				'type' => 'text',
				'title' => esc_html__('Comment Button', 'uamp'),
				'default' => esc_html__('Add a Comment', 'uamp'),
			),

			array(
				'id'       => 'uamp_pages_ajax_comment',
				'type'     => 'button_set',
				'title'    => __( 'Ajax Comment?', 'uamp' ),
				'subtitle' => __( 'Do you want to enable Ajax Comment ON?', 'uamp' ),
				'desc'     => __( 'Enable/Disable Ajax Commenting on AMP Version', 'uamp' ),
				'options'  => array(
					'enable' => 'Enable',
					'disable' => 'Disable'
				),
				'default'  => 'enable'
			),




		)
	) );





	Redux::setSection( $opt_name, array(
		'title'            => __( 'Socials', 'uamp' ),
		'id'               => 'socials',
		'fields'           => array(

			array(
				'id' => 'uamp_twitter',
				'type' => 'text',
				'validate' => 'url',
				'title' => esc_html__('Twitter', 'uamp'),
				'desc'     => __( 'Twitter URL', 'uamp' ),
				'default' => "https://twitter.com/jwthemeltd",
			),

			array(
				'id' => 'uamp_facebook',
				'type' => 'text',
				'title' => esc_html__('Facebook', 'uamp'),
				'desc'     => __( 'Facebook URL', 'uamp' ),
				'default' => "https://facebook/jwthemeltd",
			),

			array(
				'id' => 'uamp_instagram',
				'type' => 'text',
				'title' => esc_html__('Instagram', 'uamp'),
				'desc'     => __( 'Instagram URL', 'uamp' ),
				'default' => "#",
			),

			array(
				'id' => 'uamp_pinterest',
				'type' => 'text',
				'title' => esc_html__('Pinterest', 'uamp'),
				'desc'     => __( 'Pinterest URL', 'uamp' ),
				'default' => "#",
			),
		)
	) );




	if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
		$section = array(
			'icon'   => 'el el-list-alt',
			'title'  => __( 'Documentation', 'uamp' ),
			'fields' => array(
				array(
					'id'       => '17',
					'type'     => 'raw',
					'markdown' => true,
					'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
					//'content' => 'Raw content here',
				),
			),
		);
		Redux::setSection( $opt_name, $section );
	}
	/*
	 * <--- END SECTIONS
	 */


	/*
	 *
	 * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
	 *
	 */

	/*
	*
	* --> Action hook examples
	*
	*/

	// If Redux is running as a plugin, this will remove the demo notice and links


	// Function to test the compiler hook and demo CSS output.
	// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
	//add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

	// Change the arguments after they've been declared, but before the panel is created
	//add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

	// Change the default value of a field after it's been set, but before it's been useds
	//add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

	// Dynamically add a section. Can be also used to modify sections/fields
	//add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

	/**
	 * This is a test function that will let you see when the compiler hook occurs.
	 * It only runs if a field    set with compiler=>true is changed.
	 * */
	if ( ! function_exists( 'compiler_action' ) ) {
		function compiler_action( $options, $css, $changed_values ) {
			echo '<h1>The compiler hook has run!</h1>';
			echo "<pre>";
			print_r( $changed_values ); // Values that have changed since the last save
			echo "</pre>";
			//print_r($options); //Option values
			//print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
		}
	}

	/**
	 * Custom function for the callback validation referenced above
	 * */
	if ( ! function_exists( 'redux_validate_callback_function' ) ) {
		function redux_validate_callback_function( $field, $value, $existing_value ) {
			$error   = false;
			$warning = false;

			//do your validation
			if ( $value == 1 ) {
				$error = true;
				$value = $existing_value;
			} elseif ( $value == 2 ) {
				$warning = true;
				$value   = $existing_value;
			}

			$return['value'] = $value;

			if ( $error == true ) {
				$field['msg']    = 'your custom error message';
				$return['error'] = $field;
			}

			if ( $warning == true ) {
				$field['msg']      = 'your custom warning message';
				$return['warning'] = $field;
			}

			return $return;
		}
	}

	/**
	 * Custom function for the callback referenced above
	 */
	if ( ! function_exists( 'redux_my_custom_field' ) ) {
		function redux_my_custom_field( $field, $value ) {
			print_r( $field );
			echo '<br/>';
			print_r( $value );
		}
	}

	/**
	 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
	 * Simply include this function in the child themes functions.php file.
	 * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
	 * so you must use get_template_directory_uri() if you want to use any of the built in icons
	 * */
	if ( ! function_exists( 'dynamic_section' ) ) {
		function dynamic_section( $sections ) {
			//$sections = array();
			$sections[] = array(
				'title'  => __( 'Section via hook', 'uamp' ),
				'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'uamp' ),
				'icon'   => 'el el-paper-clip',
				// Leave this as a blank section, no options just some intro text set above.
				'fields' => array()
			);

			return $sections;
		}
	}

	/**
	 * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
	 * */
	if ( ! function_exists( 'change_arguments' ) ) {
		function change_arguments( $args ) {
			//$args['dev_mode'] = true;

			return $args;
		}
	}

	/**
	 * Filter hook for filtering the default value of any given field. Very useful in development mode.
	 * */
	if ( ! function_exists( 'change_defaults' ) ) {
		function change_defaults( $defaults ) {
			$defaults['str_replace'] = 'Testing filter hook!';

			return $defaults;
		}
	}

	/**
	 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
	 */

	function uamp_remove_redux_demo() {
		// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			remove_filter( 'plugin_row_meta', array(
				ReduxFrameworkPlugin::instance(),
				'plugin_metalinks'
			), null, 2 );

			// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
		}
	}

	add_action( 'redux/loaded', 'uamp_remove_redux_demo' );