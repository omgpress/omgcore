<?php

namespace WP_Titan_1_0_0\Theme;

defined( 'ABSPATH' ) || exit;

class Template extends \WP_Titan_1_0_0\Template {

	protected $base_path = 'template-parts';

	public function get( string $name, array $args = array() ): string {
		$args = wp_parse_args( $args );

		ob_start();

		$this->render( $name, $args );

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): void {
		get_template_part( $this->base_path . '/' . $name, null, $args );
	}
}
