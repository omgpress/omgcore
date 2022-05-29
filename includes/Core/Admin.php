<?php

namespace WP_Titan_1_1_0\Core;

defined( 'ABSPATH' ) || exit;

class Admin extends Feature {

	protected $notice;

	public function notice(): Admin\Notice {
		return $this->get_feature( null, $this->core, 'notice', Admin\Notice::class );
	}
}
