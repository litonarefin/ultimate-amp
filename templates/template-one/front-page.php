<?php do_action('uamp/template/start'); ?>

<?php do_action('uamp/template/header'); ?>

<?php do_action('uamp/template/sidebar'); ?>

<?php do_action('uamp_post_before_loop'); ?>

<?php
	$frontpage_id = get_option( 'page_on_front' );

	$blog_id = get_option( 'page_for_posts' );
?>

<?php do_action('uamp/template/home/loop'); ?>

<?php do_action('uamp_post_after_loop'); ?>

<?php do_action('uamp/template/footer'); ?>

<?php do_action('uamp/template/end'); ?>