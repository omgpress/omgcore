<?php

namespace WP_Titan_1_1_0\Core;

use WP_Titan_1_1_0\Core;
use WP_Titan_1_1_0\Helper;

defined( 'ABSPATH' ) || exit;

abstract class Feature {

	use Helper\Featured;

	protected $core;

	public function __construct( Core $core ) {
		$this->core = $core;
	}

	protected function is_theme(): bool {
		return 'theme' === $this->core->get_env();
	}
}
