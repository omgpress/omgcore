<?php

namespace WP_Titan_1_0_19;

defined( 'ABSPATH' ) || exit;

abstract class Basic_Feature {

	protected $app;
	protected $core;

	public function __construct( App $app, Core $core ) {
		$this->app  = $app;
		$this->core = $core;
	}
}
