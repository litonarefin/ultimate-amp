<?php
/**
 * @var $this AMPHTML_Template
 */
$posts = $this->get_related_posts( $this->post, $this->related_atts['count'] );

if ( $posts && $posts->have_posts() ) : ?>
	<aside>
		<h3><?php echo $this->related_atts['title'] ?></h3>
		<ul>
			<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
				<?php $link = get_permalink( get_the_id() ); ?>
				<li><a href="<?php echo $this->get_amphtml_link( $link ); ?>"
				       title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php endwhile; ?>
		</ul>
	</aside>
	<?php
endif;
wp_reset_query();