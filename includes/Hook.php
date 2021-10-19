<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

class Hook {

	protected $instance_key;

	public function __construct( string $instance_key ) {
		$this->instance_key = $instance_key;
	}

	public function apply_filters( string $slug, /* mixed */ ...$args ) /* mixed */ {
		return apply_filters( $this->instance_key . '_' . $slug, ...$args );
	}

	public function add_filter( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return add_filter( $this->instance_key . '_' . $slug, $callback, $priority, $accepted_args );
	}

	public function do_action( string $slug, /* mixed */ ...$args ): void {
		do_action( $this->instance_key . '_' . $slug, ...$args );
	}

	public function add_action( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return $this->add_filter( $slug, $callback, $priority, $accepted_args );
	}
}
