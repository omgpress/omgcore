<?php

namespace WP_Titan_0_9_2\Core;

use WP_Titan_0_9_2\Feature;

defined( 'ABSPATH' ) || exit;

class Admin extends Feature {

	protected $notice;

	public function notice(): Admin\Notice {
		return $this->get_feature( 'notice', Admin\Notice::class );
	}
}
