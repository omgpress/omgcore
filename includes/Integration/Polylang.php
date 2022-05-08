<?php

namespace WP_Titan_1_0_7\Integration;

use WP_Titan_1_0_7\App;
use const WP_Titan_1_0_7\PRIORITY;

defined( 'ABSPATH' ) || exit;

/**
 * Polylang.
 */
class Polylang extends Plugin {

	/**
	 * Is Polylang active.
	 */
	public function is_active(): bool {
		return class_exists( 'Polylang' );
	}

	/**
	 * Required.
	 */
	public function setup(): App {
		if ( $this->validate_single_call( __FUNCTION__, $this->app ) ) {
			return $this->app;
		}

		$this->add_setup_action(
			function (): void {
				if ( ! $this->is_active() ) {
					return;
				}

				$this->change_home_url();
			}
		);

		return $this->app;
	}

	protected function change_home_url(): void {
		$this->core->hook()->add_filter(
			'home_url',
			function ( string $path, string $raw_path, $base ): string {
				return $base ? $path : ( pll_home_url() . ( '/' === $raw_path ? '' : $raw_path ) );
			},
			PRIORITY,
			3
		);
	}
}
