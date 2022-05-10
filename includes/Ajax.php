<?php

namespace WP_Titan_1_0_12;

defined( 'ABSPATH' ) || exit;

/**
 * Manage Ajax actions.
 */
class Ajax extends Feature {

	/**
	 * Add a callback function for an Ajax action.
	 */
	public function add_action( string $slug, callable $callback ): App {
		add_action( 'wp_ajax_' . $this->app->get_key( $slug ), $callback );
		add_action( 'wp_ajax_nopriv_' . $this->app->get_key( $slug ), $callback );

		return $this->app;
	}

	public function get_url( string $action = '' ): string {
		$url = admin_url( 'admin-ajax.php' );

		if ( $action ) {
			$url = add_query_arg( $url, array( 'action' => $this->app->get_key( $action ) ) );
		}

		return $url;
	}
}
