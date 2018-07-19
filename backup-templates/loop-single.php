<?php
/**
 * The Template for render AMP HTML page loop content
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/loop-single.php.
 *
 * @var $this AMPHTML_Template
 */
$post_link = $this->get_amphtml_link( get_permalink() ); ?>
<div class="amphtml-content">
	<h2 class="amphtml-title">
		<a href="<?php echo $post_link; ?>"
		   title="<?php echo wp_kses_data( $this->title ); ?>">
			<?php echo wp_kses_data( $this->title ); ?>
		</a>
	</h2>
	<?php if ( $this->is_featured_image() ): ?>
		<?php if ( $this->options->get( 'archive_featured_image_link' ) ): ?>
			<a href="<?php echo $post_link; ?>" title="<?php echo wp_kses_data( $this->title ); ?>">
		<?php endif; ?>
		<?php echo $this->render_element( 'image', $this->featured_image ) ?>
		<?php if ( $this->options->get( 'archive_featured_image_link' ) ): ?>
			</a>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( $this->is_enabled_meta() ): ?>
		<ul class="amphtml-meta">
			<?php echo $this->get_post_meta() ?>
		</ul>
	<?php endif; ?>
	<?php if ( $this->is_enabled_excerpt() ): ?>
		<?php echo $this->get_archive_page_description(); ?>
	<?php endif; ?>
</div>