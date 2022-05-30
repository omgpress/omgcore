<?php

namespace WP_Titan_1_1_1\Core;

use const WP_Titan_1_1_1\ROOT_FILE;

defined( 'ABSPATH' ) || exit;

class FS extends Feature {

	public function get_path( string $path = '' ): string {
		return dirname( ROOT_FILE ) . DIRECTORY_SEPARATOR . $path;
	}

	public function get_url( string $url = '', bool $stamp = false ): string {
		if ( $stamp ) {
			return $this->get_url_with_stamp( $url, $this->get_path( $url ) );
		}

		return plugins_url( "/$url", ROOT_FILE );
	}

	public function get_app_path( string $path = '' ): string {
		if ( $this->is_theme() ) {
			return get_theme_file_path() . DIRECTORY_SEPARATOR . $path;

		} else {
			return plugin_dir_path( $this->core->get_app_root_file() ) . $path;
		}
	}

	public function get_app_url( string $url = '', bool $stamp = false ): string {
		$raw_url = $url;

		if ( $this->is_theme() ) {
			$url = get_theme_file_uri() . '/' . $url;

		} else {
			$url = plugin_dir_url( $this->core->get_app_root_file() ) . $url;
		}

		if ( $stamp ) {
			return $this->get_url_with_stamp( $url, $this->get_app_path( $raw_url ) );
		}

		return $url;
	}

	protected function get_url_with_stamp( string $url, string $path ): string {
		if ( file_exists( $path ) ) {
			return add_query_arg( $url, array( 'ver' => filemtime( $path ) ) );
		}

		return $url;
	}
}
