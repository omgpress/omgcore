<?php

namespace WP_Titan_1_0_17\Helper;

use WP_Titan_1_0_17\App;
use function WP_Titan_1_0_17\wpt_die;
use function WP_Titan_1_0_17\wpt_is_debug_enabled;

defined( 'ABSPATH' ) || exit;

trait Single_Call {

	protected $single_calls = array();

	protected function validate_single_call( string $function, App $app, bool $silent = false ): bool {
		$called = $this->is_single_called( $function );

		if ( ! $silent && $called && wpt_is_debug_enabled() ) {
			wpt_die( "<code>${$function}</code> can be called only once.", null, $app->get_key() );
		}

		$this->single_calls[] = $function;

		return $called;
	}

	protected function is_single_called( string $function ): bool {
		return in_array( $function, $this->single_calls, true );
	}
}
