<?php

namespace WP_Titan_1_0_5;

defined( 'ABSPATH' ) || exit;

const ROOT_FILE  = __FILE__;
const H_PRIORITY = 1;
const PRIORITY   = 10;
const L_PRIORITY = 999999;

if ( ! class_exists( 'WP_Titan_1_0_5\App' ) ) {
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
}

if ( ! function_exists( 'wpt_generate_random_str' ) ) {
	function wpt_generate_random_str( int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ): string {
		$pieces = array();
		$max    = mb_strlen( $keyspace, '8bit' ) - 1;

		for ( $i = 0; $i < $length; ++ $i ) {
			$pieces[] = $keyspace[ random_int( 0, $max ) ];
		}

		return implode( '', $pieces );
	}
}

if ( ! function_exists( 'wpt_die' ) ) {
	function wpt_die( string $message, ?string $title = null, ?string $key = null, bool $enable_backtrace = true, bool $is_core = true, $footer_text = null ): void {
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
					function ( array $caller ) /* mixed */ {
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
				<?php echo empty( $footer_text ) ? 'This error message comes from <a href="https://github.com/dpripa/wp-titan" target="_blank" style="color: #9b9b9b;">WP Titan</a>.' : wp_kses_post( $footer_text ); ?>
				<br>
				<?php if ( $key ) { ?>
					Instance: <code style="color: #444;"><?php echo esc_html( $key ); ?></code>
					&ensp;|&ensp;
				<?php } ?>
				Level: <code style="color: #444;"><?php echo $is_core ? 'core' : 'app'; ?></code>
			</p>
			<?php
		}

		wp_die( ob_get_clean(), $title ); // phpcs:ignore
	}
}
