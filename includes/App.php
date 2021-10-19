<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

final class App {

	protected $instance_key;
	protected $environment;

	public $hook;
	public $http;
	public $template;
	public $asset;
	public $ajax;
	public $admin;
	public $logger;

	protected static $instances = array();

	protected function __clone() {}

	public function __wakeup() {
		crash( 'Cannot unserialize a singleton.' );
	}

	protected function __construct( string $instance_key, string $environment ) {
		$this->instance_key = $instance_key;
		$this->environment  = $environment;

		$this->hook = new Hook( $this->instance_key );

		switch ( $this->environment ) {
			case 'theme':
				$this->http     = new Http_Theme( $this->instance_key );
				$this->template = new Template_Theme( $this->instance_key );
				break;

			case 'plugin':
			default:
				$this->http     = new Http_Plugin( $this->instance_key );
				$this->template = new Template_Plugin( $this->instance_key );
				break;
		}

		$this->asset  = new Asset( $this->instance_key, $this->http );
		$this->ajax   = new Ajax( $this->instance_key );
		$this->admin  = new Admin( $this->instance_key );
		$this->logger = new Logger( $this->instance_key, $this->http );
	}

	public static function get_instance( string $key, string $environment = 'plugin' ): self {
		if ( ! isset( self::$instances[ $key ] ) ) {
			self::$instances[ $key ] = new self( $key, $environment );
		}

		return self::$instances[ $key ];
	}
}
