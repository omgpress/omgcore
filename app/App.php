<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

final class App {

	protected $instance_key;
	protected $environment;

	public $hook;
	public $fs;
	public $template;
	public $http;
	public $asset;
	public $ajax;
	public $admin;
	public $logger;

	protected static $instances = array();

	protected function __clone() {}

	public function __wakeup() {
		wpt_die( 'Cannot unserialize a singleton.' );
	}

	protected function __construct( string $instance_key, string $environment ) {
		$this->instance_key = $instance_key;
		$this->environment  = $environment;

		switch ( $this->environment ) {
			case 'theme':
				$this->fs       = new Theme\Fs( $this->instance_key );
				$this->hook     = new Hook( $this->instance_key );
				$this->template = new Theme\Template( $this->instance_key );
				break;

			case 'plugin':
			default:
				$this->fs       = new Plugin\Fs( $this->instance_key );
				$this->hook     = new Plugin\Hook( $this->instance_key, $this->fs );
				$this->template = new Plugin\Template( $this->instance_key, $this->fs );
				break;
		}

		$this->http   = new Http( $this->instance_key );
		$this->asset  = new Asset( $this->instance_key, $this->fs );
		$this->ajax   = new Ajax( $this->instance_key );
		$this->admin  = new Admin( $this->instance_key );
		$this->logger = new Logger( $this->instance_key, $this->fs, $this->http );
	}

	public static function get_instance( string $key, string $environment = 'plugin' ): self {
		if ( ! isset( self::$instances[ $key ] ) ) {
			self::$instances[ $key ] = new self( $key, $environment );
		}

		return self::$instances[ $key ];
	}
}
