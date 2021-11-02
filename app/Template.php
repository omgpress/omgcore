<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

abstract class Template extends Feature {

	protected $base_path = '';

	abstract public function get( string $name, array $args = array() ): string;

	abstract public function render( string $name, array $args = array() ): void;

	public function set_base_path( string $base_path ): void {
		$this->base_path = $base_path;
	}
}
