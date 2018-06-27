<!doctype html>
<html <?php TemplateManager::uamp_language_attributes(); ?> amp>
<head>
    <meta charset="utf-8">
    <link rel="dns-prefetch" href="https://cdn.ampproject.org">
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1">
    <meta name="theme-color" content="<?php echo '#0379c4'; ?>">

	<?php do_action('amp_post_template_head', $uamp); ?>

	<?php do_action('uamp/template/head'); ?>

</head>
<body <?php TemplateManager::uamp_body_class();?>>

    <header class="ampstart-headerbar fixed flex justify-start items-center top-0 left-0 right-0 pl2 pr4 ">
        <div role="button" aria-label="open sidebar" on="tap:header-sidebar.toggle" tabindex="0" class="ampstart-navbar-trigger  pr2  ">☰
        </div>
        <amp-img src="../img/blog/logo.png" width="100" height="61.3" layout="fixed" class="my0 mx-auto " alt="The Blog"></amp-img>
    </header>