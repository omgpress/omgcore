<?php

namespace WP_Titan_0_9_0\Plugin;

defined( 'ABSPATH' ) || exit;

class Template extends \WP_Titan_0_9_0\Template {

	protected $base_path = 'templates';

	public function get( string $name, array $args = array() ): string {
		$args = wp_parse_args( $args );

		ob_start();

		include $this->app->fs()->get_path( $this->base_path . DIRECTORY_SEPARATOR . $name . '.php' );

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): void {
		echo $this->get( $name, $args ); // phpcs:ignore
	}
}
