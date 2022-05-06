<?php

namespace WP_Titan_1_0_1;

defined( 'ABSPATH' ) || exit;

/**
 * Manage uploads.
 */
class Upload extends Feature {

	protected $uploads_path;

	/** @ignore */
	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->uploads_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $app->get_key() . DIRECTORY_SEPARATOR;
	}

	public function get_path( string $path = '' ): string {
		return $this->uploads_path . $path;
	}

	public function set_content( string $name, string $content, bool $private = false ): self {
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			include_once ABSPATH . 'wp-includes' . DIRECTORY_SEPARATOR . 'pluggable.php';
		}

		if ( ! current_user_can( 'delete_posts' ) ) {
			return $this;
		}

		$this->create_base_dir();

		if ( 1 < count( explode( DIRECTORY_SEPARATOR, $name ) ) ) {
			$this->create_dir( dirname( $name ), $private );
		}

		$file = fopen( $this->uploads_path . $name, 'a' ); // phpcs:ignore

		fwrite( $file, $content ); // phpcs:ignore
		fclose( $file ); // phpcs:ignore

		return $this;
	}

	protected function create_dir( string $name, bool $private = false ): void {
		$path = $this->uploads_path . $name . DIRECTORY_SEPARATOR;

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
		if ( is_dir( $this->uploads_path ) ) {
			return;
		}

		mkdir( $this->uploads_path, 0755, true );
	}
}
