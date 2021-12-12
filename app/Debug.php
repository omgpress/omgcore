<?php

namespace WP_Titan_0_9_1;

defined( 'ABSPATH' ) || exit;

class Debug extends Feature {

	public function die( string $message, ?string $title = null, bool $enable_backtrace = true ): void {
		wpt_die( $message, $title, $this->app->get_key(), $enable_backtrace, false );
	}
}
