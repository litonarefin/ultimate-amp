<?php
/**
 * The Template for render AMP HTML page header
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/header.php.
 *
 * @var $this AMPHTML_Template
 */
?>
<title><?php echo esc_html( $this->doc_title ); ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<link rel="canonical" href="<?php echo $this->get_canonical_url(); ?>">
<?php do_action( 'amphtml_template_head', $this ); ?>
<script async src="https://cdn.ampproject.org/v0.js"></script>
<?php foreach ( $this->get_embedded_elements() as $element ): ?>
	<?php echo $this->render_element( 'extended-component', $element ) ?>
<?php endforeach; ?>
<?php echo $this->render( 'amp-boilerplate' ) ?>
<?php if ( $this->favicon ): ?>
	<link rel="shortcut icon" href="<?php echo $this->favicon; ?>"/>
<?php endif; ?>
<style amp-custom>
	<?php echo ( !$this->options->get('rtl_enable') )
		? $this->get_style( 'style' ) : $this->get_style( 'rtl-style' ); ?>
	<?php do_action( 'amphtml_template_css', $this ); ?>
</style>
<script type="application/ld+json"><?php echo json_encode( $this->metadata ); ?></script>