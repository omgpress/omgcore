<?php

namespace WP_Titan_0_9_1;

defined( 'ABSPATH' ) || exit;

class Ajax extends Feature {

	public function add_action( string $slug, callable $callback ): void {
		add_action( 'wp_ajax_' . $this->app->get_key() . '_' . $slug, $callback );
		add_action( 'wp_ajax_nopriv_' . $this->app->get_key() . '_' . $slug, $callback );
	}
}
