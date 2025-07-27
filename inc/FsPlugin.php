<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class FsPlugin extends Fs {
	protected string $root_file;

	public function __construct( string $root_file ) {
		parent::__construct();

		$this->root_file = $root_file;
	}

	/**
	 * Returns the URL of the plugin directory.
	 *
	 * @param string $rel Optional. Relative path to append to the URL.
	 * @param bool $stamp Optional. Whether to append a file modification timestamp.
	 *
	 * @return string The full URL of the plugin directory or file.
	 */
	public function get_url( string $rel = '', bool $stamp = false ): string {
		$url = plugin_dir_url( $this->root_file );
		$url = $rel ? ( $url . $rel ) : rtrim( $url, '/\\' );

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
	 * Returns the absolute path of the plugin directory.
	 *
	 * @param string $rel Optional. Relative path to append to the directory path.
	 *
	 * @return string The full path of the plugin directory or file.
	 */
	public function get_path( string $rel = '' ): string {
		$path = plugin_dir_path( $this->root_file );

		return $rel ? "$path{$rel}" : rtrim( $path, '/\\' );
	}
}
