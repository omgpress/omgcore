<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

abstract class Feature {

	protected $app;
	protected $core;

	public function __construct( App $app, Core $core ) {
		$this->app  = $app;
		$this->core = $core;
	}

	protected function apply_feature( string $property, string $class ) /* mixed */ {
		if ( ! is_a( $this->$property, $class ) ) {
			$this->$property = new $class( $this->app, $this->core );
		}

		return $this->$property;
	}
}
