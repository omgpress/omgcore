<?php

namespace WP_Titan_1_0_21;

use WP_Titan_1_0_21\Customizer\Section;
use WP_Titan_1_0_21\Customizer\Control;

defined( 'ABSPATH' ) || exit;

/**
 * Manage customizer.
 */
class Customizer extends Feature {

	protected $extended_panels = array();

	protected $extended_sections = array(
		'link' => Section\Link::class,
	);

	protected $extended_controls = array(
		'media'       => \WP_Customize_Media_Control::class,
		'code_editor' => \WP_Customize_Code_Editor_Control::class,
		'alpha_color' => Control\Alpha_Color::class,
		'line'        => Control\Line::class,
		'notice'      => Control\Notice::class,
		'radio_image' => Control\Radio_Image::class,
	);

	protected $settings = array();

	protected $default_setting_args = array(
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	);

	protected $default_control_args = array();

	protected function get_key( string $slug ): string {
		return $this->app->get_key( "customizer_$slug" );
	}

	public function add_panel( string $panel, array $args, ?string $panel_classname = null ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		add_action(
			'customize_register',
			function ( \WP_Customize_Manager $wp_customize ) use ( $panel, $args, $panel_classname ): void {
				$key = $this->get_key( $panel );

				if ( $panel_classname ) {
					$wp_customize->add_panel( new $panel_classname( $wp_customize, $key, $args ) );

				} elseif ( isset( $args['type'] ) && in_array( $args['type'], array_keys( $this->extended_panels ), true ) ) {
					$type = $args['type'];

					unset( $args['type'] );
					$wp_customize->add_section( new $this->extended_panels[ $type ]( $wp_customize, $key, $args ) );

				} else {
					$wp_customize->add_panel( $key, $args );
				}
			}
		);

		return $this->app;
	}

	public function add_section( string $section, ?string $panel, array $args, ?string $section_classname = null ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		if ( $panel ) {
			$args['panel'] = $this->get_key( $panel );
		}

		add_action(
			'customize_register',
			function ( \WP_Customize_Manager $wp_customize ) use ( $section, $args, $section_classname ): void {
				$key = $this->get_key( $section );

				if ( $section_classname ) {
					$wp_customize->add_section( new $section_classname( $wp_customize, $key, $args ) );

				} elseif ( isset( $args['type'] ) && in_array( $args['type'], array_keys( $this->extended_sections ), true ) ) {
					$type = $args['type'];

					unset( $args['type'] );
					$wp_customize->add_section( new $this->extended_sections[ $type ]( $wp_customize, $key, $args ) );

				} else {
					$wp_customize->add_section( $key, $args );
				}
			}
		);

		return $this->app;
	}

	public function add_setting( string $setting, string $section, array $args, array $control_args, ?string $control_classname = null ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		$key = $this->get_key( "{$section}_$setting" );

		if ( isset( $args['default'] ) ) {
			$this->settings[ $section ][ $setting ]['default'] = $args['default'];
		}

		if ( isset( $args['type'] ) ) {
			$this->settings[ $section ][ $setting ]['type'] = $args['type'];
		}

		if ( $this->validate_setup() ) {
			return $this->app;
		}

		$args         = wp_parse_args( $args, $this->default_setting_args );
		$control_args = wp_parse_args( $control_args, $this->default_control_args );

		$control_args['section'] = $this->get_key( $section );

		add_action(
			'customize_register',
			function ( \WP_Customize_Manager $wp_customize ) use ( $key, $args, $control_args, $control_classname ): void {
				$wp_customize->add_setting( $key, $args );

				if ( $control_classname ) {
					$wp_customize->add_control( new $control_classname( $wp_customize, $key, $control_args ) );

				} elseif ( isset( $control_args['type'] ) && in_array( $control_args['type'], array_keys( $this->extended_controls ), true ) ) {
					$type = $control_args['type'];

					unset( $control_args['type'] );
					$wp_customize->add_control( new $this->extended_controls[ $type ]( $wp_customize, $key, $control_args ) );

				} else {
					$wp_customize->add_control( $key, $control_args );
				}
			}
		);

		return $this->app;
	}

	public function get_setting( string $setting, string $section ) /* mixed */ {
		$this->validate_setup();

		$key = $this->get_key( "{$section}_$setting" );

		return get_option( $key, $this->settings[ $section ][ $setting ]['default'] ?? null );
	}

	/**
	 * Required.
	 */
	public function setup(): App {
		$this->add_setup_action(
			__FUNCTION__,
			function (): void {
				$this->enqueue_assets();
			}
		);

		return $this->app;
	}

	protected function enqueue_assets(): void {
		add_action(
			'customize_controls_enqueue_scripts',
			function (): void {
				$this->core->asset()->enqueue_script( 'customizer', array( 'jquery', 'customize-controls', 'wp-color-picker' ) );
				$this->core->asset()->enqueue_style( 'customizer', array( 'wp-color-picker' ) );
			}
		);
	}
}
