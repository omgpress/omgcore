<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

class Template_Theme extends Template {

	public function __construct( string $instance_key ) {
		$this->instance_key = $instance_key;
	}

	public function get( string $slug ): string {
		// TODO: Implement get() method.
	}

	public function render( string $slug ): void {
		// TODO: Implement render() method.
	}
}
