<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

class Ajax {

	protected $instance_key;

	public function __construct( string $instance_key ) {
		$this->instance_key = $instance_key;
	}

	public function add_action( string $slug, callable $callback ) {
		add_action( 'wp_ajax_' . $this->instance_key . '_' . $slug, $callback );
		add_action( 'wp_ajax_nopriv_' . $this->instance_key . '_' . $slug, $callback );
	}
}
