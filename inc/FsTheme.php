<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class FsTheme extends Fs {
	/**
	 * Returns the URL of the theme directory.
	 *
	 * @param string $rel Optional. Relative path to append to the URL.
	 * @param bool $stamp Optional. Whether to append a file modification timestamp.
	 *
	 * @return string The full URL of the theme directory or file.
	 */
	public function get_url( string $rel = '', bool $stamp = false ): string {
		$url = get_theme_file_uri( $rel );

		if ( $stamp ) {
			$path = $this->get_path( $rel );

			if ( ! file_exists( $path ) ) {
				return $url;
			}

			return add_query_arg( array( 'ver' => filemtime( $path ) ), $url );
		}

		return $url;
	}

	/**
	 * Returns the absolute path of the theme directory.
	 *
	 * @param string $rel Optional. Relative path to append to the directory path.
	 *
	 * @return string The full path of the theme directory or file.
	 */
	public function get_path( string $rel = '' ): string {
		$path = get_stylesheet_directory() . '/';

		return $rel ? "$path{$rel}" : rtrim( $path, '/\\' );
	}
}
