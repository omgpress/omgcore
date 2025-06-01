<?php
namespace OmgCore\Helper;

defined( 'ABSPATH' ) || exit;

trait Singleton {
	protected static ?self $instance = null;

	public static function get_instance(): self {
		if ( ! self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	abstract protected function __construct();
}
