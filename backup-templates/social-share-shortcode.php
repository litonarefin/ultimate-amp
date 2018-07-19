<?php
$href   = '';
$app_id = '';
$w_send = '';
$w_text = '';
?>
<div class="social-box">
	<?php
	foreach ( $this->share_atts['types'] as $type ):
		if ( 'facebook' == $type ) {
			$href   = 'data-param-href="' . trailingslashit( $this->get_permalink() ) . '"';
			$app_id = 'data-param-app_id="145634995501895"';
		}
		if ( 'whatsapp' == $type ) {
			$w_send = 'data-share-endpoint="whatsapp://send"';
			$w_text = 'data-param-text="' . trailingslashit( $this->get_permalink() ) . '"';
		}
		?>
		<amp-social-share type="<?php echo $type ?>"
		                  width="<?php echo $this->share_atts['width'] ?>"
		                  height="<?php echo $this->share_atts['height'] ?>"
			<?php echo $href . ' ' . $app_id ?>
			<?php echo $w_send . ' ' . $w_text ?>
		>
		</amp-social-share>
	<?php endforeach; ?>
</div>