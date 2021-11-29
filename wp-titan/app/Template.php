<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

abstract class Template extends Feature {

	protected $base_path;
	protected $config;

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$config = $app->get_config();

		if ( isset( $config['template']['base_path'] ) ) {
			$this->base_path = $config['template']['base_path'];
		}
	}

	abstract public function get( string $name, array $args = array() ): string;

	abstract public function render( string $name, array $args = array() ): void;
}
