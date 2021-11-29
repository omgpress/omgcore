<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

class Log extends Feature {

	protected $key;
	protected $name = 'general';
	protected $file;

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->key  = $this->app->get_key() . '_log_' . $this->name;
		$this->file = 'debug' . DIRECTORY_SEPARATOR . $this->name . '.log';

		if ( isset( $_GET[ $this->key ] ) && 'delete' === $_GET[ $this->key ] ) { // phpcs:ignore
			add_action( 'admin_init', array( $this, 'delete_file' ) );
		}
	}

	public function write( string $message, string $level = 'warning' ): void {
		$timestamp = gmdate( 'n/j/Y H:i:s' );
		$content   = "[$timestamp] $level: $message\n\n";

		$this->app->upload()->edit_file( $this->file, $content, true );
	}

	public function get_content(): string {
		return $this->file_exists() ? nl2br( file_get_contents( $this->file ) ) : ''; // phpcs:ignore
	}

	public function get_filesize(): string {
		return ( $this->file_exists() ? ( round( filesize( $this->file ) / 1024, 3 ) ) : 0 ) . 'KB';
	}

	public function get_delete_url(): string {
		return add_query_arg( array( $this->key => 'delete' ), $this->app->http()->get_current_url() );
	}

	public function delete_file(): void {
		if ( current_user_can( 'delete_posts' ) && $this->file_exists() ) {
			unlink( $this->file );
		}

		$this->app->http()->redirect( remove_query_arg( $this->key, $this->app->http()->get_current_url() ) );
	}

	public function file_exists(): bool {
		return file_exists( $this->file );
	}
}
