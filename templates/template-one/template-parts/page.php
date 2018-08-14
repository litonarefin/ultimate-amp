<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/9/18
	 */
	global $uamp_options;
	?>

<main id="content" role="main" <?php post_class();?>>
	<article class="recipe-article">
		<header>
			<span class="ampstart-subtitle block px3 pt2 mb2">
                <?php do_action('uamp/template/post/meta');?>
            </span>
			<h1 class="mb1 px3">
				<?php echo wp_kses_data( $this->get( 'post_title' ) ); ?>
			</h1>
			<!-- Start byline -->
			<address class="ampstart-byline clearfix mb4 px3">

				<?php do_action('uamp/template/post/meta/author');?>

				<div class="amp-wp-meta amp-wp-posted-on">
					<time datetime="<?php echo esc_attr( date( 'c', $this->get( 'post_publish_timestamp' ) ) ); ?>">
						<?php
							echo esc_html(
								sprintf(
									_x( '%s ago', '%s = human-readable time difference', 'amp' ),
									human_time_diff( $this->get( 'post_publish_timestamp' ), current_time( 'timestamp'
                                    ) )
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

                <?php if($uamp_options['uamp_pages_ajax_comment']=="disable"){?>

                    <a href="<?php Ultimate_AMP_Helper::uamp_comment_link();?>" class="button center add-comment mt3">
		                <?php uamp_pages_comment_button(); ?>
                    </a>

                <?php } else{ ?>




                <div class="button center add-comment mt3">
	                <?php uamp_pages_comment_button(); ?>
                </div>

                <form
                        method="post"
                        id="commentform"
                        class="comment-form"
                        novalidate=""
                        action-xhr="<?php echo admin_url('admin-ajax.php?action=uamp_comment_submit') ?>"
                        target="_top">
                    <p class="comment-notes">
                        <span id="email-notes">Your email address will not be published.</span> Required fields are marked <span class="required">*</span>
                    </p>
                    <p class="comment-form-comment">
                        <label for="comment">Comment</label>
                        <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea>
                    </p>
                    <p class="comment-form-author">
                        <label for="author">Name <span class="required">*</span></label>
                        <input id="author" name="author" type="text" value="" maxlength="245" aria-required="true" required="required" />
                    </p>
                    <p class="comment-form-email">
                        <label for="email">Email <span class="required">*</span></label>
                        <input id="email" name="email" type="email" value="" maxlength="100" aria-describedby="email-notes" aria-required="true" required="required" />
                    </p>
                    <p class="comment-form-url">
                        <label for="url">Website</label>
                        <input id="url" name="url" type="url" value="" maxlength="200" />
                    </p>
                    <p class="form-submit">
                        <input name="submit" type="submit" id="submit" class="submit" value="Post Comment" />
                        <input type="hidden" name="comment_post_ID" value="1" id="comment_post_ID" /><br/>
                        <input type="hidden" name="comment_parent" id="comment_parent" value="0" />
                    </p>
                    <div submit-error>
                        <template type="amp-mustache">
                            {{msg}}
                        </template>
                    </div>
                </form>


                <?php } ?>


			</div>

		</section>



	</article>
</main>
