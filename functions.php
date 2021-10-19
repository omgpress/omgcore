<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

function crash( string $message, string $title = '' ): void {
	$message = '<h2>Error' . ( $title ? ( ': ' . esc_html( $title ) ) : '' ) . '</h2>' . $message;
	$title   = 'Error: ' . esc_html( $title ?? $message );

	wp_die( $message, $title ); // phpcs:ignore
}
