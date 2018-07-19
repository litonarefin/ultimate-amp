<?php $post_link = $this->get_amphtml_link( get_permalink() ); ?>
<?php if ( $this->options->get( 'wc_shop_link_image' ) ): ?>
	<a href="<?php echo $post_link; ?>" title="<?php echo wp_kses_data( $this->title ); ?>">
<?php endif; ?>
		<?php echo $this->render_element( 'image', $this->featured_image ); ?>
<?php if ( $this->options->get( 'wc_shop_link_image' ) ): ?>
	</a>
<?php endif; ?>
