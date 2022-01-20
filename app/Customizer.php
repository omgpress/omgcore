<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

class Customizer extends Feature {

	public $manager;

	public function set_manager( \WP_Customize_Manager $manager ): void {
		$this->manager = $manager;
	}

	public function add_section( /* object|string */ $key, array $args = array() ): \WP_Customize_Section {
		return $this->manager->add_section( $this->app->get_key( $key ), $args );
	}

	public function add_setting( string $key, array $args = array() ): \WP_Customize_Setting {
		$args = wp_parse_args(
			$args,
			array(
				'capability' => 'edit_theme_options',
			)
		);

		$args['type'] = 'option';

		return $this->manager->add_setting( $this->get_setting_key( $key ), $args );
	}

	public function add_control( /* string|object */ $key, array $args = array() ): \WP_Customize_Control {
		$key              = $this->get_setting_key( $key );
		$control          = null;
		$args['settings'] = $key;

		if ( isset( $args['type'] ) ) {
			switch ( $args['type'] ) {
				case 'alpha_color':
					$control = new Customizer\Control\Alpha_Color( $this->manager, $key, $args );
					break;
			}
		}

		return $this->manager->add_control( ( $control ?: $key ), ( $control ? $args : array() ) );
	}

	protected function get_setting_key( string $key ): string {
		return 'customizer_' . $this->app->get_key( $key );
	}
}
