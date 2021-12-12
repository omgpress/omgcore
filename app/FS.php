<?php

namespace WP_Titan_0_9_1;

defined( 'ABSPATH' ) || exit;

abstract class FS extends Feature {

	abstract public function get_path( string $path = '' ): string;

	abstract public function get_url( string $url = '' ): string;
}
