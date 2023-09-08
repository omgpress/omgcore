<?php

namespace Wpappy_1_0_7;

defined( 'ABSPATH' ) || exit;

/**
 * Manage ajax actions.
 */
class Ajax extends Feature {

	/**
	 * Add a callback function for an ajax action.
	 */
	public function add_action( string $action, callable $callback ): App {
		$key = $this->app->get_key( $action );

		add_action( "wp_ajax_$key", $callback );
		add_action( "wp_ajax_nopriv_$key", $callback );

		return $this->app;
	}

	public function get_url( string $action = '' ): string {
		$url = $this->app->admin()->get_url( 'admin-ajax' );
		$key = $this->app->get_key( $action );

		if ( $action ) {
			if ( ! has_action( "wp_ajax_$key" ) ) {
				$this->core->debug()->die( "The <code>'$action'</code> Ajax action isn't defined." );
			}

			$url = add_query_arg( $url, array( 'action' => $key ) );
		}

		return $url;
	}
}
