<?php

namespace WP_Titan_0_9_1;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WP_Titan_0_9_1\App' ) ) {
	return;
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

function wpt_die( string $message, ?string $title = null, ?string $key = null, bool $enable_backtrace = true, bool $is_core = true ): void {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		remove_filter( 'wp_robots', 'wp_robots_noindex_search' );
		remove_filter( 'wp_robots', 'wp_robots_noindex_embeds' );
	}

	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		ob_start();
		?>
		<h2>Error</h2>
		<p>Something went wrong. Enable <code>WP_DEBUG</code> to see complete information.</p>
		<p>
			<a href="https://wordpress.org/support/article/debugging-in-wordpress/" target="_blank">Read about debugging in WordPress</a>
		</p>
		<?php
		wp_die( ob_get_clean(), 'Error' ); // phpcs:ignore
	}

	$backtrace = $enable_backtrace ? array_values(
		array_filter(
			array_map(
				function ( array $caller ) {
					if ( isset( $caller['class'] ) ) {
						$caller_namespace = explode( '\\', $caller['class'] )[0];

					} else {
						$caller_namespace = explode( '\\', $caller['function'] )[0];
					}

					if ( __NAMESPACE__ === $caller_namespace ) {
						return false;

					} else {
						return array(
							'file'     => $caller['file'],
							'line'     => $caller['line'],
							'function' => $caller['function'],
							'class'    => $caller['class'] ?? null,
						);
					}
				},
				debug_backtrace() // phpcs:ignore
			)
		)
	) : array();

	ob_start();
	?>
	<h2>Error<?php echo $title ? ( ': ' . esc_html( $title ) ) : ''; ?></h2>
	<p><?php echo wp_kses_post( $message ); ?></p>

	<?php if ( $backtrace ) { ?>
		<div style="margin-top: 25px;">
			<h4 style="margin-bottom: 0;">Backtrace:</h4>
			<ul style="margin: 15px 0 0; padding-left: 0; list-style: none; color: #9b9b9b;">
				<?php foreach ( $backtrace as $caller ) { ?>
					<li style="font-size: 12px;">
						<?php echo esc_html( $caller['file'] . ':' . $caller['line'] . ': ' ); ?>
						<code style="color: #444;">
							<?php echo esc_html( ( $caller['class'] ? ( $caller['class'] . '::' ) : '' ) . $caller['function'] ); ?>
						</code>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php
	}

	if ( $key || $is_core ) {
		?>
		<hr style="margin-top: 35px; border-top: 1px solid #dadada; border-bottom: 0;">
		<p style="margin-top: 15px; font-size: 12px; color: #9b9b9b;">
			This error message comes from <a href="https://github.com/dpripa/wp-titan" target="_blank" style="color: #9b9b9b;">WP Titan</a>.
			<br>
			<?php if ( $key ) { ?>
				Instance: <code style="color: #444;"><?php echo esc_html( $key ); ?></code>
				&ensp;|&ensp;
			<?php } ?>
			Source: <code style="color: #444;"><?php echo $is_core ? 'core' : 'project'; ?></code>
		</p>
		<?php
	}

	wp_die( ob_get_clean(), $title ); // phpcs:ignore
}
