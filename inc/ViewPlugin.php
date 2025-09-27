<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

/**
 * @ignore
 */
class ViewPlugin extends View {
	public function get( string $name, array $args = array() ): string { // phpcs:ignore
		ob_start();
		include $this->app->fs()->get_path( "$this->dir/$name.php" );

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): self {
		echo wp_kses_post( $this->get( $name, $args ) );

		return $this;
	}
}
