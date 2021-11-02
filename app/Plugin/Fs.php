<?php

namespace WP_Titan_1_0_0\Plugin;

defined( 'ABSPATH' ) || exit;

class Fs extends \WP_Titan_1_0_0\Fs {

	protected $root_file = '';

	public function set_root_file( string $file ): void {
		$this->root_file = $file;
	}

	public function get_root_file(): string {
		return $this->root_file;
	}

	public function get_path( string $path = '' ): string {

		return plugin_dir_path( $this->root_file ) . $path;
	}

	public function get_url( string $url = '' ): string {

		return plugin_dir_url( $this->root_file ) . $url;
	}
}
