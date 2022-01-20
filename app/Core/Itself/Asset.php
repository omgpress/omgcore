<?php

namespace WP_Titan_0_9_2\Core\Itself;

defined( 'ABSPATH' ) || exit;

class Asset extends \WP_Titan_0_9_2\Asset {

	protected function setup_root_url(): void {
		$this->root_url = $this->core->itself()->fs()->get_url();
	}

	protected function setup_root_path(): void {
		$this->root_path = $this->core->itself()->fs()->get_path();
	}

	protected function get_key( string $slug ): string {
		return 'wp_titan_' . str_replace( '-', '_', $slug );
	}
}
