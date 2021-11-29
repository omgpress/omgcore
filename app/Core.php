<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

final class Core {

	private $app;
	private $log;

	public function __construct( App $app ) {
		$this->app = $app;
	}

	public function log(): Core\Log {
		return $this->apply_feature( 'log', Core\Log::class );
	}

	protected function apply_feature( string $property, string $class ) /* mixed */ {
		if ( ! is_a( $this->$property, $class ) ) {
			$this->$property = new $class( $this->app, $this );
		}

		return $this->$property;
	}
}
