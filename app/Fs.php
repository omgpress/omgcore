<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

abstract class Fs extends Feature {

	abstract public function get_path( string $path = '' ): string;

	abstract public function get_url( string $url = '' ): string;
}
