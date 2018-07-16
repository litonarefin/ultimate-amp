<?php
/**
 * The Template for including AMP HTML analytics component
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/yandex-metrika.php.
 *
 * @var $this AMPHTML_Template
 */
$ya_metrica = $this->get_yandex_metrika(); ?>
<amp-analytics type="metrika" id="yandex-metrika">
	<script type="application/json"><?php echo json_encode( $ya_metrica ); ?></script>
</amp-analytics>