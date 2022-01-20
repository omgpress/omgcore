<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

abstract class Feature {

	protected $app;
	protected $core;

	protected $feature_key;
	protected $config_keys = array();

	public function __construct( App $app, Core $core ) {
		$this->app  = $app;
		$this->core = $core;

		if ( $this->feature_key && $this->config_keys ) {
			$this->setup_config_props();
		}
	}

	protected function setup_config_props(): void {
		$config = $this->app->get_config();

		if ( empty( $config[ $this->feature_key ] ) ) {
			return;
		}

		foreach ( $this->config_keys as $config_key ) {
			if ( isset( $config[ $this->feature_key ][ $config_key ] ) ) {
				$this->$config_key = $config[ $this->feature_key ][ $config_key ];
			}
		}
	}

	protected function get_feature( string $prop, string $class ) /* mixed */ {
		if ( ! is_a( $this->$prop, $class ) ) {
			$this->$prop = new $class( $this->app, $this->core );
		}

		return $this->$prop;
	}
}
