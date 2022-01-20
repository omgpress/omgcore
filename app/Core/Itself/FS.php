<?php

namespace WP_Titan_0_9_2\Core\Itself;

use const WP_Titan_0_9_2\ROOT_FILE;

defined( 'ABSPATH' ) || exit;

class FS extends \WP_Titan_0_9_2\FS {

	protected function set_root_file(): void {
		$this->root_file = ROOT_FILE;
	}
}
