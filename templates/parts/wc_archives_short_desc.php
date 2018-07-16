<?php
$html = apply_filters( 'woocommerce_short_description', $this->post->post_excerpt );
echo $this->get_sanitize_obj()->sanitize_content( $html );