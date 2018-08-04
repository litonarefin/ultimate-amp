<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 7/26/18
	 */

do_action('uamp/template/start');

do_action('uamp/template/header');

do_action('uamp/template/sidebar');

do_action('uamp_post_before_loop');

do_action('uamp/template/search/query');

do_action('uamp_post_after_loop');

do_action('uamp/template/footer');

do_action('uamp/template/end');