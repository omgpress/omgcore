<?php

namespace WP_Titan_0_9_2\Core;

use WP_Titan_0_9_2\Feature;

defined( 'ABSPATH' ) || exit;

final class Itself extends Feature {

	protected $fs;
	protected $asset;

	public function fs(): Itself\FS {
		return $this->get_feature( 'fs', Itself\FS::class );
	}

	public function asset(): Itself\Asset {
		return $this->get_feature( 'asset', Itself\Asset::class );
	}
}
