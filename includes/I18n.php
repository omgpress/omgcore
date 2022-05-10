<?php

namespace WP_Titan_1_0_11;

defined( 'ABSPATH' ) || exit;

/**
 * Manage i18n (translations) for the application.
 */
class I18n extends Feature {

	protected $dirname = 'languages';

	/**
	 * Set a name for the directory that contains the i18n files.
	 *
	 * Default: "languages".
	 */
	public function set_dirname( string $dirname ): App {
		$this->dirname = $dirname;

		return $this->app;
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
	 * Required.
	 */
	public function setup(): App {
		if ( $this->validate_single_call( __FUNCTION__, $this->app ) ) {
			return $this->app;
		}

		$this->add_setup_action(
			function (): void {
				if ( $this->is_theme() ) {
					load_theme_textdomain( $this->app->get_key(), $this->get_path() );

				} else {
					load_plugin_textdomain( $this->app->get_key(), false, $this->get_path() );
				}
			}
		);

		return $this->app;
	}

	public function localize_script( string $slug ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		wp_set_script_translations( $this->app->asset()->get_key( $slug ), $this->app->get_key(), $this->get_path() );

		return $this->app;
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
