<?php

namespace WP_Titan_1_0_7\Integration;

use WP_Titan_1_0_7\Feature;

defined( 'ABSPATH' ) || exit;

abstract class Plugin extends Feature {

	abstract public function is_active(): bool;
}
