<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

class Admin extends Feature {

	protected $notice;

	public function notice(): Admin\Notice {
		return $this->apply_feature( 'notice', Admin\Notice::class );
	}
}
