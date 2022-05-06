<?php

namespace WP_Titan_1_0_1;

defined( 'ABSPATH' ) || exit;

/**
 * Manage Ajax actions.
 */
class Ajax extends Feature {

	/**
	 * Add a callback for an Ajax action.
	 */
	public function add_action( string $slug, callable $callback ): self {
		add_action( 'wp_ajax_' . $this->app->get_key( $slug ), $callback );
		add_action( 'wp_ajax_nopriv_' . $this->app->get_key( $slug ), $callback );

		return $this;
	}
}
