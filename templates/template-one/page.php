<?php
	do_action('uamp/template/start');

	$this->load_parts(array('header'));

	do_action('uamp/template/sidebar');

	do_action('uamp_post_before_loop');

	$this->load_parts(array('template-parts/page'));

	do_action('uamp_post_after_loop');

	$this->load_parts(array('footer'));

	do_action('uamp/template/end');