<?php

namespace WP_Titan_1_0_3;

defined( 'ABSPATH' ) || exit;

/**
 * Helpers for working with URLs and redirects.
 */
class Http extends Feature {

	public function get_current_url( bool $rel = false ): string {
		$rel_path = add_query_arg( null, null );

		return $rel ? $rel_path : $this->get_home_url( $rel_path );
	}

	public function get_home_url( string $path = '/', bool $base = false ): string {
		return $this->core->hook()->apply_filters( 'home_url', home_url( $path ), $path, $base );
	}

	public function get_tel_url( string $tel ): string {
		return 'tel:' . str_replace( array( '-', ' ', '(', ')' ), '', $tel );
	}

	public function redirect( string $url, ?callable $callback = null ): App {
		header( 'Location: ' . $url );

		if ( $callback ) {
			header_register_callback( $callback );
		}

		return $this->app;
	}
}
