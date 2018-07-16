<?php if ( $this->product->get_sku() ): ?>
	<small class="amphtml-sku"><?php _e( 'SKU', 'amphtml' ) ?>: <?php echo $this->product->get_sku() ?></small>
<?php endif; ?>