<?php

namespace Wpappy_1_0_7;

defined( 'ABSPATH' ) || exit;

/**
 * Manage form actions.
 */
class Form extends Feature {

	/**
	 * Add a callback function for a form action.
	 */
	public function add_action( string $action, callable $callback ): App {
		$key = $this->app->get_key( $action );

		add_action( "admin_post_$key", $callback );
		add_action( "admin_post_nopriv_$key", $callback );

		return $this->app;
	}

	public function get_url( string $action = '' ): string {
		$url = $this->app->admin()->get_url( 'admin-post' );
		$key = $this->app->get_key( $action );

		if ( $action ) {
			if ( ! has_action( "admin_post_$key" ) ) {
				$this->core->debug()->die( "The <code>'$action'</code> form action isn't defined." );
			}

			$url = add_query_arg( $url, array( 'action' => $key ) );
		}

		return $url;
	}
}
