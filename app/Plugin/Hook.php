<?php

namespace WP_Titan_1_0_0\Plugin;

use WP_Titan_1_0_0\Fs;

defined( 'ABSPATH' ) || exit;

class Hook extends \WP_Titan_1_0_0\Hook {

	protected $fs;

	public function __construct( string $instance_key, Fs $fs ) {
		parent::__construct( $instance_key );

		$this->fs = $fs;
	}

	public function register_activation( callable $callback ): void {
		register_activation_hook( $this->fs->get_root_file(), $callback );
	}

	public function register_deactivation( callable $callback ): void {
		register_deactivation_hook( $this->fs->get_root_file(), $callback );
	}
}
