<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

class Ajax extends Feature {

	public function add_action( string $slug, callable $callback ) {
		add_action( 'wp_ajax_' . $this->app->get_key() . '_' . $slug, $callback );
		add_action( 'wp_ajax_nopriv_' . $this->app->get_key() . '_' . $slug, $callback );
	}
}
