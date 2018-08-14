Not set, This is Front Page

<?php
	$li_id = get_option( 'page_on_front' );
	print_r($li_id);

	if( is_home() && 'posts' == get_option( 'show_on_front' )){
		print_r('This is Home Posts Page');
	}

	if( ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_on_front' ) && is_page( get_option( 'page_on_front' ) ) ) ){
		print_r('This is Frontsss Page
');
	}

	if(is_home() && 'page' == get_option( 'show_on_front' )){
		print_r('This is isPosts Page
');
	}

?>


<?php
    do_action('uamp/template/start');

    $this->load_parts(array('header'));

    do_action('uamp/template/sidebar');

    do_action('uamp_post_before_loop');

//    $this->load_parts(array('inc/loop'));
	$this->load_parts(array('template-parts/front'));



	do_action('uamp_post_after_loop');

    $this->load_parts(array('footer'));

    do_action('uamp/template/end');

