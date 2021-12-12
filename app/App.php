<?php

namespace WP_Titan_0_9_1;

defined( 'ABSPATH' ) || exit;

final class App {

	private $key;
	private $root_file;
	private $config;
	private $env;

	private $core;
	private $debug;
	private $log;
	private $simpleton;
	private $fs;
	private $hook;
	private $template;
	private $http;
	private $asset;
	private $ajax;
	private $admin;
	private $upload;
	private $text;

	private static $instances = array();

	private function __construct( string $key, string $root_file, array $config ) {
		$this->key       = $key;
		$this->root_file = $root_file;
		$this->config    = $config;
		$root_file_paths = explode( DIRECTORY_SEPARATOR, $this->root_file );
		$root_dir_number = count( $root_file_paths ) - 3;
		$isset_root_dir  = isset( $root_file_paths[ $root_dir_number ] );
		$is_theme        = $isset_root_dir && 'themes' === $root_file_paths[ $root_dir_number ];
		$is_plugin       = $isset_root_dir && 'plugins' === $root_file_paths[ $root_dir_number ];
		$this->env       = $is_theme ? 'theme' : 'plugin';

		if ( ! $is_theme && ! $is_plugin ) {
			wpt_die( 'Wrong project root file.', null, $this->key );
		}
	}

	private function __clone() {}

	public function __wakeup() {
		wpt_die( 'Cannot unserialize a singleton.', null, $this->key );
	}

	public static function get_instance( string $key, string $root_file, array $config = array() ): self {
		if ( ! isset( self::$instances[ $key ] ) ) {
			self::$instances[ $key ] = new self( $key, $root_file, $config );
		}

		return self::$instances[ $key ];
	}

	public function get_key(): string {
		return $this->key;
	}

	public function get_root_file(): string {
		return $this->root_file;
	}

	public function get_config(): array {
		return $this->config;
	}

	public function get_env(): string {
		return $this->env;
	}

	private function core(): Core {
		if ( ! is_a( $this->core, Core::class ) ) {
			$this->core = new Core( $this );
		}

		return $this->core;
	}

	public function debug(): Debug {
		return $this->get_feature( 'debug', Debug::class );
	}

	public function log(): Log {
		return $this->get_feature( 'log', Log::class );
	}

	public function simpleton(): Simpleton {
		return $this->get_feature( 'simpleton', Simpleton::class );
	}

	public function fs(): FS {
		return $this->get_feature( 'fs', Plugin\FS::class, Theme\FS::class );
	}

	public function hook(): Hook {
		return $this->get_feature( 'hook', Plugin\Hook::class, Hook::class );
	}

	public function template(): Template {
		return $this->get_feature( 'template', Plugin\Template::class, Theme\Template::class );
	}

	public function http(): Http {
		return $this->get_feature( 'http', Http::class );
	}

	public function asset(): Asset {
		return $this->get_feature( 'asset', Asset::class );
	}

	public function ajax(): Ajax {
		return $this->get_feature( 'ajax', Ajax::class );
	}

	public function admin(): Admin {
		return $this->get_feature( 'admin', Admin::class );
	}

	public function upload(): Upload {
		return $this->get_feature( 'upload', Upload::class );
	}

	public function text(): Text {
		return $this->get_feature( 'text', Text::class );
	}

	private function get_feature( string $property, string $class, ?string $theme_class = null ) /* mixed */ {
		if ( ! is_a( $this->$property, $class ) ) {
			if ( $theme_class && 'theme' === $this->env ) {
				$this->$property = new $theme_class( $this, $this->core() );

			} else {
				$this->$property = new $class( $this, $this->core() );
			}
		}

		return $this->$property;
	}
}
