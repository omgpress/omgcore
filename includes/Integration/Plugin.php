<?php

namespace WP_Titan_1_0_3\Integration;

use WP_Titan_1_0_3\Feature;

defined( 'ABSPATH' ) || exit;

abstract class Plugin extends Feature {

	abstract public function is_active(): bool;
}
