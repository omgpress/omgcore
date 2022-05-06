<?php

namespace WP_Titan_1_0_3\Core;

use WP_Titan_1_0_3\Feature;

defined( 'ABSPATH' ) || exit;

class Hook extends Feature {

	public function apply_filters( string $slug, /* mixed */ ...$args ) /* mixed */ {
		return apply_filters( $this->core->get_key( $slug ), ...$args );
	}

	public function add_filter( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): App {
		add_filter( $this->core->get_key( $slug ), $callback, $priority, $accepted_args );

		return $this->app;
	}

	public function do_action( string $slug, /* mixed */ ...$args ): App {
		do_action( $this->core->get_key( $slug ), ...$args );

		return $this->app;
	}

	public function add_action( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): App {
		add_action( $this->core->get_key( $slug ), $callback, $priority, $accepted_args );

		return $this->app;
	}
}
