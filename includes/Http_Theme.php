<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

class Http_Theme extends Http {

	public function __construct( string $instance_key ) {
		$this->instance_key = $instance_key;
	}

	public function get_path( string $path = '' ): string {
		return get_parent_theme_file_path() . '/' . $path;
	}

	public function get_url( string $url = '' ): string {
		return get_parent_theme_file_uri() . '/' . $url;
	}
}
