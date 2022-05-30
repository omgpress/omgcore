<?php

namespace WP_Titan_1_1_2;

defined( 'ABSPATH' ) || exit;

class Core {

	use Helper\Featured;

	protected $app;
	protected $key;
	protected $admin;
	protected $asset;
	protected $debugger;
	protected $fs;
	protected $hook;
	protected $http;
	protected $info;
	protected $str;

	public function __construct( App $app ) {
		$this->app = $app;
		$this->key = $this->str()->generate_random_str();
	}

	public function get_key( string $slug = '' ): string {
		return $this->key . ( $slug ? ( "_$slug" ) : '' );
	}

	public function get_app_key( string $slug = '' ): string {
		return $this->app->get_key( $slug );
	}

	public function get_app_root_file(): string {
		return $this->app->get_root_file();
	}

	public function get_env(): string {
		return $this->app->get_env();
	}

	public function admin(): Core\Admin {
		return $this->get_feature( null, $this, 'admin', Core\Admin::class );
	}

	public function asset(): Core\Asset {
		return $this->get_feature( null, $this, 'asset', Core\Asset::class );
	}

	public function debugger(): Core\Debugger {
		return $this->get_feature( null, $this, 'debugger', Core\Debugger::class );
	}

	public function fs(): Core\FS {
		return $this->get_feature( null, $this, 'fs', Core\FS::class );
	}

	public function hook(): Core\Hook {
		return $this->get_feature( null, $this, 'hook', Core\Hook::class );
	}

	public function http(): Core\Http {
		return $this->get_feature( null, $this, 'http', Core\Http::class );
	}

	public function info(): Core\Info {
		return $this->get_feature( null, $this, 'info', Core\Info::class );
	}

	public function str(): Core\Str {
		return $this->get_feature( null, $this, 'str', Core\Str::class );
	}
}
