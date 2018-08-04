<amp-carousel class="amp-slider" layout="responsive" type="slides" autoplay>
	<?php

		while( uamp_have_posts() ) {

			uamp_the_post();

			if ( has_post_thumbnail() ):
				?>
				<div>
					<a href="<?php the_permalink() ?>">
						<?php the_post_thumbnail() ?>
					</a>
				</div>
			<?php
			endif;

		}

	?>
</amp-carousel>
