<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

/**
 * Manage logs.
 */
class Logger extends Feature {

	protected $key;
	protected $file;

	/** @ignore */
	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->key  = $this->app->get_key( 'debug_log' );
		$this->file = 'logs' . DIRECTORY_SEPARATOR . 'debug.log';
	}

	public function log( string $message, string $level = 'warning' ): self {
		$timestamp = gmdate( 'n/j/Y H:i:s' );
		$content   = "[$timestamp] $level: $message\n";

		$this->app->upload()->set_content( $this->file, $content, true );

		return $this;
	}

	public function log_exists(): bool {
		return file_exists( $this->file );
	}

	public function get_log(): string {
		return $this->log_exists() ? nl2br( file_get_contents( $this->file ) ) : ''; // phpcs:ignore
	}

	public function get_log_size(): string {
		return ( $this->log_exists() ? round( filesize( $this->file ) / 1024, 3 ) : 0 ) . 'KB';
	}

	public function get_delete_log_url(): string {
		return add_query_arg( array( $this->key => 'delete' ), $this->app->http()->get_current_url() );
	}

	public function delete_log(): self {
		if ( current_user_can( 'delete_posts' ) && $this->log_exists() ) {
			unlink( $this->file );
		}

		if ( empty( $_GET[ $this->key ] ) ) { // phpcs:ignore
			return $this;
		}

		$this->app->http()->redirect( remove_query_arg( $this->key, $this->app->http()->get_current_url() ) );

		return $this;
	}

	/**
	 * Required. Set up the feature.
	 *
	 * Do not hide the call in the late hooks, as this may ruin the work of this feature.\
	 * The best way to call it directly under the "plugins_loaded" or "after_setup_theme" hooks.
	 */
	public function setup(): self {
		if ( $this->validate_single_call( __METHOD__ ) ) {
			return $this;
		}

		$this->delete_log_by_url();

		return $this;
	}

	protected function delete_log_by_url(): void {
		if ( empty( $_GET[ $this->key ] ) || 'delete' === $_GET[ $this->key ] ) { // phpcs:ignore
			return;
		}

		add_action( 'admin_init', array( $this, 'delete_log' ) );
	}
}
