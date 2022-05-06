<?php

namespace WP_Titan_1_0_2;

defined( 'ABSPATH' ) || exit;

/**
 * Manage debugging.
 */
class Debug extends Feature {

	protected $die_footer_text = null;

	public function set_die_footer_text( string $text ): self {
		$this->die_footer_text = $text;

		return $this;
	}

	public function is_enabled(): bool {
		return defined( 'WP_DEBUG' ) && WP_DEBUG;
	}

	public function die( string $message, ?string $title = null, bool $enable_backtrace = true ): void {
		wpt_die( $message, $title, $this->app->get_key(), $enable_backtrace, false, $this->die_footer_text );
	}
}
