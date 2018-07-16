<?php
/**
 *
 * @var $this AMPHTML_Template
 */
if ( comments_open() || get_comments_number() ) :
	wp_reset_query();
	ob_start();
	comments_template();
	$comments = ob_get_clean();
	echo $this->get_sanitize_obj()->sanitize_content( $comments );
endif;