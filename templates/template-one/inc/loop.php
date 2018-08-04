<?php
	global $uamp_options;
	$excerpt_length = 5;
?>

<main id="content" role="main" class="<?php post_class();?>">

	<?php
	$count = 1;
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var('paged');
	} elseif ( get_query_var( 'page' ) ) {
		$paged = get_query_var('page');
	} else {
		$paged = 1;
	}

	$exclude_ids = ''; // If User wants to Exclude Post ID's

	$args = array(
		'post_type'           => 'post',
		'orderby'             => 'date',
		'paged'               => esc_attr($paged),
//		'post__not_in' 		  => $exclude_ids,
		'has_password' 		  => false ,
		'post_status'		  => 'publish'
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
			<h2 class="mb1 px3">
                <a href="<?php the_permalink();?>">
                    <?php the_title(); ?>
                </a>
            </h2>
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
//				if(has_excerpt()){
//					$content = get_the_excerpt();
//				}else{
//					$content = get_the_content();
//				}
				$content = get_the_excerpt();

				echo apply_filters('uamp_modify_index_content', $content,  $excerpt_length );

			?>


		</div>

	</article>

	<?php wp_reset_query(); } } ?>

</main>

