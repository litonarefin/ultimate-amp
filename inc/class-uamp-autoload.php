<?php
/**
 * Author Name: Liton Arefin
 * Author URL: https://jeweltheme.com
 * Date: 6/24/18
 */


//namespace Uamp\inc;

/**
 * Autoload the classes used by the Ultimate AMP plugin.
 *
 * Class UltimateAmpAutoload
 */
class UltimateAmpAutoload {

	/**
	 * Map of Classname to relative filepath sans extension.

	 * @note We omitted the leading slash and the .php extension from each
	 *       relative filepath because they are redundant and to include
	 *       them would take up unnecessary bytes of memory at runtime.
	 *
	 * @example Format (note no leading / and no .php extension):
	 *
	 *  array(
	 *      'Class_Name1' =>  'subdir-of-inc/amp/lib/vendor/amp/includes/filename1',
	 *      'Class_Name2' =>  '2nd-subdir-of-inc/amp/includes/filename2',
	 *  );
	 *
	 * @var string[]
	 */
	private static $_classmap = array(
		'Ultimate_AMP_Helper'                         => 'inc/class-uamp-helper',
		'UampAdminOptions'                         	  => 'inc/admin/admin-options',
		'Template_Loader'                         	  => 'lib/class-uamp-template-loader',
		'Ultimate_AMP_Template'              		  => 'inc/class-uamp-template',
		'Ultimate_AMP_Sanitize'              		  => 'inc/class-uamp-sanitize',
		'Ultimate_AMP_Shortcode'              		  => 'inc/class-uamp-shortcodes',
		'FastImage'              					  => 'lib/Fastimage',
		'UAMP_Nav_Menu_Walker'                        => 'inc/menu-walker',
		'ThirdPartyCompatibility'                     => 'inc/class-uamp-third-party-compatibility',
		'Ultimate_Template_Loader'                    => 'inc/class-uamp-template-loader',
		'Ultimate_AMP_Load_Template_Files'                    => 'inc/class-uamp-extend-lib-template-loader',

	);

	/**
	 * Is registered.
	 *
	 * @var bool
	 */
	public static $is_registered = false;

	/**
	 * Perform the autoload on demand when requested by PHP runtime.
	 *
	 * Design Goal: Execute as few lines of code as possible each call.
	 *
	 * @since 0.6
	 *
	 * @param string $class_name Class name.
	 */
	protected static function autoload( $class_name ) {
		if ( ! isset( self::$_classmap[ $class_name ] ) ) {
			return;
		}
		$filepath = self::$_classmap[ $class_name ];
		require UAMP_PLUGIN_DIR . "/{$filepath}.php";
	}

	/**
	 * Registers this autoloader to PHP.
	 *
	 * @since 0.6
	 *
	 * Called at the end of this file; calling a second time has no effect.
	 */
	public static function register() {
		if ( ! self::$is_registered ) {
			spl_autoload_register( array( __CLASS__, 'autoload' ) );
			self::$is_registered = true;
		}
	}

	/**
	 * Allows an extensions plugin to register a class and its file for autoloading
	 *
	 * @since 0.6
	 *
	 * @param string $class_name Full classname (include namespace if applicable).
	 * @param string $filepath   Absolute filepath to class file, including .php extension.
	 */
	public static function register_autoload_class( $class_name, $filepath ) {
		self::$_classmap[ $class_name ] = '!' . $filepath;
	}
}
