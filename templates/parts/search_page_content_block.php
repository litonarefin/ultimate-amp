<?php
if ( have_posts() ):
	while ( have_posts() ):
		the_post();
		$id = get_the_ID();
		$this->set_archive_page_post( $id );
		echo $this->render( 'loop-single' );
	endwhile;
else:
	echo $this->render( 'search-none' );
endif;