<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WP_Titan_0_9_0\App' ) ) {
	return;
}

function _die( string $message, ?string $title = null, ?string $key = null, bool $is_core = true ): void {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		remove_filter( 'wp_robots', 'wp_robots_noindex_search' );
		remove_filter( 'wp_robots', 'wp_robots_noindex_embeds' );
	}

	ob_start();
	?>
	<h2>Error<?php echo $title ? ( ': ' . esc_html( $title ) ) : ''; ?></h2>
	<p><?php echo wp_kses_post( $message ); ?></p>
	<hr style="margin-top: 35px; border-top: 1px solid #dadada; border-bottom: 0;">
	<p style="margin-top: 15px; font-size: 12px; color: #9b9b9b;">
		This error message comes from <a href="https://github.com/dpripa/wp-titan" target="_blank" style="color: #9b9b9b;">WP Titan</a>.
		<br>
		Instance: <code style="color: #444;"><?php echo esc_html( $key ); ?></code>
		&ensp;|&ensp;
		Source: <code style="color: #444;"><?php echo $is_core ? 'core' : 'project'; ?></code>
	</p>
	<?php
	wp_die( ob_get_clean(), $title ); // phpcs:ignore
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
