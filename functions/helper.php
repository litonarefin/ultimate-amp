<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/14/18
	 */



	// New Template Directory
	add_filter('amp_post_template_dir','uamp_new_template_dir');

	function uamp_new_template_dir(){
		$dir = UAMP_DIR . '/templates/template-one';
		return $dir;
	}



	/*
	 * Check if WordPress AMP
	 */
	add_action( 'plugins_loaded', 'uamp_deactivate_amp_plugin' );
	add_filter( 'plugin_action_links', 'uamp_modify_amp_activatation_link', 10, 2 );

	/*
	 * Check if Accelerated Mobile Pages
	 */
	add_action( 'plugins_loaded', 'uamp_deactivate_ampforwp_plugin' );
	add_filter( 'plugin_action_links', 'uamp_modify_ampforwp_activatation_link', 10, 2 );

	/*
	 * Check if Better AMP
	 */
	add_action( 'plugins_loaded', 'uamp_deactivate_better_amp_plugin' );
	add_filter( 'plugin_action_links', 'uamp_modify_better_amp_activatation_link', 10, 2 );

	/*
	 * Check if WP AMP
	 */
	add_action( 'plugins_loaded', 'uamp_deactivate_wp_amp_plugin' );
	add_filter( 'plugin_action_links', 'uamp_modify_wp_amp_activatation_link', 10, 2 );



	/*
	 * WP.COM AMP
	 */

	function uamp_deactivate_amp_plugin(){
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '3.5', '>=' ) ) {

			if ( current_user_can( 'activate_plugins' ) ) {

				add_action( 'admin_init', 'uamp_deactivate_amp');

				function uamp_deactivate_amp() {
					deactivate_plugins( UAMP_PLUGIN_DIR . 'amp/amp.php' );
				}
			}
		}
	}


	function uamp_modify_amp_activatation_link($actions, $plugin_file){
		$plugin = '';

		$plugin = 'amp/amp.php';
		if ( $plugin == $plugin_file ) {
			add_thickbox();
			unset($actions['activate']);
			$amp_activate = '<span style="cursor:pointer;color:#0089c8" class="warning_activate_amp" onclick="alert(\'AMP is already bundled with Ultimate AMP. Please do not install this plugin with Ultimate AMP to avoid conflicts. \')">Activate</span>';
			array_unshift ($actions, $amp_activate );
		}
		return $actions;
	}



	/*
	 * AMP for WP Plugin
	 */
	function uamp_deactivate_ampforwp_plugin(){

		if ( current_user_can( 'activate_plugins' ) ) {

			add_action( 'admin_init', 'uamp_deactivate_ampforwp');

			function uamp_deactivate_ampforwp() {
				deactivate_plugins( UAMP_PLUGIN_DIR . 'accelerated-mobile-pages/accelerated-moblie-pages.php' );
			}
		}
	}


	function uamp_modify_ampforwp_activatation_link($actions, $plugin_file){
		$plugin = '';

		$plugin = 'accelerated-mobile-pages/accelerated-moblie-pages.php';
		if ( $plugin == $plugin_file ) {
			add_thickbox();
			unset($actions['activate']);
			$amp_activate = '<span style="cursor:pointer;color:#0089c8" class="warning_activate_amp" onclick="alert(\'Please do not install AMPforWP plugin with Ultimate AMP to avoid conflicts. \')">Activate</span>';
			array_unshift ($actions, $amp_activate );
		}
		return $actions;
	}



	/*
	 * Better AMP Plugin
	 */
	function uamp_deactivate_better_amp_plugin(){

		if ( current_user_can( 'activate_plugins' ) ) {

			add_action( 'admin_init', 'uamp_deactivate_better_amp');

			function uamp_deactivate_better_amp() {
				deactivate_plugins( UAMP_PLUGIN_DIR . 'better-amp/better-amp.php' );
			}
		}
	}


	function uamp_modify_better_amp_activatation_link($actions, $plugin_file){
		$plugin = '';

		$plugin = 'better-amp/better-amp.php';
		if ( $plugin == $plugin_file ) {
			add_thickbox();
			unset($actions['activate']);
			$amp_activate = '<span style="cursor:pointer;color:#0089c8" class="warning_activate_amp" onclick="alert(\'Please do not install Better AMP plugin with Ultimate AMP to avoid conflicts. \')">Activate</span>';
			array_unshift ($actions, $amp_activate );
		}
		return $actions;
	}



	/*
	 * WP AMP Plugin
	 */
	function uamp_deactivate_wp_amp_plugin(){

		if ( current_user_can( 'activate_plugins' ) ) {

			add_action( 'admin_init', 'uamp_deactivate_better_amp');

			function uamp_deactivate_better_amp() {
				deactivate_plugins( UAMP_PLUGIN_DIR . 'wp-amp/wp-amp.php' );
			}
		}
	}


	function uamp_modify_wp_amp_activatation_link($actions, $plugin_file){
		$plugin = '';

		$plugin = 'wp-amp/wp-amp.php';
		if ( $plugin == $plugin_file ) {
			add_thickbox();
			unset($actions['activate']);
			$amp_activate = '<span style="cursor:pointer;color:#0089c8" class="warning_activate_amp" onclick="alert(\'Please do not install WP AMP|VestaThemes.com plugin with Ultimate AMP to avoid conflicts. \')">Activate</span>';
			array_unshift ($actions, $amp_activate );
		}
		return $actions;
	}

