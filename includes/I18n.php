<?php

namespace WP_Titan_1_0_1;

defined( 'ABSPATH' ) || exit;

/**
 * Manage i18n (translations) for the project.
 */
class I18n extends Feature {

	protected $dirname = 'languages';

	/**
	 * Set a name for the directory that contains the i18n files.
	 *
	 * Default: "languages".
	 */
	public function set_dirname( string $dirname ): self {
		$this->dirname = $dirname;

		return $this;
	}

	/**
	 * Get a path to the directory that contains the i18n files.
	 */
	public function get_path(): string {
		$this->validate_setup();

		return $this->app->fs()->get_path( $this->dirname );
	}

	/**
	 * Get a URL of the directory that contains the i18n files.
	 */
	public function get_url(): string {
		$this->validate_setup();

		return $this->app->fs()->get_url( $this->dirname );
	}

	/**
	 * Required. Set up the feature.
	 *
	 * Do not hide the call in the late hooks, as this may ruin the work of this feature.\
	 * The best way to call it directly in the "plugins_loaded" or "after_setup_theme" hooks.
	 */
	public function setup(): self {
		if ( $this->validate_single_call( __FUNCTION__ ) ) {
			return $this;
		}

		if ( $this->is_theme() ) {
			load_theme_textdomain( $this->app->get_key(), $this->get_path() );

		} else {
			load_plugin_textdomain( $this->app->get_key(), false, $this->get_path() );
		}

		return $this;
	}

	public function localize_script( string $slug ): self {
		if ( $this->validate_setup() ) {
			return $this;
		}

		wp_set_script_translations( $this->app->get_key( $slug ), $this->app->get_key(), $this->get_path() );

		return $this;
	}

	public function __( string $text ): string {
		return translate( $text, $this->validate_setup() ? 'default' : $this->app->get_key() ); // phpcs:ignore
	}

	public function esc_html__( string $text ): string {
		return esc_html( $this->__( $text ) ); // phpcs:ignore
	}

	public function esc_attr__( string $text ): string {
		return esc_attr( $this->__( $text ) ); // phpcs:ignore
	}

	public function _n( string $single, string $plural, int $number ): string {
		return _n( $single, $plural, $number, $this->validate_setup() ? 'default' : $this->app->get_key() ); // phpcs:ignore
	}

	public function esc_html_n( string $single, string $plural, int $number ): string {
		return esc_html( $this->_n( $single, $plural, $number ) );
	}

	public function esc_attr_n( string $single, string $plural, int $number ): string {
		return esc_attr( $this->_n( $single, $plural, $number ) );
	}
}
