<?php

namespace WP_Titan_1_0_14;

defined( 'ABSPATH' ) || exit;

abstract class Feature {

	use Helper\Featured;
	use Helper\Single_Call;

	protected $app;
	protected $core;

	public function __construct( App $app, Core $core ) {
		$this->app  = $app;
		$this->core = $core;
	}

	protected function set_property( $property, $value ): void {
		if ( $this->validate_setter() ) {
			return;
		}

		$this->$property = $value;
	}

	protected function get_property( $property ) /* mixed */ {
		if ( $this->validate_setup() ) {
			return null;
		}

		return $this->$property;
	}

	protected function validate_setter(): bool {
		$classname        = static::class;
		$is_setted_up     = $this->is_single_called( 'setup' );
		$is_app_setted_up = $this->app->is_setted_up();

		if ( ( $is_setted_up || $is_app_setted_up ) && wpt_is_debug_enabled() ) {
			$trigger = $is_app_setted_up ? 'application' : 'feature';

			wpt_die( "It's too late to change something in the <code>$classname</code> since the $trigger has already been setted up.", null, $this->app->get_key() );
		}

		return $is_setted_up || $is_app_setted_up;
	}

	protected function validate_setup(): bool {
		$classname = static::class;
		$not_setup = ! $this->is_single_called( 'setup' );

		if ( $not_setup && wpt_is_debug_enabled() ) {
			wpt_die( "Need to setup the <code>$classname</code> feature first.", null, $this->app->get_key() );
		}

		return $not_setup;
	}

	protected function is_theme(): bool {
		return 'theme' === $this->app->get_env();
	}

	protected function add_setup_action( string $function, callable $callback, int $priority = H_PRIORITY ): void {
		if ( $this->validate_single_call( $function, $this->app ) ) {
			return;
		}

		add_action( $this->is_theme() ? 'after_setup_theme' : 'plugins_loaded', $callback, $priority );
	}
}
