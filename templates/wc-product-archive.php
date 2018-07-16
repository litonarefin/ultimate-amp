<?php
/**
 * The Template for displaying WooCommerce Archive Pages
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/wc-product-archive.php.
 *
 * @var $this AMPHTML_Template
 */
$view = $this->options->get( 'wc_archives_view' );
?>
	<header class="page-header">
		<?php if ( $this->options->get( 'wc_archive_breadcrumbs' ) ): ?>
			<?php echo $this->render( 'breadcrumb' ); ?>
		<?php endif; ?>
		<h1 class="amphtml-title"><?php woocommerce_page_title(); ?></h1>
		<?php if ( $this->options->get( 'wc_archives_desc' ) ): ?>
			<?php echo term_description(); ?>
		<?php endif; ?>
		<?php if ( $this->options->get( 'wc_archive_original_btn_block' ) ): ?>
			<?php echo $this->render( 'wc_archive_original_btn_block' ); ?>
		<?php endif; ?>
	</header>
	<div <?php if ( $view === 'grid' ): ?>id="wc-archive-wrap"<?php endif; ?>>
		<?php
		if ( have_posts() ):
			while ( have_posts() ): the_post();
				$id = get_the_ID();
				$this->set_archive_page_post( $id );
				echo $this->render( 'wc-content-product' );
			endwhile;
		endif;
		?>
	</div>
<?php echo $this->render( 'pagination' ) ?>