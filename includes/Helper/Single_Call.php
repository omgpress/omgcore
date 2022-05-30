<?php

namespace WP_Titan_1_1_1\Helper;

use WP_Titan_1_1_1\App;

defined( 'ABSPATH' ) || exit;

trait Single_Call {

	protected $single_calls = array();

	protected function validate_single_call( string $function, App $app, bool $silent = false ): bool {
		$called = $this->is_single_called( $function );

		if ( ! $silent && $called ) {
			$this->core->debugger()->die( "<code>${$function}</code> can be called only once." );
		}

		$this->single_calls[] = $function;

		return $called;
	}

	protected function is_single_called( string $function ): bool {
		return in_array( $function, $this->single_calls, true );
	}
}
