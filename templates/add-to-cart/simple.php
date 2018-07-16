<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/add-to-cart/simple.php.
 *
 * @var $this AMPHTML_WC
 */
global $product;

if ( 'add_to_cart_cart' == $this->get_template()->get_option( 'add_to_cart_behav' ) ) {
	global $woocommerce;
	$add_to_cart_url = wc_get_cart_url();
	$add_to_cart_url = add_query_arg( 'add-to-cart', $product->get_id(), $add_to_cart_url );
} else if ( 'add_to_cart_checkout' == $this->get_template()->get_option( 'add_to_cart_behav' ) ) {
	global $woocommerce;
	$add_to_cart_url = $woocommerce->cart->get_checkout_url();
	$add_to_cart_url = add_query_arg( 'add-to-cart', $product->get_id(), $add_to_cart_url );
} else if ( 'add_to_cart' == $this->get_template()->get_option( 'add_to_cart_behav' ) ) {
	$add_to_cart_url = get_permalink( $product->get_id() );
	$add_to_cart_url = add_query_arg( 'add-to-cart', $product->get_id(), $add_to_cart_url );
	$add_to_cart_url = add_query_arg( 'add-to-cart-redirect', '1', $add_to_cart_url );
} else {
	$add_to_cart_url = get_permalink( $product->get_id() );
	$add_to_cart_url = add_query_arg( 'add-to-cart-redirect', '1', $add_to_cart_url );
}

if ( $product->is_in_stock() ) : ?>
	<?php do_action( 'amp_before_add_to_cart_button' ); ?>
	<p class="amphtml-add-to">
		<a class="a-button"
		   href="<?php echo $add_to_cart_url ?>"><?php echo $this->get_template()->get_option( 'add_to_cart_text' ) ?></a>
	</p>
	<?php do_action( 'amp_after_add_to_cart_button' ); ?>
<?php endif; ?>