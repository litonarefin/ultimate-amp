<?php  global $uamp_options;?>
<!doctype html>
<html <?php Ultimate_AMP_Helper::uamp_language_attributes(); ?> amp>
<head>
    <meta charset="utf-8">
    <link rel="dns-prefetch" href="https://cdn.ampproject.org">
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1">
    <link rel="shortcuts icon" href="<?php echo esc_url( $uamp_options['uamp_favicon']['url'] ); ?>"/>
    <title><?php echo $this->get( 'document_title' );?></title>

	<?php do_action('amp_post_template_head', $this); ?>

	<?php do_action('uamp/template/head');?>

</head>
<body <?php Ultimate_AMP_Helper::uamp_body_class();?>>

    <header itemscope itemtype="https://schema.org/WPHeader" class="ampstart-headerbar fixed flex justify-start items-center top-0 left-0 right-0 pl2 pr4">
        <div role="button" aria-label="open sidebar" on="tap:header-sidebar.toggle" tabindex="0" class="ampstart-navbar-trigger  pr2  ">☰
        </div>


        <?php
			$uamp_logo_width = $uamp_options['uamp_logo_width'];
			$uamp_logo_height = $uamp_options['uamp_logo_height'];

            if( $uamp_options['uamp_logo_type'] == "text" ){ ?>

            <a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
                <span class="amp-site-title">
				    <?php echo esc_html( $uamp_options['uamp_logo_text'] ); ?>
			    </span>
            </a>

        <?php } elseif( $uamp_options['uamp_logo_type'] == "image" ){?>
            <a class="logo" href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
				<?php if ( $uamp_options['uamp_logo_image']['url'] ) { ?>
                    <amp-img src="<?php echo esc_url( $uamp_options['uamp_logo_image']['url'] ); ?>"
                             width="<?php echo $uamp_logo_width;?>"
                             height="<?php echo $uamp_logo_height;?>"
                             layout="fixed"
                             class="my0 mx-auto"></amp-img>
				<?php } ?>
            </a>

		<?php } elseif( $uamp_options['uamp_logo_type'] == "text_image" ){?>


            <a class="logo" href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
				<?php if ( $uamp_options['uamp_logo_image']['url'] ) { ?>
                    <amp-img
                            src="<?php echo esc_url( $uamp_options['uamp_logo_image']['url'] ); ?>"
                            width="<?php echo $uamp_logo_width;?>"
                            height="<?php echo $uamp_logo_height;?>"
                            layout="fixed"
                            class="my0 mx-auto"></amp-img>
				<?php } ?>
                <span class="amp-site-title">
				    <?php echo esc_html( $uamp_options['uamp_logo_text'] ); ?>
			    </span>
            </a>

		<?php } ?>

    </header>