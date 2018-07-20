<?php
	global $post_id;
	$uamp = new AMP_Post_Template($post_id);
    $post_author = $uamp->get( 'post_author' );

    if ( $post_author ) : ?>
	<div class="amp-wp-meta amp-wp-byline">
		<?php if ( function_exists( 'get_avatar_url' ) ) : ?>
			<amp-img src="<?php echo esc_url( get_avatar_url( $post_author->user_email, array( 'size' => 24 ) ) ); ?>" width="16" height="16" layout="fixed"></amp-img>
		<?php endif; ?>
		<span class="amp-wp-author author vcard"><?php echo esc_html( $post_author->display_name ); ?></span>
	</div>
<?php endif; ?>
