<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

/**
 * Manage i18n (translations) for the project.
 */
class I18n extends Feature {

	protected $dirname = 'languages';

	public function set_dirname( string $dirname ): self {
		$this->dirname = $dirname;

		return $this;
	}

	public function get_path(): string {
		return $this->app->fs()->get_path( $this->dirname );
	}

	public function get_url(): string {
		return $this->app->fs()->get_url( $this->dirname );
	}

	/**
	 * Required. Set up the feature.
	 *
	 * Do not hide the call in the late hooks, as this may ruin the work of this feature.\
	 * The best way to call it directly under the "plugins_loaded" or "after_setup_theme" hooks.
	 */
	public function setup(): self {
		if ( $this->validate_single_call( __METHOD__ ) ) {
			return $this;
		}

		if ( $this->is_theme() ) {
			load_theme_textdomain( $this->app->get_key(), $this->get_path() );

		} else {
			load_plugin_textdomain( $this->app->get_key(), false, $this->get_path() );
		}

		return $this;
	}

	public function __( string $text ): string {
		return __( $text, $this->app->get_key() ); // phpcs:ignore
	}

	public function esc_html__( string $text ): string {
		return esc_html__( $text, $this->app->get_key() ); // phpcs:ignore
	}

	public function esc_attr__( string $text ): string {
		return esc_attr__( $text, $this->app->get_key() ); // phpcs:ignore
	}

	public function _n( string $single, string $plural, int $number ): string {
		return _n( $single, $plural, $number, $this->app->get_key() ); // phpcs:ignore
	}

	public function esc_html_n( string $single, string $plural, int $number ): string {
		return esc_html( $this->_n( $single, $plural, $number ) );
	}

	public function esc_attr_n( string $single, string $plural, int $number ): string {
		return esc_attr( $this->_n( $single, $plural, $number ) );
	}
}
