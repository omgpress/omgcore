<?php

namespace Wpappy_1_0_7;

defined( 'ABSPATH' ) || exit;

/**
 * Manage Rest API.
 */
class API extends Feature {

	protected $remote;

	public function remote(): API\Remote {
		return $this->get_feature( $this->app, $this->core, 'remote', API\Remote::class );
	}

	public function add_rote(): App {
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'myplugin/v1',
					'/author-posts/(?P<id>\d+)',
					array(
						'methods'  => 'GET',
						'callback' => 'my_awesome_func',
					)
				);
			}
		);

		return $this->app;
	}
}
