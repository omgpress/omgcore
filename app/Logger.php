<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

class Logger extends Feature {

	protected $key;
	protected $fs;
	protected $http;
	protected $path;
	protected $filename = 'debug.log';

	public function __construct( string $instance_key, Fs $fs, Http $http ) {
		parent::__construct( $instance_key );

		$this->key  = $this->instance_key . '_log';
		$this->fs   = $fs;
		$this->http = $http;
		$this->path = WP_CONTENT_DIR . '/uploads/' . $this->instance_key . '-log/';

		if ( isset( $_GET[ $this->key ] ) && 'del' === $_GET[ $this->key ] ) { // phpcs:ignore
			add_action( 'admin_init', array( $this, 'delete_file' ) );
		}
	}

	public function get_content(): string {
		return $this->file_exists() ? nl2br( file_get_contents( $this->path . $this->filename ) ) : ''; // phpcs:ignore
	}

	public function get_filesize(): string {
		return ( $this->file_exists() ? ( round( filesize( $this->path . $this->filename ) / 1024, 3 ) ) : 0 ) . 'KB';
	}

	public function delete_file(): void {
		if ( $this->can_delete_file() && $this->file_exists() ) {
			unlink( $this->path . $this->filename );
		}

		header( 'Location:' . str_replace( '&' . $this->key . '=del', '', $this->http->get_current_uri() ) );
	}

	public function file_exists(): bool {
		return file_exists( $this->path . $this->filename );
	}

	public function can_delete_file(): bool {
		return current_user_can( 'delete_posts' );
	}

	public function log( string $message, string $level = 'info' ): void {
		$this->create_uploads_dir();
		$this->create_htaccess();

		$log       = fopen( $this->path . $this->filename, 'a' ); // phpcs:ignore
		$timestamp = gmdate( 'n/j/Y H:i:s' );
		$content   = "[$timestamp] $level: $message\n\n";

		fwrite( $log, $content ); // phpcs:ignore
		fclose( $log ); // phpcs:ignore
	}

	protected function create_uploads_dir(): void {
		if ( is_dir( $this->path ) ) {
			return;
		}

		mkdir( $this->path, 0755, true );
	}

	protected function create_htaccess(): void {
		if ( file_exists( $this->path . '.htaccess' ) ) {
			return;
		}

		$htaccess = fopen( $this->path . '.htaccess', 'w' ); // phpcs:ignore

		fwrite( $htaccess, 'deny from all' ); // phpcs:ignore
		fclose( $htaccess ); // phpcs:ignore
	}
}
