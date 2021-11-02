<?php

namespace WP_Titan_1_0_0\Theme;

defined( 'ABSPATH' ) || exit;

class Fs extends \WP_Titan_1_0_0\Fs {

	public function get_path( string $path = '' ): string {
		return get_parent_theme_file_path() . '/' . $path;
	}

	public function get_url( string $url = '' ): string {
		return get_parent_theme_file_uri() . '/' . $url;
	}
}
