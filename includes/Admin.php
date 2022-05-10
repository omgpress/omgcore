<?php

namespace WP_Titan_1_0_12;

defined( 'ABSPATH' ) || exit;

/**
 * Features used only for the admin panel.
 */
class Admin extends Feature {

	protected $notice;

	/**
	 * Manage admin notices.
	 */
	public function notice(): Admin\Notice {
		return $this->get_feature( $this->app, $this->core, 'notice', Admin\Notice::class );
	}
}
