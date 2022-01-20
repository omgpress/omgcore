<?php

namespace WP_Titan_0_9_2\Core;

use WP_Titan_0_9_2\Feature;

defined( 'ABSPATH' ) || exit;

class Customizer extends Feature {

	protected $control;

	public function control(): Customizer\Control {
		return $this->get_feature( 'control', Customizer\Control::class );
	}
}
