<?php

namespace WP_Titan_1_1_1\Customizer;

use WP_Titan_1_1_1\App;
use WP_Titan_1_1_1\Feature;

defined( 'ABSPATH' ) || exit;

/**
 * Manage customizer context.
 */
class Context extends Feature {

	protected $panel;
	protected $section;

	public function add_panel( ?string $panel ): App {
		$this->panel = $panel;

		return $this->app;
	}

	public function add_section( ?string $section ): App {
		$this->section = $section;

		return $this->app;
	}

	public function add( ?string $panel, ?string $section ): App {
		return $this->app;
	}

	public function remove(): App {
		$this->panel   = null;
		$this->section = null;

		return $this->app;
	}

	public function get_panel(): ?string {
		return $this->panel;
	}

	public function get_section(): ?string {
		return $this->section;
	}
}
