<?php

namespace WP_Titan_1_0_5;

defined( 'ABSPATH' ) || exit;

/**
 * Manage debugging.
 */
class Debugger extends Feature {

	protected $die_footer_text = null;

	public function set_die_footer_text( string $text ): App {
		$this->die_footer_text = $text;

		return $this->app;
	}

	public function is_enabled(): bool {
		return defined( 'WP_DEBUG' ) && WP_DEBUG;
	}

	public function die( string $message, ?string $title = null, bool $enable_backtrace = true ): void {
		wpt_die( $message, $title, $this->app->get_key(), $enable_backtrace, false, $this->die_footer_text );
	}
}
