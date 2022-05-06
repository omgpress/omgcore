<?php

namespace WP_Titan_1_0_2;

use WP_Titan_1_0_2\Customizer\Section;
use WP_Titan_1_0_2\Customizer\Control;

defined( 'ABSPATH' ) || exit;

/**
 * Manage customizer.
 */
class Customizer extends Feature {

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
		return $this->app->get_key( 'customizer_' . $slug );
	}

	public function add_panel( string $panel, array $args, ?string $panel_class = null ): self {
		if ( $this->validate_setup() ) {
			return $this;
		}

		add_action(
			'customize_register',
			function ( \WP_Customize_Manager $wp_customize ) use ( $panel, $args, $panel_class ): void {
				$key = $this->get_key( $panel );

				if ( $panel_class ) {
					$wp_customize->add_panel( new $panel_class( $wp_customize, $key, $args ) );

				} else {
					$wp_customize->add_panel( $key, $args );
				}
			}
		);

		return $this;
	}

	public function add_section( string $section, ?string $panel, array $args, ?string $section_class = null ): self {
		if ( $this->validate_setup() ) {
			return $this;
		}

		if ( $panel ) {
			$args['panel'] = $this->get_key( $panel );
		}

		add_action(
			'customize_register',
			function ( \WP_Customize_Manager $wp_customize ) use ( $section, $args, $section_class ): void {
				$key = $this->get_key( $section );

				if ( $section_class ) {
					$wp_customize->add_section( new $section_class( $wp_customize, $key, $args ) );

				} elseif ( isset( $args['wpt_type'] ) && in_array( $args['wpt_type'], array_keys( $this->extended_sections ), true ) ) {
					$wp_customize->add_section( new $this->extended_sections[ $args['wpt_type'] ]( $wp_customize, $key, $args ) );

				} else {
					$wp_customize->add_section( $key, $args );
				}
			}
		);

		return $this;
	}

	public function add_setting( string $setting, string $section, array $args, array $control_args, ?string $control_class = null ): self {
		if ( $this->validate_setup() ) {
			return $this;
		}

		$key = $this->get_key( $section . '_' . $setting );

		if ( isset( $args['default'] ) ) {
			$this->settings[ $section ][ $setting ]['default'] = $args['default'];
		}

		if ( isset( $args['type'] ) ) {
			$this->settings[ $section ][ $setting ]['type'] = $args['type'];
		}

		$args         = wp_parse_args( $args, $this->default_setting_args );
		$control_args = wp_parse_args( $control_args, $this->default_control_args );

		$control_args['section'] = $this->get_key( $section );

		add_action(
			'customize_register',
			function ( \WP_Customize_Manager $wp_customize ) use ( $key, $args, $control_args, $control_class ): void {
				$wp_customize->add_setting( $key, $args );

				if ( $control_class ) {
					$wp_customize->add_control( new $control_class( $wp_customize, $key, $control_args ) );

				} elseif ( isset( $control_args['wpt_type'] ) && in_array( $control_args['wpt_type'], array_keys( $this->extended_controls ), true ) ) {
					$wp_customize->add_control( new $this->extended_controls[ $control_args['wpt_type'] ]( $wp_customize, $key, $control_args ) );

				} else {
					$wp_customize->add_control( $key, $control_args );
				}
			}
		);

		return $this;
	}

	public function get_setting( string $setting, string $section ) /* mixed */ {
		$key = $this->get_key( $section . '_' . $setting );

		return get_option( $key, $this->settings[ $section ][ $setting ]['default'] ?? false );
	}

	/**
	 * Required. Set up the feature.
	 * Do not hide the call in the late hooks, as this may ruin the work of this feature.\
	 * The best way to call it directly in the "plugins_loaded" or "after_setup_theme" hooks.
	 */
	public function setup(): self {
		if ( $this->validate_single_call( __FUNCTION__ ) ) {
			return $this;
		}

		$this->enqueue_assets();

		return $this;
	}

	protected function enqueue_assets(): void {
		add_action(
			'customize_controls_enqueue_scripts',
			function (): void {
				$this->core->asset()
					->enqueue_style( 'customizer', array( 'wp-color-picker' ) )
					->enqueue_script( 'customizer', array( 'jquery', 'customize-controls', 'wp-color-picker' ) );
			}
		);
	}
}
