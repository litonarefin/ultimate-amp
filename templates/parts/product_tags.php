<?php
/**
 * @var $this AMPHTML_Template
 * @var $product WC_Product
 */
$tag_count = sizeof( get_the_terms( $this->post->ID, 'product_tag' ) );
echo get_the_term_list( $this->post->ID, 'product_tag', '<p class="amphtml-tagged-as">' . _n( 'Tag:', 'Tags:', $tag_count, 'amphtml' ) . ' ', ', ', '</p>' );