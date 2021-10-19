<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

class Http_Plugin extends Http {

	protected $root_file = '';

	public function __construct( string $instance_key ) {

		if ( empty( $this->root_file ) ) {
			crash( 'Setup root file' );
		}

		$this->instance_key = $instance_key;
	}

	public function set_root_file( string $file ): void {
		$this->root_file = $file;
	}

	public function get_path( string $path = '' ): string {
		return plugin_dir_path( $this->root_file ) . $path;
	}

	public function get_url( string $url = '' ): string {
		return plugin_dir_url( $this->root_file ) . $url;
	}
}
