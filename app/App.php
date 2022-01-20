<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

final class App {

	private static $projects = array();

	private $key;
	private $root_file;
	private $config;
	private $env;

	private $debug;
	private $fs;
	private $hook;
	private $http;
	private $simpleton;
	private $asset;
	private $template;
	private $ajax;
	private $admin;
	private $upload;
	private $str;
	private $customizer;

	private $core;

	private function __construct( string $key ) {
		$this->key = $key;
	}

	private function __clone() {}

	public function __wakeup() {
		wpt_die( 'Cannot unserialize a singleton.', null, $this->key );
	}

	public static function get_project( string $key ): self {
		if ( empty( self::$projects[ $key ] ) ) {
			self::$projects[ $key ] = new self( $key );
		}

		return self::$projects[ $key ];
	}

	public function setup( string $root_file, array $config = array() ): void {
		if ( $this->root_file ) {
			wpt_die( 'Setup can be done only once.', null, $this->key );
		}

		$this->root_file = $root_file;
		$root_file_paths = explode( DIRECTORY_SEPARATOR, $this->root_file );
		$root_dir_number = count( $root_file_paths ) - 3;
		$isset_root_dir  = isset( $root_file_paths[ $root_dir_number ] );
		$is_theme        = $isset_root_dir && 'themes' === $root_file_paths[ $root_dir_number ];
		$is_plugin       = $isset_root_dir && 'plugins' === $root_file_paths[ $root_dir_number ];
		$this->env       = $is_theme ? 'theme' : 'plugin';
		$this->config    = $config;

		if ( ! $is_theme && ! $is_plugin ) {
			wpt_die( 'Wrong project root file.', null, $this->key );
		}

		new Core\Setup( $this, $this->core() );
	}

	public function get_key( string $slug = '' ): string {
		return $this->get_prop( 'key' ) . ( $slug ? ( '_' . $slug ) : '' );
	}

	public function get_root_file(): string {
		return $this->get_prop( 'root_file' );
	}

	public function get_config(): array {
		return $this->get_prop( 'config' );
	}

	public function get_env(): string {
		return $this->get_prop( 'env' );
	}

	public function debug(): Debug {
		return $this->get_feature( 'debug', Debug::class );
	}

	public function fs(): FS {
		return $this->get_feature( 'fs', FS::class );
	}

	public function hook(): Hook {
		return $this->get_feature( 'hook', Hook::class );
	}

	public function http(): Http {
		return $this->get_feature( 'http', Http::class );
	}

	public function simpleton(): Simpleton {
		return $this->get_feature( 'simpleton', Simpleton::class );
	}

	public function asset(): Asset {
		return $this->get_feature( 'asset', Asset::class );
	}

	public function template(): Template {
		return $this->get_feature( 'template', Template::class );
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

	public function str(): Str {
		return $this->get_feature( 'str', Str::class );
	}

	public function customizer(): Customizer {
		return $this->get_feature( 'customizer', Customizer::class );
	}

	private function core(): Core {
		if ( ! is_a( $this->core, Core::class ) ) {
			$this->core = new Core( $this );
		}

		return $this->core;
	}

	private function get_prop( string $prop ) /* mixed */ {
		$this->validate_setup();

		return $this->$prop;
	}

	private function get_feature( string $prop, string $class ) /* mixed */ {
		$this->validate_setup();

		if ( ! is_a( $this->$prop, $class ) ) {
			$this->$prop = new $class( $this, $this->core() );
		}

		return $this->$prop;
	}

	private function validate_setup(): void {
		if ( empty( $this->root_file ) ) {
			wpt_die( 'Project isn\'t configured, complete the setup first.', null, $this->key );
		}
	}
}
