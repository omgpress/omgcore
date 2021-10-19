<?php

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

class Admin {

	private $instance_key;

	public $notice;

	public function __construct( string $instance_key ) {
		$this->instance_key = $instance_key;

		$this->notice = new Admin\Notice( $this->instance_key );
	}
}
