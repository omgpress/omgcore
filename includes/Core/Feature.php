<?php

namespace WP_Titan_1_0_20\Core;

use WP_Titan_1_0_20\Core;
use WP_Titan_1_0_20\Helper;

defined( 'ABSPATH' ) || exit;

abstract class Feature {

	use Helper\Featured;

	protected $core;

	public function __construct( Core $core ) {
		$this->core = $core;
	}
}
