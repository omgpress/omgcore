<?php

namespace WP_Titan_1_0_20;

defined( 'ABSPATH' ) || exit;

class Core {

	use Helper\Featured;

	protected $app;
	protected $key;

	protected $asset;
	protected $debugger;
	protected $fs;
	protected $hook;
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

	public function str(): Core\Str {
		return $this->get_feature( null, $this, 'str', Core\Str::class );
	}
}
