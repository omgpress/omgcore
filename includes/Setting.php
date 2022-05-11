<?php

namespace WP_Titan_1_0_13;

defined( 'ABSPATH' ) || exit;

/**
 * Manage settings.
 */
class Setting extends Feature {

	public function setup(): App {
		$this->add_setup_action(
			__FUNCTION__,
			function (): void {
				$this->enqueue_assets();
			}
		);

		return $this->app;
	}

	public function add_page( string $page ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		return $this->app;
	}

	public function add_tab( string $tab, string $page = '' ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		return $this->app;
	}

	public function add( string $setting, string $box = '', string $tab = '', string $page = '' ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		return $this->app;
	}

	public function get( string $setting, string $tab = '', string $page = '' ) /* mixed */ {
		if ( $this->validate_setup() ) {
			return null;
		}

		return '';
	}

	protected function enqueue_assets(): void {
		add_action(
			'admin_enqueue_scripts',
			function (): void {
				$this->core->asset()->enqueue_script( 'setting' );
				$this->core->asset()->enqueue_style( 'setting' );
			}
		);
	}
}
