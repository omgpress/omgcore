<?php

namespace WP_Titan_1_0_1;

defined( 'ABSPATH' ) || exit;

/**
 * Manage file system.
 */
class FS extends Feature {

	public function get_path( string $path = '', bool $parent_theme = false ): string {
		if ( $this->is_theme() ) {
			return ( $parent_theme ? get_parent_theme_file_path() : get_theme_file_path() ) . DIRECTORY_SEPARATOR . $path;

		} else {
			return plugin_dir_path( $this->app->get_root_file() ) . $path;
		}
	}

	public function get_url( string $url = '', bool $use_stamp = false, bool $parent_theme = false ): string {
		if ( $this->is_theme() ) {
			$complete_url = ( $parent_theme ? get_parent_theme_file_uri() : get_theme_file_uri() ) . '/' . $url;

		} else {
			$complete_url = plugin_dir_url( $this->app->get_root_file() ) . $url;
		}

		if ( $use_stamp ) {
			$path = $this->get_path( $url );

			if ( file_exists( $path ) ) {
				return add_query_arg( $complete_url, array( 'ver' => filemtime( $path ) ) );
			}
		}

		return $complete_url;
	}
}
