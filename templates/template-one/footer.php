<?php 
    global $post_id;
    $uamp = new AMP_Post_Template($post_id);
?>
    <footer class="ampstart-footer flex flex-column items-center px3 ">
        <nav class="ampstart-footer-nav">
            <ul class="list-reset flex flex-wrap mb3">
                <li class="px1"><a class="text-decoration-none ampstart-label" href="#">About</a></li>
                <li class="px1"><a class="text-decoration-none ampstart-label" href="#">Contact</a></li>
                <li class="px1"><a class="text-decoration-none ampstart-label" href="#">Terms</a></li>
            </ul>
        </nav>
        <small>
            © Your Company, 2016
        </small>
    </footer>
	<?php do_action( 'amp_post_template_footer', $uamp ); ?>
</body>
</html>