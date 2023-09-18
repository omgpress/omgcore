<?php

namespace O0W7_1;

defined( 'ABSPATH' ) || exit;

interface App {
	public function validate_setup( string $namespace): bool;
	public function get_key( string $key = ''): string;
	public function get_root_file(): string;
	public function get_type(): string;
}
