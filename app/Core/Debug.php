<?php

namespace WP_Titan_0_9_2\Core;

use WP_Titan_0_9_2\App;
use WP_Titan_0_9_2\Core;
use WP_Titan_0_9_2\Feature;

defined( 'ABSPATH' ) || exit;

class Debug extends Feature {

	protected $log_key;
	protected $log_file;

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->log_key  = $this->app->get_key( 'debug_log' );
		$this->log_file = 'logs' . DIRECTORY_SEPARATOR . 'debug.log';
	}

	public function log( string $message, string $level = 'warning' ): void {
		$timestamp = gmdate( 'n/j/Y H:i:s' );
		$content   = "[$timestamp] $level: $message\n";

		$this->app->upload()->set_content( $this->log_file, $content, true );
	}

	public function log_exists(): bool {
		return file_exists( $this->log_file );
	}

	public function get_log(): string {
		return $this->log_exists() ? nl2br( file_get_contents( $this->log_file ) ) : ''; // phpcs:ignore
	}

	public function get_log_size(): string {
		return ( $this->log_exists() ? round( filesize( $this->log_file ) / 1024, 3 ) : 0 ) . 'KB';
	}

	public function get_log_delete_url(): string {
		return add_query_arg( array( $this->log_key => 'delete' ), $this->app->http()->get_current_url() );
	}

	public function delete_log(): void {
		if ( current_user_can( 'delete_posts' ) && $this->log_exists() ) {
			unlink( $this->log_file );
		}

		if ( empty( $_GET[ $this->log_key ] ) ) { // phpcs:ignore
			return;
		}

		$this->app->http()->redirect( remove_query_arg( $this->log_key, $this->app->http()->get_current_url() ) );
	}

	public function action_delete_log_by_url(): void {
		if ( isset( $_GET[ $this->log_key ] ) && 'delete' === $_GET[ $this->log_key ] ) { // phpcs:ignore
			$this->delete_log();
		}
	}
}
