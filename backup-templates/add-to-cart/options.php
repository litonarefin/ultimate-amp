<?php
/**
 * Options product add to cart
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/add-to-cart/options.php.
 *
 * @var $this AMPHTML_WC
 */

$product           = wc_get_product( get_the_ID() );
$template          = $this->get_template();
$add_to_cart_behav = $this->get_template()->get_option( 'add_to_cart_behav' );

if ( $add_to_cart_behav == 'add_to_cart_cart' ) {
	global $woocommerce;
	$form_action = wc_get_cart_url();
} elseif ( $add_to_cart_behav == 'add_to_cart_checkout' ) {
	global $woocommerce;
	$form_action = $woocommerce->cart->get_checkout_url();
} else {
	$form_action = get_permalink( $product->get_id() );
}

if ( $product->get_type() === 'variable' ) {
	$attributes_set = $product->get_variation_attributes();
}

if ( $product->is_in_stock() ) : ?>
	<?php do_action( 'amp_before_add_to_cart_button' ); ?>
	<div class="clearfix"></div>
	<form action="<?php echo $form_action ?>" method="get" target="_blank" id="form1">
		<?php if ( $template->get_option( 'product_options' ) && $product->get_type() === 'variable' ): ?>
			<div class="amp-product-options">
				<?php foreach ( $attributes_set as $attribute_name => $options ): ?>
					<div class="amp-atribute-select-block">
						<label
							for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?>:</label>
						<select name="<?php echo wc_variation_attribute_name( $attribute_name ); ?>"
						        class="amp-attribute-select">
							<?php foreach ( $options as $option ): ?>
								<option
									value="<?php echo $option; ?>"><?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endforeach; ?>
			</div>
			<input type="hidden" name="amp-add-to-cart" value="1">
		<?php endif; ?>

		<?php if ( $template->get_option( 'product_qty' ) ): ?>
			<div class="amp-product-options">
				<label for="quantity"><?php _e( 'Quantity: ', 'amphtml' ); ?></label>
				<input type="number" name="quantity" value="1"/>
			</div>
		<?php endif; ?>

		<?php if ( $add_to_cart_behav == 'add_to_cart' OR $add_to_cart_behav == 'add_to_cart_cart' OR $add_to_cart_behav == 'add_to_cart_checkout' ): ?>
			<input type="hidden" name="add-to-cart" value="<?php echo $product->get_id(); ?>">
		<?php endif; ?>

        <input type="hidden" name="add-to-cart-redirect" value="1">

		<p class="amphtml-add-to">
			<input class="a-button" type="submit"
			       value="<?php echo $this->get_template()->get_option( 'add_to_cart_text' ) ?>">
		</p>
	</form>
	<?php do_action( 'amp_after_add_to_cart_button' ); ?>
<?php endif; ?>