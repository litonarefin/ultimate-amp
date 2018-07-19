<?php
/**
 * The Template for displaying AMP HTML carousel component
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/carousel.php.
 *
 * @var $this AMPHTML_Template
 */
if ( isset( $element ) ): ?>
	<amp-carousel type="slides" layout="responsive" width="<?php echo $element['width'] ?>"
	              height="<?php echo $element['height'] ?>">
		<?php echo $element['images'] ?>
	</amp-carousel>
<?php endif; ?>