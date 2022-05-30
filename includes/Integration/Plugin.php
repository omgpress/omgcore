<?php

namespace WP_Titan_1_1_1\Integration;

use WP_Titan_1_1_1\Feature;

defined( 'ABSPATH' ) || exit;

abstract class Plugin extends Feature {

	abstract public function is_active(): bool;
}
