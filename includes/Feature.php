<?php

namespace WP_Titan_1_0_0;

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

	protected function validate_single_call( string $method ): bool {
		$called = in_array( $method, $this->single_calls, true );

		if ( $called && $this->app->debug()->is_enabled() ) {
			wpt_die( "<code>${method}</code> can be called only once.", null, $this->app->get_key() );
		}

		$this->single_calls[] = $method;

		return $called;
	}

	protected function is_theme(): bool {
		return 'theme' === $this->app->get_env();
	}
}
