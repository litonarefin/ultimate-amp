<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/21/18
	 */

	class Uamp_Admin{

		public function __construct() {
			$this->uamp_include_files();
			$this->uamp_actions();
		}

		public function uamp_include_files(){

		}

		public function uamp_actions(){
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		public function uamp_menu_position(){
			apply_filters('uamp_menu_position', 48);
		}

		public function uamp_get_capability(){
			return apply_filters('uamp_capability', 'manage_options');
		}


		public function admin_menu(){
			$capability= $this->uamp_get_capability();

			add_menu_page(
					__( 'Ultimate AMP', 'uamp' ),
					__( 'Ultimate AMP', 'uamp' ),
					$capability,
					'uamp',
					array( $this, 'uamp_index' ),
					plugins_url( 'amp.png', __FILE__ ),
					$this->uamp_menu_position()
				);
//			add_submenu_page(
//					'uamp',
//					__( 'Options', 'uamp' ),
//					__( 'Options', 'uamp' ),
//					$capability,
//					'uamp',
//					array( $this, 'uamp_index' )
//				);


		}


		public function uamp_index(){
			// Redux panel inclusion code
//			if ( ! class_exists( 'ReduxFramework' ) ) {
//				return;
//			}
//				require_once dirname( __FILE__ ).'/loader.php';
			//require_once dirname( __FILE__ ).'/redux-core/framework.php';



			if ( is_admin() ) {
				// Register all the main options
				//require_once dirname( __FILE__ ).'/AdminOptions.php';
//				require_once dirname( __FILE__ ).'/templates/report-bugs.php';
			}


		}

	}

	return new Uamp_Admin();