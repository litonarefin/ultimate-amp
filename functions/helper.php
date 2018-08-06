<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/14/18
	 */

	/*
	 * Check if WordPress AMP
	 */
	add_action( 'plugins_loaded', 'uamp_deactivate_amp_plugin' );
	add_filter( 'plugin_action_links', 'uamp_modify_amp_activatation_link', 10, 2 );


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