<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class ViewPlugin extends View {
	protected string $dir = 'view';
	protected Fs $fs;

	public function __construct( Fs $fs ) {
		$this->fs = $fs;
	}

	public function get( string $name, array $args = array() ): string { // phpcs:ignore
		ob_start();
		include $this->fs->get_path( "$this->dir/$name.php" );

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): void {
		echo wp_kses_post( static::get( $name, $args ) );
	}
}
