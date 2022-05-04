<?php

namespace WP_Titan_1_0_0\Core;

use WP_Titan_1_0_0\Feature;
use const WP_Titan_1_0_0\ROOT_FILE;

defined( 'ABSPATH' ) || exit;

class FS extends Feature {

	public function get_path( string $path = '' ): string {
		return dirname( ROOT_FILE ) . DIRECTORY_SEPARATOR . $path;
	}

	public function get_url( string $url = '' ): string {
		return plugins_url( '/' . $url, ROOT_FILE );
	}
}
