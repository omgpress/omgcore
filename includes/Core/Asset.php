<?php

namespace WP_Titan_1_0_16\Core;

defined( 'ABSPATH' ) || exit;

class Asset extends \WP_Titan_1_0_16\Asset {

	protected function set_fs(): void {
		$this->fs = $this->core->fs();
	}

	public function get_key( string $slug ): string {
		return $this->core->get_key( str_replace( '-', '_', $slug ) );
	}
}
