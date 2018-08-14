<?php do_action('uamp/template/start'); ?>

<?php $this->load_parts(array('header')); ?>

<?php do_action('uamp/template/sidebar'); ?>

<?php do_action('uamp_post_before_loop'); ?>

<?php $this->load_parts(array('single-post')); ?>

<?php do_action('uamp_post_after_loop'); ?>

<?php $this->load_parts(array('footer')); ?>

<?php do_action('uamp/template/end'); ?>