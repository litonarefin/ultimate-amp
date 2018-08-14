Set Home Page Template
<?php


	do_action('uamp/template/start');

    $this->load_parts(array('header'));

    do_action('uamp/template/sidebar');

    do_action('uamp_post_before_loop');

	if( is_home() && 'posts' == get_option( 'show_on_front' )){

		//print_r('This is Home Posts Page');
		$this->load_parts(array('inc/loop'));

	} elseif(is_home() && 'page' == get_option( 'show_on_front' )){

		$this->load_parts(array('template-parts/front'));

    }


    do_action('uamp_post_after_loop');

    $this->load_parts(array('footer'));

    do_action('uamp/template/end');
