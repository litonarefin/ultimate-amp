<div class="clearfix">
	<p class="amphtml-price">
		<?php if ( $this->get_option( 'product_price' ) ): ?>
			<span class="price"><?php echo $this->product->get_price_html(); ?></span>
		<?php endif; ?>

		<?php if ( $this->get_option( 'product_stock_status' ) ): ?>
			<span class="amphtml-stock-status">
	                <?php if ( ! $this->product->is_in_stock() ): ?>
		                <?php _e( 'Out Of Stock', 'amphtml' ) ?>
	                <?php else: ?>
		                <?php _e( 'In Stock', 'amphtml' ) ?>
	                <?php endif; ?>
	            </span>
		<?php endif; ?>
	</p>

	<?php if ( $this->get_option( 'product_add_to' ) ): ?>
		<?php AMPHTML_WC()->get_add_to_cart_button( false ); ?>
	<?php endif; ?>
</div>