<?php

namespace WP_Titan_1_1_2;

defined( 'ABSPATH' ) || exit;

/**
 * Manage file system.
 */
class FS extends Feature {

	public function get_path( string $path = '' ): string {
		return $this->core->fs()->get_app_path( $path );
	}

	public function get_url( string $url = '', bool $stamp = false ): string {
		return $this->core->fs()->get_app_url( $url, $stamp );
	}
}
