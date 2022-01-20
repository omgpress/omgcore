<?php

namespace WP_Titan_0_9_2\Core\Customizer;

use WP_Titan_0_9_2\Feature;

defined( 'ABSPATH' ) || exit;

class Control extends Feature {

	public function action_enqueue_assets(): void {
		$this->core->itself()->asset()->enqueue_style( 'customizer-control' );
		$this->core->itself()->asset()->enqueue_script( 'customizer-control' );
	}
}
