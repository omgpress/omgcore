<?php

namespace WP_Titan_1_0_1;

defined( 'ABSPATH' ) || exit;

abstract class Feature {

	use Featured;

	protected $app;
	protected $core;
	protected $single_calls = array();

	public function __construct( App $app, Core $core ) {
		$this->app  = $app;
		$this->core = $core;
	}

	protected function validate_single_call( string $function ): bool {
		$called = in_array( $function, $this->single_calls, true );

		if ( $called && $this->app->debug()->is_enabled() ) {
			wpt_die( "<code>${$function}</code> can be called only once.", null, $this->app->get_key() );
		}

		$this->single_calls[] = $function;

		return $called;
	}

	protected function validate_setup(): bool {
		$classname = static::class;
		$not_setup = ! in_array( 'setup', $this->single_calls, true );

		if ( $not_setup && $this->app->debug()->is_enabled() ) {
			wpt_die( "Need to setup the <code>${classname}</code> feature first.", null, $this->app->get_key() );
		}

		return $not_setup;
	}

	protected function is_theme(): bool {
		return 'theme' === $this->app->get_env();
	}
}
