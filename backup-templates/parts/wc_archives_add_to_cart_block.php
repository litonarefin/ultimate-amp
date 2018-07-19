<div class="clearfix">
	<?php if ( $this->get_option( 'wc_archives_price' ) ): ?>
		<p class="amphtml-price"><?php _e( 'Price', 'amphtml' ) ?>:<?php woocommerce_template_loop_price(); ?></p>
	<?php endif; ?>

	<?php if ( $this->get_option( 'wc_archives_add_to_cart' ) ): ?>
		<?php AMPHTML_WC()->get_add_to_cart_button(); ?>
	<?php endif; ?>
</div>