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
			<?php echo $uamp->get( 'post_amp_content' ); // amphtml content; no kses ?>
        </div>

        <section class="recipe-comments">
            <h2 class="mb3">4 Responses</h2>
            <ul class="list-reset">
                <li class="mb4">
                    <h3 class="ampstart-subtitle">Sriram</h3>
                    <span class="h5 block mb3">02.24.17 at 6:01 pm</span>
                    <p>This is perfect for a summer patio party. Thanks for another great one!</p>
                </li>
                <li class="mb4">
                    <h3 class="ampstart-subtitle">Eric</h3>
                    <span class="h5 block mb3">02.24.17 at 5:14 am</span>
                    <p>These were so good I woke up dreaming about them. Regards, Eric.</p>
                </li>
            </ul>
        </section>
	</article>
</main>