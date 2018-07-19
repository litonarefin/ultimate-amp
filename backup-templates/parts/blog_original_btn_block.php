<?php
$url = $this->get_canonical_url();
if ( $this->options->get( 'mobile_amp' ) ) {
	$url = add_query_arg( array (
		'view-original-redirect' => '1',
	), $url );
}
?>
<div class="amp-button-holder">
	<a href="<?php echo $url ?>" class="amp-button"><?php echo $this->options->get( 'blog_original_btn_text' ) ?></a>
</div>