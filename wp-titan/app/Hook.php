<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

class Hook extends Feature {

	public function apply_filters( string $slug, /* mixed */ ...$args ) /* mixed */ {
		return apply_filters( $this->app->get_key() . '_' . $slug, ...$args );
	}

	public function add_filter( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return add_filter( $this->app->get_key() . '_' . $slug, $callback, $priority, $accepted_args );
	}

	public function do_action( string $slug, /* mixed */ ...$args ): void {
		do_action( $this->app->get_key() . '_' . $slug, ...$args );
	}

	public function add_action( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return $this->add_filter( $slug, $callback, $priority, $accepted_args );
	}
}
