<?php

namespace Wpappy_1_0_3\Integration;

use Wpappy_1_0_3\Feature;

defined( 'ABSPATH' ) || exit;

abstract class Plugin extends Feature {

	abstract public function is_active(): bool;
}
