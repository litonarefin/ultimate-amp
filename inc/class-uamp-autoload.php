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
	 *
	 * @note We omitted the leading slash and the .php extension from each
	 *       relative filepath because they are redundant and to include
	 *       them would take up unnecessary bytes of memory at runtime.
	 *
	 * @example Format (note no leading / and no .php extension):
	 *
	 *  array(
	 *      'Class_Name1' =>  'subdir-of-inc/amp/includes/filename1',
	 *      'Class_Name2' =>  '2nd-subdir-of-inc/amp/includes/filename2',
	 *  );
	 *
	 * @var string[]
	 */
	private static $_classmap = array(
		'Ultimate_AMP_Helper'                         => 'inc/class-amp-helper',
		'UampAdminOptions'                         	  => 'inc/admin/admin-options',
		'Template_Loader'                         	  => 'lib/class-uamp-template-loader',
//		'Ultimate_AMP_Abstract_Template'              => 'inc/class-uamp-template-manager',
		'Ultimate_AMP_Template'              		  => 'inc/class-uamp-template',
		'Ultimate_AMP_Sanitize'              		  => 'inc/class-uamp-sanitize',
		'Ultimate_AMP_Shortcode'              		  => 'inc/class-uamp-shortcodes',
		'FastImage'              					  => 'lib/Fastimage',
		'UAMP_Nav_Menu_Walker'                        => 'inc/menu-walker',
		'AMP_Theme_Support'                           => 'inc/amp/includes/class-amp-theme-support',
		'AMP_Comment_Walker'                          => 'inc/amp/includes/class-amp-comment-walker',
		'AMP_Template_Customizer'                     => 'inc/amp/includes/admin/class-amp-customizer',
		'AMP_Post_Meta_Box'                           => 'inc/amp/includes/admin/class-amp-post-meta-box',
		'AMP_Post_Type_Support'                       => 'inc/amp/includes/class-amp-post-type-support',
		'AMP_Base_Embed_Handler'                      => 'inc/amp/includes/embeds/class-amp-base-embed-handler',
		'AMP_DailyMotion_Embed_Handler'               => 'inc/amp/includes/embeds/class-amp-dailymotion-embed',
		'AMP_Facebook_Embed_Handler'                  => 'inc/amp/includes/embeds/class-amp-facebook-embed',
		'AMP_Gallery_Embed_Handler'                   => 'inc/amp/includes/embeds/class-amp-gallery-embed',
		'AMP_Instagram_Embed_Handler'                 => 'inc/amp/includes/embeds/class-amp-instagram-embed',
		'AMP_Issuu_Embed_Handler'                     => 'inc/amp/includes/embeds/class-amp-issuu-embed-handler',
		'AMP_Meetup_Embed_Handler'                    => 'inc/amp/includes/embeds/class-amp-meetup-embed-handler',
		'AMP_Pinterest_Embed_Handler'                 => 'inc/amp/includes/embeds/class-amp-pinterest-embed',
		'AMP_Playlist_Embed_Handler'                  => 'inc/amp/includes/embeds/class-amp-playlist-embed-handler',
		'AMP_Reddit_Embed_Handler'                    => 'inc/amp/includes/embeds/class-amp-reddit-embed-handler',
		'AMP_SoundCloud_Embed_Handler'                => 'inc/amp/includes/embeds/class-amp-soundcloud-embed',
		'AMP_Tumblr_Embed_Handler'                    => 'inc/amp/includes/embeds/class-amp-tumblr-embed-handler',
		'AMP_Twitter_Embed_Handler'                   => 'inc/amp/includes/embeds/class-amp-twitter-embed',
		'AMP_Vimeo_Embed_Handler'                     => 'inc/amp/includes/embeds/class-amp-vimeo-embed',
		'AMP_Vine_Embed_Handler'                      => 'inc/amp/includes/embeds/class-amp-vine-embed',
		'AMP_YouTube_Embed_Handler'                   => 'inc/amp/includes/embeds/class-amp-youtube-embed',
		'FastImage'                                   => 'inc/amp/includes/lib/fastimage/class-fastimage',
		'WillWashburn\Stream\Exception\StreamBufferTooSmallException' => 'inc/amp/includes/lib/fasterimage/Stream/Exception/StreamBufferTooSmallException',
		'WillWashburn\Stream\StreamableInterface'     => 'inc/amp/includes/lib/fasterimage/Stream/StreamableInterface',
		'WillWashburn\Stream\Stream'                  => 'inc/amp/includes/lib/fasterimage/Stream/Stream',
		'FasterImage\Exception\InvalidImageException' => 'inc/amp/includes/lib/fasterimage/Exception/InvalidImageException',
		'FasterImage\ExifParser'                      => 'inc/amp/includes/lib/fasterimage/ExifParser',
		'FasterImage\ImageParser'                     => 'inc/amp/includes/lib/fasterimage/ImageParser',
		'FasterImage\FasterImage'                     => 'inc/amp/includes/lib/fasterimage/FasterImage',
		'AMP_Analytics_Options_Submenu'               => 'inc/amp/includes/options/class-amp-analytics-options-submenu',
		'AMP_Options_Menu'                            => 'inc/amp/includes/options/class-amp-options-menu',
		'AMP_Options_Manager'                         => 'inc/amp/includes/options/class-amp-options-manager',
		'AMP_Analytics_Options_Submenu_Page'          => 'inc/amp/includes/options/views/class-amp-analytics-options-submenu-page',
		'AMP_Options_Menu_Page'                       => 'inc/amp/includes/options/views/class-amp-options-menu-page',
		'AMP_Rule_Spec'                               => 'inc/amp/includes/sanitizers/class-amp-rule-spec',
		'AMP_Allowed_Tags_Generated'                  => 'inc/amp/includes/sanitizers/class-amp-allowed-tags-generated',
		'AMP_Audio_Sanitizer'                         => 'inc/amp/includes/sanitizers/class-amp-audio-sanitizer',
		'AMP_Base_Sanitizer'                          => 'inc/amp/includes/sanitizers/class-amp-base-sanitizer',
		'AMP_Blacklist_Sanitizer'                     => 'inc/amp/includes/sanitizers/class-amp-blacklist-sanitizer',
		'AMP_Iframe_Sanitizer'                        => 'inc/amp/includes/sanitizers/class-amp-iframe-sanitizer',
		'AMP_Img_Sanitizer'                           => 'inc/amp/includes/sanitizers/class-amp-img-sanitizer',
		'AMP_Comments_Sanitizer'                      => 'inc/amp/includes/sanitizers/class-amp-comments-sanitizer',
		'AMP_Form_Sanitizer'                          => 'inc/amp/includes/sanitizers/class-amp-form-sanitizer',
		'AMP_Playbuzz_Sanitizer'                      => 'inc/amp/includes/sanitizers/class-amp-playbuzz-sanitizer',
		'AMP_Style_Sanitizer'                         => 'inc/amp/includes/sanitizers/class-amp-style-sanitizer',
		'AMP_Tag_And_Attribute_Sanitizer'             => 'inc/amp/includes/sanitizers/class-amp-tag-and-attribute-sanitizer',
		'AMP_Video_Sanitizer'                         => 'inc/amp/includes/sanitizers/class-amp-video-sanitizer',
		'AMP_Customizer_Design_Settings'              => 'inc/amp/includes/settings/class-amp-customizer-design-settings',
		'AMP_Customizer_Settings'                     => 'inc/amp/includes/settings/class-amp-customizer-settings',
		'AMP_Content'                                 => 'inc/amp/includes/templates/class-amp-content',
		'AMP_Content_Sanitizer'                       => 'inc/amp/includes/templates/class-amp-content-sanitizer',
		'AMP_Post_Template'                           => 'inc/amp/includes/templates/class-amp-post-template',
		'AMP_DOM_Utils'                               => 'inc/amp/includes/utils/class-amp-dom-utils',
		'AMP_HTML_Utils'                              => 'inc/amp/includes/utils/class-amp-html-utils',
		'AMP_Image_Dimension_Extractor'               => 'inc/amp/includes/utils/class-amp-image-dimension-extractor',
		'AMP_Validation_Utils'                        => 'inc/amp/includes/utils/class-amp-validation-utils',
		'AMP_String_Utils'                            => 'inc/amp/includes/utils/class-amp-string-utils',
		'AMP_WP_Utils'                                => 'inc/amp/includes/utils/class-amp-wp-utils',
		'AMP_Widget_Archives'                         => 'inc/amp/includes/widgets/class-amp-widget-archives',
		'AMP_Widget_Categories'                       => 'inc/amp/includes/widgets/class-amp-widget-categories',
		'AMP_Widget_Media_Video'                      => 'inc/amp/includes/widgets/class-amp-widget-media-video',
		'AMP_Widget_Recent_Comments'                  => 'inc/amp/includes/widgets/class-amp-widget-recent-comments',
		'AMP_Widget_Text'                             => 'inc/amp/includes/widgets/class-amp-widget-text',
		'WPCOM_AMP_Polldaddy_Embed'                   => 'inc/amp/wpcom/class-amp-polldaddy-embed',
		'AMP_Test_Stub_Sanitizer'                     => 'tests/stubs',
		'AMP_Test_World_Sanitizer'                    => 'tests/stubs',

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
