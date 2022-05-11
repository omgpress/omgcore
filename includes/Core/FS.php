<?php

namespace WP_Titan_1_0_17\Core;

use const WP_Titan_1_0_17\ROOT_FILE;

defined( 'ABSPATH' ) || exit;

class FS extends \WP_Titan_1_0_17\FS {

	public function get_path( string $path = '', bool $parent_theme = false ): string {
		return dirname( ROOT_FILE ) . DIRECTORY_SEPARATOR . $path;
	}

	public function get_url( string $url = '', bool $stamp = false, bool $parent_theme = false ): string {
		$complete_url = plugins_url( "/$url", ROOT_FILE );

		if ( $stamp ) {
			return $this->get_url_with_stamp( $complete_url );
		}

		return $complete_url;
	}
}
