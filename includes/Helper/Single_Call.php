<?php

namespace WP_Titan_1_0_7\Helper;

use WP_Titan_1_0_7\App;
use function WP_Titan_1_0_7\wpt_die;

defined( 'ABSPATH' ) || exit;

trait Single_Call {

	protected $single_calls = array();

	protected function validate_single_call( string $function, App $app ): bool {
		$called = in_array( $function, $this->single_calls, true );

		if ( $called && $app->debugger()->is_enabled() ) {
			wpt_die( "<code>${$function}</code> can be called only once.", null, $this->app->get_key() );
		}

		$this->single_calls[] = $function;

		return $called;
	}
}
