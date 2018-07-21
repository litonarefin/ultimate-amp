<?php 
global $post_id;
$uamp = new AMP_Post_Template($post_id);
?>
<!doctype html>
<html <?php Ultimate_AMP_Helper::uamp_language_attributes(); ?> amp>
<head>
    <meta charset="utf-8">
    <link rel="dns-prefetch" href="https://cdn.ampproject.org">
    <link rel="canonical" href="https://ultimate-amp.app/amp/">
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1">
    <meta name="theme-color" content="<?php echo '#0379c4'; ?>">

	<?php do_action('amp_post_template_head', $uamp); ?>

	<?php do_action('uamp/template/head'); ?>

</head>
<body <?php Ultimate_AMP_Helper::uamp_body_class();?>>

    <header itemscope itemtype="https://schema.org/WPHeader" class="ampstart-headerbar fixed flex justify-start items-center top-0 left-0 right-0 pl2 pr4">
        <div role="button" aria-label="open sidebar" on="tap:header-sidebar.toggle" tabindex="0" class="ampstart-navbar-trigger  pr2  ">☰
        </div>

        <a href="<?php echo esc_url( $uamp->get( 'home_url' ) ); ?>">
            <?php $site_icon_url = $uamp->get( 'site_icon_url' ); ?>
            <?php if ( $site_icon_url ) { ?>
                <amp-img src="<?php echo esc_url( $site_icon_url ); ?>" width="100" height="61.3" layout="fixed" class="my0 mx-auto"></amp-img>
            <?php } ?>
            <span class="amp-site-title">
				<?php echo esc_html( wptexturize( $uamp->get( 'blog_name' ) ) ); ?>
			</span>
        </a>
    </header>