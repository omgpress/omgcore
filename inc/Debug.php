<?php

namespace Wpappy_1_0_7;

defined( 'ABSPATH' ) || exit;

/**
 * Manage debugging.
 */
class Debug extends Feature {

	protected $log;
	protected $die_footer_text = '';

	/**
	 * Set a text to the "die" message footer.
	 *
	 * @param string $text Default empty.
	 */
	public function set_die_footer_text( string $text ): App {
		$this->set_property( 'die_footer_text', $text );

		return $this->app;
	}

	public function is_enabled(): bool {
		return Core\Debug::is_enabled();
	}

	public function die( string $message, ?string $title = null, bool $enable_backtrace = true ): void {
		Core\Debug::raw_die(
			$message,
			$title,
			$this->app->get_key(),
			$enable_backtrace,
			false,
			$this->die_footer_text
		);
	}

	/**
	 * Manage logs.
	 */
	public function log(): Debug\Log {
		return $this->get_feature( $this->app, $this->core, 'log', Debug\Log::class );
	}
}
