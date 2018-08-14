<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/9/18
	 */
	?>

<main id="content" role="main" <?php post_class();?>>
	<article class="recipe-article">
		<header>
			<span class="ampstart-subtitle block px3 pt2 mb2">
                <?php //$this->load_parts( array('parts/meta-taxonomy' )); ?>
            </span>
			<h1 class="mb1 px3">
				<?php echo wp_kses_data( $this->get( 'post_title' ) ); ?>
			</h1>
			<!-- Start byline -->
			<address class="ampstart-byline clearfix mb4 px3">

				<?php $this->load_parts( array('parts/meta-author' ));?>

				<div class="amp-wp-meta amp-wp-posted-on">
					<time datetime="<?php echo esc_attr( date( 'c', $this->get( 'post_publish_timestamp' ) ) ); ?>">
						<?php
							echo esc_html(
								sprintf(
									_x( '%s ago', '%s = human-readable time difference', 'amp' ),
									human_time_diff( $this->get( 'post_publish_timestamp' ), current_time( 'timestamp' ) )
								)
							);
						?>
					</time>
				</div>
			</address>
			<!-- End byline -->

			<?php $this->load_parts( array('parts/featured-image' )); ?>

		</header>

		<div class="mb4 px3">
			<?php echo $this->get( 'post_amp_content' ); // amphtml content; no kses
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

			<div class="center">
				<a href="<?php Ultimate_AMP_Helper::uamp_comment_link();?>" class="button center add-comment mt3">
					<?php echo _e('Add Comment', 'uamp'); ?>
				</a>
			</div>

		</section>



	</article>
</main>
