<?php

namespace WP_Titan_1_1_1;

defined( 'ABSPATH' ) || exit;

/**
 * Manage debugging.
 */
class Debugger extends Feature {

	protected $die_footer_text = '';

	public function set_die_footer_text( string $text ): App {
		$this->set_property( 'die_footer_text', $text );

		return $this->app;
	}

	public function is_enabled(): bool {
		return Core\Debugger::is_enabled();
	}

	public function die( string $message, ?string $title = null, bool $enable_backtrace = true ): void {
		Core\Debugger::raw_die( $message, $title, $this->app->get_key(), $enable_backtrace, false, $this->die_footer_text );
	}
}
