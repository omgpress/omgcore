<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

class Hook extends Feature {

	protected $separator = '_';

	public function apply_filters( string $slug, /* mixed */ ...$args ) /* mixed */ {
		return apply_filters( $this->instance_key . $this->separator . $slug, ...$args );
	}

	public function add_filter( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return add_filter( $this->instance_key . $this->separator . $slug, $callback, $priority, $accepted_args );
	}

	public function do_action( string $slug, /* mixed */ ...$args ): void {
		do_action( $this->instance_key . $this->separator . $slug, ...$args );
	}

	public function add_action( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return $this->add_filter( $slug, $callback, $priority, $accepted_args );
	}
}
