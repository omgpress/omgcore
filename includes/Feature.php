<?php

namespace WP_Titan_1_0_19;

defined( 'ABSPATH' ) || exit;

abstract class Feature extends Basic_Feature {

	use Helper\Featured;
	use Helper\Single_Call;

	protected function set_property( string $property, /* mixed */ $value ): void {
		$this->validate_setter();

		$this->$property = $value;
	}

	protected function get_property( string $property ) /* mixed */ {
		$this->validate_setup();

		return $this->$property;
	}

	protected function validate_setter(): bool {
		$classname             = static::class;
		$is_app_setup_complete = $this->app->is_setup_complete();
		$is_setup_complete     = $this->is_single_called( 'setup' );

		if ( ( $is_app_setup_complete || $is_setup_complete ) && is_debug_enabled() ) {
			$trigger = $is_app_setup_complete ? 'application' : 'feature';

			_die( "It's too late to set something to the <code>$classname</code> since the $trigger setup has already been complete.", null, $this->app->get_key() );
		}

		return $is_setup_complete || $is_app_setup_complete;
	}

	protected function validate_setup(): bool {
		$classname         = static::class;
		$is_setup_complete = $this->is_single_called( 'setup' );

		if ( ! $is_setup_complete && is_debug_enabled() ) {
			_die( "Need to setup the <code>$classname</code> feature first.", null, $this->app->get_key() );
		}

		return ! $is_setup_complete;
	}

	protected function is_theme(): bool {
		return 'theme' === $this->app->get_env();
	}

	protected function add_setup_action( string $function, callable $callback, int $priority = HIGH_PRIORITY ): void {
		if ( $this->app->is_setup_complete() && is_debug_enabled() ) {
			$classname = static::class;

			_die( "It's too late to setup the <code>$classname</code> since the application setup has already been complete.", null, $this->app->get_key() );
		}

		if ( $this->validate_single_call( $function, $this->app ) ) {
			return;
		}

		add_action( $this->is_theme() ? 'after_setup_theme' : 'plugins_loaded', $callback, $priority );
	}
}
