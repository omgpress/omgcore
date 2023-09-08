<?php

namespace Wpappy_1_0_7\Integration;

use Wpappy_1_0_7\Feature;

defined( 'ABSPATH' ) || exit;

abstract class Plugin extends Feature {

	abstract public function is_active(): bool;
}
