<?php
/**
 * The Template for displaying Post meta time
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/meta-time.php.
 *
 * @var $this AMPHTML_Template
 */
$date_format = $this->options->get( 'post_meta_date_format' );

if ( 'none' !== $date_format ): ?>
	<li class="amphtml-meta-posted-on">
		<time datetime="<?php echo esc_attr( date( 'c', $this->publish_timestamp ) ); ?>">
			<?php if ( 'relative' == $date_format ) {
				echo esc_html( sprintf( _x( '%s ago', '%s = human-readable time difference', 'amp' ), human_time_diff( $this->publish_timestamp ) ) );
			} else if ( 'default' == $date_format ) { ?>
				<span class="entry-date">
					<?php echo esc_html( get_the_date() ); ?>
				</span>
				<?php
			} else if ( 'custom' == $date_format ) { ?>
				<span class="entry-date">
					<?php echo date_i18n( $this->options->get( 'post_meta_date_format_custom' ), $this->publish_timestamp ); ?>
				</span>
			<?php }
			?>
		</time>
	</li>
<?php endif; ?>