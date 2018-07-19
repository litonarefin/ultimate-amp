<?php
/**
 * The Template for displaying AMP HTML ad shortcode
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/ad-shortcode.php.
 *
 * @var $this AMPHTML_Template
 */
?>
<p>
	<amp-ad type="<?php echo $this->shortcode_atts['type'] ?>"
	        width=<?php echo $this->shortcode_atts['width'] ?>
	        height=<?php echo $this->shortcode_atts['height'] ?>
	        layout=<?php echo $this->shortcode_atts['layout'] ?>
	        <?php if ( 'doubleclick' == $this->shortcode_atts['type'] ): ?>
	        data-slot="<?php echo $this->shortcode_atts['data-slot'] ?>"
	<?php elseif ( 'adsense' == $this->shortcode_atts['type'] ): ?>
		data-ad-client="<?php echo $this->shortcode_atts['data-ad-client'] ?>"
		data-ad-slot="<?php echo $this->shortcode_atts['data-ad-slot'] ?>"
	<?php endif; ?>
	></amp-ad>
</p>