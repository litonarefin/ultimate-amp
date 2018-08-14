<?php
	global $uamp_options;
	$excerpt_length = 5;
?>

<main id="content" role="main" class="<?php post_class();?>">

	<?php
		$get_home_object_id = get_option( 'page_on_front' );

		$args = array(
			'post_type'           => 'page',
			'post_count'          => 1,
			'page_id'            => $get_home_object_id
		);

		$query_filter_args = apply_filters('uamp_query_args', $args);
		$query = new WP_Query( $query_filter_args );

		//Place for Blog Title
		if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post(); ?>


			<article class="recipe-article">
				<header>
					<?php /* <span class="ampstart-subtitle block px3 pt2 mb2">
                <?php do_action('uamp/template/home/meta');?>
            </span> */ ?>
					<h1 class="mb1 px3">
							<?php the_title(); ?>
					</h1>
					<!-- Start byline -->
					<address class="ampstart-byline clearfix mb2 px3">

						<div class="amp-wp-meta amp-wp-posted-on">
							<time>
								<?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?>
							</time>
						</div>
					</address>
					<!-- End byline -->


				</header>



				<div class=" px3">
					<?php do_action('uamp/template/featured-image');?>

					<?php

//						echo apply_filters('uamp_modify_index_content', get_the_content(),  $excerpt_length );

						the_content()

					?>


				</div>

			</article>

			<?php wp_reset_query(); } } ?>

</main>

