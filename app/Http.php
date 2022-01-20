<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

class Http extends Feature {

	public function get_current_url( bool $rel = false ): string {
		$rel_url = add_query_arg( null, null );

		return $rel ? $rel_url : home_url( $rel_url );
	}

	public function redirect( string $url, ?callable $callback = null ): void {
		header( 'Location: ' . $url );

		if ( $callback ) {
			header_register_callback( $callback );
		}
	}
}
