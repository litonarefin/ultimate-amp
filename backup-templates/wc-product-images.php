<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/product-thumbnails.php.
 *
 * @var $this AMPHTML_Template
 */
global $post, $product, $woocommerce; ?>
<div class="thumbnails">
	<?php foreach ( $this->product_image_ids as $attachment_id ) {

		$image_link = wp_get_attachment_url( $attachment_id );

		if ( ! $image_link ) {
			continue;
		}

		$image_title   = esc_attr( get_the_title( $attachment_id ) );
		$image_alt     = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		$image_caption = esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );

		$image = wp_get_attachment_image( $attachment_id, 'shop_single', 0, $attr = array (
			'title' => $image_title,
			'alt'   => $image_alt,
		) );

		echo sprintf( '<a href="%s" title="%s">%s</a>', $image_link, $image_caption, $image );
	}
	?>
</div>