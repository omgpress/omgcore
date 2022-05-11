<?php

namespace WP_Titan_1_0_13;

defined( 'ABSPATH' ) || exit;

/**
 * Manage file system.
 */
class FS extends Feature {

	public function get_path( string $path = '' ): string {
		if ( $this->is_theme() ) {
			return get_theme_file_path() . DIRECTORY_SEPARATOR . $path;

		} else {
			return plugin_dir_path( $this->app->get_root_file() ) . $path;
		}
	}

	public function get_url( string $url = '', bool $stamp = false ): string {
		if ( $this->is_theme() ) {
			$url = get_theme_file_uri() . '/' . $url;

		} else {
			$url = plugin_dir_url( $this->app->get_root_file() ) . $url;
		}

		if ( $stamp ) {
			return $this->get_url_with_stamp( $url );
		}

		return $url;
	}

	protected function get_url_with_stamp( string $url ): string {
		$path = $this->get_path( $url );

		if ( file_exists( $path ) ) {
			return add_query_arg( $url, array( 'ver' => filemtime( $path ) ) );
		}

		return $url;
	}
}
