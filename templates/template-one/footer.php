<?php
	global $uamp_options;

	$uamp_is_amp = $uamp_options['uamp_is_amp'];
	$url = AMP_Theme_Support::get_current_canonical_url();

    if ( $uamp_is_amp == "enable" ) {
        $url = add_query_arg( array (
            'desktop-redirect' => '1',
        ), $url );
    }
?>

    <footer class="ampstart-footer flex flex-column items-center px3 ">
        <nav class="ampstart-footer-nav">
            <?php
                if ( has_nav_menu( 'uamp-footer-menu' ) ) {
                    echo str_replace('<li class="', '<li class="px1 ',
                        wp_nav_menu(
                            [
                                'theme_location' => 'uamp-footer-menu',
                                'container' => false,
                                'items_wrap' => '<ul class="list-reset flex flex-wrap mb3">%3$s</ul>',
                                'depth' => 1,
                                'menu_id' => 'footer-menu',
                                'echo' => false
                            ]
                        )
                    );
                }
            ?>
        </nav>

        <div class="uamp-button">

            <a href="<?php echo $url;?>">
	            <?php uamp_redirect_to_desktop_version();?>
            </a>

        </div>
        <p>
            <?php uamp_footer_copyright_text();?>
        </p>
    </footer>
	<?php do_action( 'amp_post_template_footer', $uamp ); ?>
</body>
</html>