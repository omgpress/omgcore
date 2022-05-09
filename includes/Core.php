<?php

namespace WP_Titan_1_0_10;

defined( 'ABSPATH' ) || exit;

class Core {

	use Helper\Featured;

	protected $app;
	protected $key;

	protected $asset;
	protected $fs;
	protected $hook;

	public function __construct( App $app ) {
		$this->app = $app;
		$this->key = wpt_generate_random_str( 16 );
	}

	public function get_key( string $slug = '' ): string {
		return $this->key . ( $slug ? ( '_' . $slug ) : '' );
	}

	public function asset(): Core\Asset {
		return $this->get_feature( $this->app, $this, 'asset', Core\Asset::class );
	}

	public function fs(): Core\FS {
		return $this->get_feature( $this->app, $this, 'fs', Core\FS::class );
	}

	public function hook(): Core\Hook {
		return $this->get_feature( $this->app, $this, 'hook', Core\Hook::class );
	}
}
