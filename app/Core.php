<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

final class Core {

	private $app;

	private $itself;
	private $admin;
	private $debug;
	private $customizer;

	public function __construct( App $app ) {
		$this->app = $app;
	}

	public function itself(): Core\Itself {
		return $this->get_feature( 'itself', Core\Itself::class );
	}

	public function admin(): Core\Admin {
		return $this->get_feature( 'admin', Core\Admin::class );
	}

	public function debug(): Core\Debug {
		return $this->get_feature( 'debug', Core\Debug::class );
	}

	public function customizer(): Core\Customizer {
		return $this->get_feature( 'customizer', Core\Customizer::class );
	}

	protected function get_feature( string $property, string $class ) /* mixed */ {
		if ( ! is_a( $this->$property, $class ) ) {
			$this->$property = new $class( $this->app, $this );
		}

		return $this->$property;
	}
}
