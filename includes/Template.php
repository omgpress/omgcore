<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

abstract class Template {

	abstract public function __construct( string $instance_key );

	abstract public function get( string $slug ): string;

	abstract public function render( string $slug ): void;
}
