<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class Logger extends OmgFeature {
	protected Fs $fs;
	protected string $dir_path;

	public function __construct( string $key, Fs $fs ) {
		parent::__construct();

		$this->fs       = $fs;
		$this->dir_path = WP_CONTENT_DIR . "/uploads/{$key}_log";
	}

	public function get_content( string $group = 'debug' ): string {
		return $this->fs->read_text_file( "$this->dir_path/$group.log" );
	}

	public function get_path( string $group = 'debug' ): string {
		return "$this->dir_path/$group.log";
	}

	public function success( string $message, string $group = 'debug' ): self {
		return $this->write( $message, 'success', $group );
	}

	public function info( string $message, string $group = 'debug' ): self {
		return $this->write( $message, 'info', $group );
	}

	public function warning( string $message, string $group = 'debug' ): self {
		return $this->write( $message, 'warning', $group );
	}

	public function error( string $message, string $group = 'debug' ): self {
		return $this->write( $message, 'error', $group );
	}

	protected function write( string $message, string $level, string $group = 'debug' ): self {
		$content  = $this->fs->read_text_file( $this->get_path( $group ) );
		$content .= '[' . gmdate( 'n/j/Y H:i:s' ) . ']' . ucfirst( $level ) . ": $message\n";

		$this->maybe_create_dir();
		$this->fs->write_text_file( $this->get_path( $group ), $content );

		return $this;
	}

	protected function maybe_create_dir(): void {
		if ( is_dir( $this->dir_path ) ) {
			return;
		}

		wp_mkdir_p( $this->dir_path );

		$htaccess_path = "$this->dir_path/.htaccess";

		if ( file_exists( $htaccess_path ) ) {
			return;
		}

		$this->fs->write_text_file( $htaccess_path, 'deny from all' );
	}
}
