<?php
	$uamp = new AMP_Post_Template($post_id);
    $categories = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'amp' ), '', $uamp->ID ); ?>
<?php if ( $categories ) : ?>
		<?php printf( esc_html__( 'Categories: %s', 'amp' ), $categories ); ?>
<?php endif; ?>

<?php
	$tags = get_the_tag_list(
		'',
		_x( ', ', 'Used between list items, there is a space after the comma.', 'amp' ),
		'',
		$uamp->ID
	); ?>
<?php if ( $tags && ! is_wp_error( $tags ) ) : ?>
	<div class="amp-wp-meta amp-wp-tax-tag">
		<?php printf( esc_html__( 'Tags: %s', 'amp' ), $tags ); ?>
	</div>
<?php endif; ?>
