<?php
$this->add_embedded_element( array (
		'slug' => 'amp-accordion',
		'src'  => 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js'
	) );
$product = wc_get_product( get_the_ID() );
$tabs    = woocommerce_default_product_tabs();
?>
<div class="ampcontent">
	<amp-accordion>
		<?php if ( $this->get_option( 'product_desc' ) && $this->content && isset( $tabs['description'] ) ): ?>
			<section expanded>
				<h4><?php echo $tabs['description']['title']; ?></h4>
				<div class="section-content"><?php echo $this->content; ?></div>
			</section>
		<?php endif; ?>

		<?php if ( $this->get_option( 'product_attributes' ) && isset( $tabs['additional_information'] ) ): ?>
			<section>
				<h4><?php echo $tabs['additional_information']['title']; ?></h4>
				<div class="section-content"><?php $product->list_attributes(); ?></div>
			</section>
		<?php endif; ?>

		<?php if ( $this->get_option( 'product_reviews' ) && isset( $tabs['reviews'] ) ): ?>
			<section>
				<h4><?php echo $tabs['reviews']['title']; ?></h4>
				<div id="comments" class="section-content comments-area">
					<?php
					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) ) {
						printf( _n( '%s review for %s%s%s', '%s reviews for %s%s%s', $count, 'amphtml' ), $count, '<span>', get_the_title(), '</span>' );
					} else {
						_e( 'Reviews', 'amphtml' );
					}

					$comments = get_comments( array ( 'post_id' => $product->get_id() ) );
					if ( count( $comments ) > 0 ) : ?>
						<ul class="comment-list amp-reviews">
							<?php
							wp_reset_query();
							ob_start();
							wp_list_comments( '', $comments );
							$comments = ob_get_clean();
							echo $this->get_sanitize_obj()->sanitize_content( $comments );
							?>
						</ul>
					<?php else : ?>
						<p class="woocommerce-noreviews"><?php _e( 'There are no reviews yet.', 'amphtml' ); ?></p>
					<?php endif; ?>

					<div class="amp-button-holder">
						<a href="<?php echo $this->get_canonical_url(); ?>#comments" class="amp-button">
							<?php _e( 'Add a review', 'amphtml' ); ?>
						</a>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</amp-accordion>
</div>