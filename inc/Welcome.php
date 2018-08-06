<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/21/18
	 */

	//namespace Uamp\inc;
?>

	<h1>Welcome To Ultimate AMP</h1>
	<p>Building the future web, together</p>
	<br>
	<h2>Save Permalinks</h2>

	<p>You need to <strong>Save Permalinks</strong> from <em> Settings>Permalinks Menu</em>. Or, Click to <a href="<?php echo
        admin_url('options-permalink.php');?>">Change Permalink</a> to
        <code><?php echo get_bloginfo('url');?>/%post_name%</code></p>
    <p>Your Present Permalink is <code><?php echo get_bloginfo('url');?><?php echo get_option('permalink_structure');?></code></p>