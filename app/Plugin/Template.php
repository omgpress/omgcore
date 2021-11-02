<?php

namespace WP_Titan_1_0_0\Plugin;

defined( 'ABSPATH' ) || exit;

class Template extends \WP_Titan_1_0_0\Template {

	protected $fs;
	protected $base_path = 'templates';

	public function __construct( string $instance_key, Fs $fs ) {
		parent::__construct( $instance_key );

		$this->fs = $fs;
	}

	public function get( string $name, array $args = array() ): string {
		$args = wp_parse_args( $args );

		ob_start();

		include $this->fs->get_path( $this->base_path . '/' . $name . '.php' );

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): void {
		echo $this->get( $name, $args ); // phpcs:ignore
	}
}
