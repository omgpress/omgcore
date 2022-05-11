<?php

namespace WP_Titan_1_0_14;

defined( 'ABSPATH' ) || exit;

/**
 * Write content to file.
 */
class Writer extends Feature {

	protected $path;

	/** @ignore */
	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->path = 'uploads' . DIRECTORY_SEPARATOR . $app->get_key() . DIRECTORY_SEPARATOR;
	}

	public function get_path( string $path = '', bool $raw = false ): string {
		return ( $raw ? '' : WP_CONTENT_DIR ) . DIRECTORY_SEPARATOR . $this->path . $path;
	}

	public function add_content( string $file, string $content, bool $private = false ): App {
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			include_once ABSPATH . 'wp-includes' . DIRECTORY_SEPARATOR . 'pluggable.php';
		}

		if ( ! current_user_can( 'delete_posts' ) ) {
			return $this->app;
		}

		$this->create_base_dir();

		if ( 1 < count( explode( DIRECTORY_SEPARATOR, $file ) ) ) {
			$this->create_dir( dirname( $file ), $private );
		}

		$file = fopen( $this->get_path( $file ), 'a' ); // phpcs:ignore

		fwrite( $file, $content ); // phpcs:ignore
		fclose( $file ); // phpcs:ignore

		return $this->app;
	}

	protected function create_dir( string $name, bool $private = false ): void {
		$path = $this->get_path( $name ) . DIRECTORY_SEPARATOR;

		if ( is_dir( $path ) ) {
			return;
		}

		mkdir( $path, 0755, true );

		if ( ! $private || file_exists( $path . '.htaccess' ) ) {
			return;
		}

		$htaccess = fopen( $path . '.htaccess', 'w' ); // phpcs:ignore

		fwrite( $htaccess, 'deny from all' ); // phpcs:ignore
		fclose( $htaccess ); // phpcs:ignore
	}

	protected function create_base_dir(): void {
		if ( is_dir( $this->get_path() ) ) {
			return;
		}

		mkdir( $this->get_path(), 0755, true );
	}
}
