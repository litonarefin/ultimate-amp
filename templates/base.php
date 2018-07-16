<?php
/**
 * The Base Template for displaying AMP HTML page.
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/base.php.
 *
 * @var $this AMPHTML_Template
 */

ob_start();
do_action( 'amphtml_template_content' );
$content = ob_get_clean();

ob_start();
echo $this->get_footer();
$footer = ob_get_clean();

$rtl = $this->options->get( 'rtl_enable' );
do_action( 'amphtml_before_header' );
?>
<!doctype html>
<html amp <?php echo $this->get_language_attributes() ?>>
<head>
	<?php echo $this->render( 'header' ) ?>
	<!--WP AMP plugin ver.<?php echo AMPHTML()->version ?>-->
</head>
<body<?php echo $rtl ? ' class="rtl"' : ''; ?>>
<?php if ( $this->options->get( 'header_menu' ) ): ?>
	<?php if ( $this->options->get( 'header_menu_type' ) == 'sidebar' ): ?>
		<amp-sidebar id='amp-sidebar' layout="nodisplay" side="right">
			<amp-img class='amp-close-image'
			         src="<?php echo plugins_url( 'img/ic_close_black_18dp_2x.png', dirname( __FILE__ ) ); ?>"
			         width="20"
			         height="20"
			         alt="close sidebar"
			         on="tap:amp-sidebar.close"
			         role="button"
			         tabindex="0">
			</amp-img>
			<?php echo $this->nav_menu(); ?>
		</amp-sidebar>
	<?php endif; ?>
<?php endif; ?>
<div class="wrapper" id="top">
	<nav class="amphtml-title">
		<?php echo $this->render( 'nav' ) ?>
	</nav>
	<div id="main">
		<div class="inner">
			<?php echo $content; ?>
		</div>
	</div>
	<div class="footer">
		<?php do_action( 'amphtml_footer_logo' ); ?>
		<?php if ( $footer ): ?>
			<div class="inner">
				<?php echo $footer; ?>
				<?php if ( $scroll = $this->get_scrolltop() ): ?>
					<span class="scrolltop-btn"><a href="#top"><?php _e( $scroll, 'amphtml' ); ?></a></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php do_action( 'amphtml_footer_bottom' ); ?>
	</div>
</div>
<?php do_action( 'amphtml_after_footer' ); ?>
</body>
</html>