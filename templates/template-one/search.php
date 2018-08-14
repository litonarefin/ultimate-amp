<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/26/18
	 */

do_action('uamp/template/start');

$this->load_parts(array('header'));

do_action('uamp/template/sidebar');

do_action('uamp_post_before_loop');

$this->load_parts(array('template-parts/search'));

do_action('uamp_post_after_loop');

$this->load_parts(array('footer'));

do_action('uamp/template/end');
