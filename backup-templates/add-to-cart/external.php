<?php
/**
 * External product add to cart
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/add-to-cart/external.php.
 *
 * @var $this AMPHTML_Template
 */
global $product;
$product_url = $product->get_product_url();
$button_text = $product->single_add_to_cart_text();

if ( ! empty( $product_url ) ) : ?>
	<?php do_action( 'amp_before_add_to_cart_button' ); ?>
	<p class="amphtml-add-to">
		<a href="<?php echo esc_url( $product_url ); ?>" rel="nofollow"
		   class="single_add_to_cart_button button alt a-button"><?php echo esc_html( $button_text ); ?></a>
	</p>
	<?php do_action( 'amp_after_add_to_cart_button' ); ?>
<?php endif; ?>