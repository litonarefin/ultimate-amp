This is Front Page
<?php
//	$current_post_id = get_option( 'page_on_front' );

	$frontpage_id = get_option( 'page_on_front' );

	$blog_id = get_option( 'page_for_posts' );

	print_r($frontpage_id);
	print_r($blog_id);

?>
<?php do_action('uamp/template/start'); ?>

<?php do_action('uamp/template/header'); ?>

<?php do_action('uamp/template/sidebar'); ?>

<?php do_action('uamp_post_before_loop'); ?>

<?php do_action('uamp/template/home/loop'); ?>

<?php do_action('uamp_post_after_loop'); ?>

<?php do_action('uamp/template/footer'); ?>

<?php do_action('uamp/template/end'); ?>