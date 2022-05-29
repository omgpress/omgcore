<?php

namespace WP_Titan_1_0_21\Core;

defined( 'ABSPATH' ) || exit;

class Http extends Feature {

	public function get_current_url( bool $rel = false ): string {
		$rel_path = add_query_arg( null, null );

		return $rel ? $rel_path : $this->get_home_url( $rel_path );
	}

	public function get_home_url( string $path = '/', bool $base = false ): string {
		return $this->core->hook()->apply_filters( 'home_url', home_url( $path ), $path, $base );
	}

	public function get_root_host(): string {
		$host  = wp_parse_url( $this->get_home_url() )['host'];
		$parts = explode( '.', $host );

		return end( $parts );
	}

	public function redirect( string $url, ?callable $callback = null ): void {
		header( "Location: $url" );

		if ( $callback ) {
			header_register_callback( $callback );
		}
	}
}
