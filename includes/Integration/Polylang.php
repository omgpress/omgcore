<?php

namespace WP_Titan_1_0_2\Integration;

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
	 * Required. Set up the feature.
	 *
	 * Do not hide the call in the late hooks, as this may ruin the work of this feature.\
	 * The best way to call it directly in the "plugins_loaded" or "after_setup_theme" hooks.
	 */
	public function setup(): self {
		if ( ! $this->is_active() || $this->validate_single_call( __FUNCTION__ ) ) {
			return $this;
		}

		$this->change_home_url();

		return $this;
	}

	protected function change_home_url(): void {
		$this->core->hook()->add_filter(
			'home_url',
			function ( string $path, string $raw_path, $base ): string {
				return $base ? $path : ( pll_home_url() . ( '/' === $raw_path ? '' : $raw_path ) );
			},
			10,
			3
		);
	}
}
