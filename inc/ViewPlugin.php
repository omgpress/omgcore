<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class ViewPlugin extends View {
	protected Fs $fs;
	protected string $dir;

	public function __construct( Fs $fs, array $config ) {
		$this->fs  = $fs;
		$this->dir = $config['dir'] ?? 'view';
	}

	public function get( string $name, array $args = array() ): string { // phpcs:ignore
		ob_start();
		include $this->fs->get_path( "$this->dir/$name.php" );

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): self {
		echo wp_kses_post( static::get( $name, $args ) );

		return $this;
	}
}
