<?php

namespace WP_Titan_1_0_2\Core;

defined( 'ABSPATH' ) || exit;

class Asset extends \WP_Titan_1_0_2\Asset {

	protected function set_fs(): void {
		$this->fs = $this->core->fs();
	}

	protected function get_key( string $slug ): string {
		return $this->core->get_key( str_replace( '-', '_', $slug ) );
	}
}
