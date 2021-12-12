<?php

namespace WP_Titan_0_9_1\Plugin;

defined( 'ABSPATH' ) || exit;

class Hook extends \WP_Titan_0_9_1\Hook {

	public function register_activation( callable $callback ): void {
		register_activation_hook( $this->app->get_root_file(), $callback );
	}

	public function register_deactivation( callable $callback ): void {
		register_deactivation_hook( $this->app->get_root_file(), $callback );
	}
}
