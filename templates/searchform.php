<?php
/**
 * The Template for displaying search forms in AMP HTML Page
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/searchform.php.
 *
 * @var $this AMPHTML_Template
 */

$this->add_embedded_element( array (
		'slug' => 'amp-form',
		'src'  => 'https://cdn.ampproject.org/v0/amp-form-0.1.js'
	) );

$scheme = is_ssl() ? "https" : "http";
?>
<form role="search" method="get" class="search-form"
      action="<?php echo $this->get_amphtml_link( esc_url( home_url( '/', $scheme ) ) ); ?>" target="_top">
	<label>
		<input type="search"
		       class="search-field"
		       placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ) ?>"
		       value="<?php echo get_search_query() ?>" name="s"/>
		<input type="hidden" name="is_amp" value="1">
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>"/>
</form>