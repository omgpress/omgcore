<?php

namespace WP_Titan_1_0_5;

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

	protected function validate_setup(): bool {
		$classname = static::class;
		$not_setup = ! in_array( 'setup', $this->single_calls, true );

		if ( $not_setup && $this->app->debugger()->is_enabled() ) {
			wpt_die( "Need to setup the <code>${classname}</code> feature first.", null, $this->app->get_key() );
		}

		return $not_setup;
	}

	protected function is_theme(): bool {
		return 'theme' === $this->app->get_env();
	}

	protected function add_setup_action( callable $callback, int $priority = H_PRIORITY ): void {
		add_action( $this->is_theme() ? 'after_setup_theme' : 'plugins_loaded', $callback, $priority );
	}
}
