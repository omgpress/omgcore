<?php

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpt_die' ) ) {
	function wpt_die( string $message, ?string $title = null, ?string $instance = null ): void {
		$instance = $instance ? ( '<p style="font-size: 12px"><i>WPT instance: <b>' . esc_html( $instance ) . '</b></i></p>' ) : '';
		$message  = '<h2>Error' . ( $title ? ( ': ' . esc_html( $title ) ) : '' ) . '</h2>' . $message . $instance;
		$title    = 'Error' . ( $title ? ( ': ' . esc_html( $title ) ) : '' );

		wp_die( $message, $title ); // phpcs:ignore
	}
}
