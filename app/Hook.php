<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

class Hook extends Feature {

	protected $env;

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->env = $this->app->get_env();
	}

	public function apply_filters( string $slug, /* mixed */ ...$args ) /* mixed */ {
		return apply_filters( $this->app->get_key( $slug ), ...$args );
	}

	public function add_filter( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return add_filter( $this->app->get_key( $slug ), $callback, $priority, $accepted_args );
	}

	public function do_action( string $slug, /* mixed */ ...$args ): void {
		do_action( $this->app->get_key( $slug ), ...$args );
	}

	public function add_action( string $slug, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return $this->add_filter( $slug, $callback, $priority, $accepted_args );
	}

	public function remove_filter( string $slug, callable $function_to_remove, int $priority = 10 ): bool {
		return remove_filter( $this->app->get_key( $slug ), $function_to_remove, $priority );
	}

	public function remove_action( string $slug, callable $function_to_remove, int $priority = 10 ): bool {
		return $this->remove_filter( $slug, $function_to_remove, $priority );
	}

	public function register_activation( callable $callback ): void {
		if ( 'theme' === $this->env ) {
			add_action( 'after_switch_theme', $callback, 10, 2 );

		} else {
			register_activation_hook( $this->app->get_root_file(), $callback );
		}
	}

	public function register_deactivation( callable $callback ): void {
		if ( 'theme' === $this->env ) {
			add_action( 'switch_theme', $callback, 10, 3 );

		} else {
			register_deactivation_hook( $this->app->get_root_file(), $callback );
		}
	}
}
