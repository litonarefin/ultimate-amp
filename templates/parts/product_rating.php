<?php
global $product;

if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
	return;
}

$rating_count = $product->get_rating_count();

if ( $rating_count > 0 ) :
	$rating = round( $product->get_average_rating() );
	?>
	<p class="star">
		<?php for ( $i = 1; $i <= 5; $i ++ ):
			if ( $rating >= $i ): ?>
				★
			<?php else: ?>
				☆
			<?php endif;
		endfor; ?>
	</p>
<?php endif; ?>