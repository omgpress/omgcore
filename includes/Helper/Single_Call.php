<?php

namespace WP_Titan_1_0_13\Helper;

use WP_Titan_1_0_13\App;
use function WP_Titan_1_0_13\wpt_die;
use function WP_Titan_1_0_13\wpt_is_debug_enabled;

defined( 'ABSPATH' ) || exit;

trait Single_Call {

	protected $single_calls = array();

	protected function validate_single_call( string $function, App $app, bool $silent = false ): bool {
		$called = in_array( $function, $this->single_calls, true );

		if ( ! $silent && $called && wpt_is_debug_enabled() ) {
			wpt_die( "<code>${$function}</code> can be called only once.", null, $this->app->get_key() );
		}

		$this->single_calls[] = $function;

		return $called;
	}
}
