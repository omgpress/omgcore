<?php

namespace WP_Titan_0_9_2\Admin;

use WP_Titan_0_9_2\Feature;

defined( 'ABSPATH' ) || exit;

class Notice extends Feature {

	public function add_transient( string $message, string $level = 'warning' ): void {
		$this->core->admin()->notice()->add_transient( $message, $level );
	}

	public function render( string $message, string $level = 'warning' ): void {
		$this->core->admin()->notice()->render( $message, $level );
	}
}
