<?php

namespace WP_Titan_0_9_2\Customizer;

use WP_Titan_0_9_2\Feature;

defined( 'ABSPATH' ) || exit;

class Control extends Feature {

	public function alpha_color( \WP_Customize_Manager $manager, string $id, array $args = array() ): Control\Alpha_Color {
		return new Control\Alpha_Color( $manager, $id, $args = array() );
	}

	public function tinymce( \WP_Customize_Manager $manager, string $id, array $args = array() ): Control\Alpha_Color {
		return new Control\Alpha_Color( $manager, $id, $args = array() );
	}
}
