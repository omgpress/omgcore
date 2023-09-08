<?php

namespace Wpappy_1_0_7;

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

	protected function set_property( string $property, /* mixed */ $value ): void {
		if ( $this->validate_setter() ) {
			return;
		}

		$this->$property = $value;
	}

	protected function validate_setter(): bool {
		$classname             = static::class;
		$is_app_setup_complete = $this->app->is_setup_called();
		$is_setup_complete     = $this->is_single_called( 'setup' );

		if ( $is_app_setup_complete || $is_setup_complete ) {
			$trigger = $is_app_setup_complete ? 'application' : 'feature';

			$this->core->debug()->die( "It's too late to set something to the <code>$classname</code> feature since the $trigger setup has already been complete." );
		}

		return $is_app_setup_complete || $is_setup_complete;
	}

	protected function validate_setup(): bool {
		$classname         = static::class;
		$is_setup_complete = $this->is_single_called( 'setup' );

		if ( ! $is_setup_complete ) {
			$this->core->debug()->die( "Need to call the <code>setup</code> method of the <code>$classname</code> feature first." );
		}

		return ! $is_setup_complete;
	}

	protected function is_theme(): bool {
		return 'theme' === $this->app->get_env();
	}

	protected function add_setup_action( string $function, callable $callback, int $priority = 1, bool $enable_silent_secondary_calls = false ): void {
		if ( $this->app->is_setup_called() ) {
			$classname = static::class;

			$this->core->debug()->die( "It's too late to call the <code>setup</code> method the <code>$classname</code> feature since the application setup has already been complete." );
		}

		if ( $this->validate_single_call( $function, $enable_silent_secondary_calls ) ) {
			return;
		}

		add_action( $this->is_theme() ? 'after_setup_theme' : 'plugins_loaded', $callback, $priority );
	}
}
