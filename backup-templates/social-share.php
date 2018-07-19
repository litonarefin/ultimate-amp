<?php
$buttons = $this->options->get( 'social_share_buttons' );
$like    = $this->options->get( 'social_like_button' );
?>
<?php if ( $buttons OR $like ): ?>
	<div class="social-box">
		<?php if ( $buttons ): ?>
			<?php foreach ( $buttons as $social_link ): ?>
				<amp-social-share type="<?php echo $social_link ?>"
					<?php if ( 'facebook' == $social_link ) { ?>
						data-param-app_id="145634995501895"
					<?php } ?>
					<?php if ( 'pinterest' == $social_link ) { ?>
						data-do="buttonPin"
					<?php } ?>
					<?php if ( 'whatsapp' == $social_link ) { ?>
						data-share-endpoint="whatsapp://send"
						data-param-text="TITLE - CANONICAL_URL"
					<?php } ?>
				></amp-social-share>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if ( $like ): ?>
			<amp-facebook-like width=100 height=32
			                   layout="fixed"
			                   data-layout="button_count"
			                   data-href="<?php echo $this->get_canonical_url() ?>">
			</amp-facebook-like>
		<?php endif; ?>
	</div>
<?php endif; ?>