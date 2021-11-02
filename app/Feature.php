<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

abstract class Feature {

	protected $instance_key;

	public function __construct( string $instance_key ) {
		$this->instance_key = $instance_key;
	}
}
