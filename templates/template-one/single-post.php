<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/9/18
	 */
	global $post_id;
	
	$uamp = new AMP_Post_Template($post_id);
	?>

<main id="content" role="main" class="<?php post_class();?>">
	<article class="recipe-article">
		<header>
			<span class="ampstart-subtitle block px3 pt2 mb2">
                <?php do_action('uamp/template/post/meta');?>
            </span>
			<h1 class="mb1 px3">
				<?php echo wp_kses_data( $uamp->get( 'post_title' ) ); ?>
			</h1>
			<!-- Start byline -->
			<address class="ampstart-byline clearfix mb4 px3">

				<?php do_action('uamp/template/post/meta/author');?>

				<div class="amp-wp-meta amp-wp-posted-on">
					<time datetime="<?php echo esc_attr( date( 'c', $uamp->get( 'post_publish_timestamp' ) ) ); ?>">
						<?php
							echo esc_html(
								sprintf(
									_x( '%s ago', '%s = human-readable time difference', 'amp' ),
									human_time_diff( $uamp->get( 'post_publish_timestamp' ), current_time( 'timestamp' ) )
								)
							);
						?>
					</time>
				</div>
			</address>
			<!-- End byline -->

			<?php do_action('uamp/template/featured-image');?>

		</header>

		<div class="mb4 px3">
			<?php echo $uamp->get( 'post_amp_content' ); // amphtml content; no kses
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'uamp' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'uamp' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				) );
			?>

		</div>

		<section class="recipe-comments">
			<h2 class="mb3"><?php comments_number( esc_html('0 Comment' ,'uamp') , esc_html('1 Comment' ,'uamp'), esc_html('% Comments' ,'uamp') );?></h2>

			<ul class="list-reset">
				<?php Ultimate_AMP_Helper::uamp_comments_list();?>
			</ul>

			<a href="<?php Ultimate_AMP_Helper::uamp_comment_link();?>" class="button add-comment text-center mt3">
				<?php echo _e('Add Comment', 'uamp'); ?>
			</a>

		</section>



	</article>
</main>
