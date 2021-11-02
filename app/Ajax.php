<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

class Ajax extends Feature {

	public function add_action( string $slug, callable $callback ) {
		add_action( 'wp_ajax_' . $this->instance_key . '_' . $slug, $callback );
		add_action( 'wp_ajax_nopriv_' . $this->instance_key . '_' . $slug, $callback );
	}
}
