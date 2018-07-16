<?php
/**
 * The Template for displaying AMP HTML header logo and navigation menu
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/nav.php.
 *
 * @var $this AMPHTML_Template
 */
?>
<div class="header">
	<div class="logo">
		<a href="<?php echo esc_url( $this->get_logo_link() ); ?>">
			<?php if ( in_array( $this->options->get( 'logo_opt' ), array (
					'icon_logo',
					'icon_an_text'
				) ) && $this->logo
			) :
				if ( $img_obj = json_decode( $this->logo ) ) {
					$logo_url = $img_obj->url;
				} else {
					$logo_url = $this->logo;
				}
				?>
				<?php if ( $logo_url ): ?>
				<amp-img src="<?php echo esc_url( $logo_url ); ?>" width="32" height="32" alt="logo"
				         class="amphtml-site-icon"></amp-img>
			<?php endif; ?>
			<?php endif; ?>
			<?php if ( in_array( $this->options->get( 'logo_opt' ), array ( 'image_logo' ) ) && $this->logo ) :
				// for compatibility with older versions
				if ( $img_obj = json_decode( $this->logo ) ) {
					$logo_url    = $img_obj->url;
					$logo_height = $img_obj->height;
					$logo_width  = $img_obj->width;
					$logo_alt    = $img_obj->alt;
				} else {
					$logo_url    = $this->logo;
					$size        = $this->get_image_size_from_url( $this->logo );
					$logo_height = $size['height'];
					$logo_width  = $size['width'];
					$logo_alt    = 'logo';
				}
				?>
				<amp-img src="<?php echo $logo_url; ?>" width="<?php echo $logo_width; ?>"
				         height="<?php echo $logo_height; ?>" alt="<?php echo $logo_alt; ?>"
				         class="amphtml-site-icon"></amp-img>
			<?php endif; ?>
			<?php if ( in_array( $this->options->get( 'logo_opt' ), array (
					'text_logo',
					'icon_an_text'
				) ) && $this->blog_name
			): ?>
				<?php echo esc_html( $this->blog_name ); ?>
			<?php endif; ?>
		</a>
	</div>
	<?php if ( $this->options->get( 'header_menu' ) ): ?>
		<?php
		if ( $this->options->get( 'header_menu_button' ) == 'icon' ) {
			$css_class = 'icon-button';
		} else {
			$css_class = 'text-button';
		}
		?>
		<?php if ( $this->options->get( 'header_menu_type' ) == 'accordion' ): ?>
			<amp-accordion>
				<section class="<?php echo $css_class ?>-section">
					<h4 class="accordion-header hamburger <?php echo $css_class ?>">
						<?php if ( $this->options->get( 'header_menu_button' ) == 'text' ) {
							_e( 'Menu', 'amphtml' );
						} ?>
					</h4>
					<div class="main-navigation">
						<?php echo $this->nav_menu(); ?>
					</div>
				</section>
			</amp-accordion>
		<?php else: ?>
			<div class="accordion-header hamburger <?php echo $css_class ?>-section">
				<button class="amp-menu-sidebar <?php echo $css_class ?>"
				        on='tap:amp-sidebar.toggle'><?php if ( $this->options->get( 'header_menu_button' ) == 'text' ) {
						_e( 'Menu', 'amphtml' );
					} ?></button>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>