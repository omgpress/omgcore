<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

class Admin extends Feature {

	public $notice;

	public function __construct( string $instance_key ) {
		parent::__construct( $instance_key );

		$this->notice = new Admin\Notice( $this->instance_key );
	}
}
