<?php
/**
 * The Template for displaying AMP HTML ad code
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/ad.php.
 *
 * @var $this AMPHTML_Template
 */
if ( 'adsense_auto' == $this->ad['type'] ){
	$this->add_embedded_element( array (
		'slug' => 'amp-auto-ads',
		'src'  => 'https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js'
	) );
} else {
	$this->add_embedded_element( array (
		'slug' => 'amp-ad',
		'src'  => 'https://cdn.ampproject.org/v0/amp-ad-0.1.js'
	) );
}
?>
<?php if ( 'other' == $this->ad['type'] ): ?>
	<?php echo $this->ad['custom_code'] ?>
<?php elseif ( 'adsense_auto' == $this->ad['type'] ): ?>
<?php else: ?>
	<p class="amphtml-ad">
		<amp-ad type="<?php echo $this->ad['type'] ?>"
		        width=<?php echo $this->ad['width'] ?>
		        height=<?php echo $this->ad['height'] ?>
		        layout=<?php echo $this->ad['layout'] ?>
		        <?php if ( 'doubleclick' == $this->ad['type'] ): ?>
		        data-slot="<?php echo $this->ad['data_slot'] ?>"
		<?php elseif ( 'adsense' == $this->ad['type'] ): ?>
			data-ad-client="<?php echo $this->ad['data_client']; ?>"
			data-ad-slot="<?php echo $this->ad['data_ad_slot']; ?>"
		<?php endif; ?>
		></amp-ad>
	</p>
<?php endif; ?>