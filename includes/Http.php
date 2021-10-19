<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

abstract class Http {

	protected $instance_key;

	abstract public function __construct( string $instance_key );

	abstract public function get_path( string $path = '' ): string;

	abstract public function get_url( string $url = '' ): string;

	public function get_current_uri( bool $rel = false ): string {
		$rel_uri = add_query_arg( null, null );

		return $rel ? $rel_uri : home_url( $rel_uri );
	}

	public function redirect( string $url, ?callable $callback = null ): void {
		header( 'Location: ' . $url );

		if ( $callback ) {
			header_register_callback( $callback );
		}
	}
}
