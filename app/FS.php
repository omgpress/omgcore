<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

class FS extends Feature {

	protected $env;

	protected $root_file;

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->env = $this->app->get_env();

		$this->set_root_file();
	}

	protected function set_root_file(): void {
		$this->root_file = $this->app->get_root_file();
	}

	public function get_path( string $path = '', bool $parent = false ): string {
		if ( 'theme' === $this->env ) {
			return ( $parent ? get_parent_theme_file_path() : get_theme_file_path() ) . DIRECTORY_SEPARATOR . $path;

		} else {
			return plugin_dir_path( $this->root_file ) . $path;
		}
	}

	public function get_url( string $url = '', bool $parent = false ): string {
		if ( 'theme' === $this->env ) {
			return ( $parent ? get_parent_theme_file_uri() : get_theme_file_uri() ) . '/' . $url;

		} else {
			return plugin_dir_url( $this->root_file ) . $url;
		}
	}
}
