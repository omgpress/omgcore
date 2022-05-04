<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

/**
 * Manage template parts.
 */
class Template extends Feature {

	protected $base_path = 'template-parts';

	public function set_base_path( string $path ): self {
		$this->base_path = $path;

		return $this;
	}

	public function get( string $name, array $args = array() ): string {
		$args = wp_parse_args( $args );

		ob_start();

		if ( $this->is_theme() ) {
			$this->render( $name, $args );

		} else {
			include $this->app->fs()->get_path( $this->base_path . DIRECTORY_SEPARATOR . $name . '.php' );
		}

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): self {
		if ( $this->is_theme() ) {
			get_template_part( $this->base_path . DIRECTORY_SEPARATOR . $name, null, $args );

		} else {
			echo $this->get( $name, $args ); // phpcs:ignore
		}

		return $this;
	}
}
