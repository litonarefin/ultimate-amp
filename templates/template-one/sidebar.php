<?php global $uamp_options;?>

<amp-sidebar id="header-sidebar" class="ampstart-sidebar px3" layout="nodisplay">
	<div class="flex justify-start items-center ampstart-sidebar-header">
		<div role="button" aria-label="close sidebar" on="tap:header-sidebar.toggle" tabindex="0" class="ampstart-navbar-trigger items-start">✕</div>
	</div>

    <?php
		if ( has_nav_menu( 'uamp-main-menu' ) ) {
			wp_nav_menu([
				'theme_location' => 'uamp-main-menu',
				'items_wrap'     => '<nav id="%1$s" itemscope itemtype="http://schema.org/SiteNavigationElement" class="ampstart-sidebar-nav ampstart-nav %2$s"><ul class="list-reset m0 p0 ampstart-label">%3$s</ul></nav>',
				'container'      => 'ul',
				'menu_id'        => 'menu',
                'depth'          => 4,
				'menu_class'     => 'amp-menu',
				'walker'		 => new UAMP_Nav_Menu_Walker()
			]);

		} elseif( is_user_logged_in()){

		    $user_can_edit_menu = current_user_can( 'edit_theme_options' );

			if ( $user_can_edit_menu ) {
				printf( '<a href="%s" class="wrap">', esc_attr( admin_url( '/nav-menus.php?action=locations' ) ) );
			}
			esc_html_e( 'Select "AMP Sidebar" Menu', 'uamp' );
			if ( $user_can_edit_menu ) {
				echo '</a>';
			}
        }

	?>

    <ul class="ampstart-social-follow list-reset flex justify-around items-center flex-wrap m0 mb4">
		<?php uamp_sidebar_socials();?>
	</ul>

</amp-sidebar>