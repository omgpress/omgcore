<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

class Debug extends Feature {

	public function die( string $message, ?string $title = null ): void {
		_die( $message, $title, $this->app->get_key(), false );
	}
}
