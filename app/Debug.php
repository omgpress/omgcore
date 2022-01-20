<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

class Debug extends Feature {

	public function die( string $message, ?string $title = null, bool $enable_backtrace = true ): void {
		wpt_die( $message, $title, $this->app->get_key(), $enable_backtrace, false );
	}

	public function log( string $message, string $level = 'warning' ): void {
		$this->core->debug()->log( $message, $level );
	}

	public function log_exists(): bool {
		return $this->core->debug()->log_exists();
	}

	public function get_log(): string {
		return $this->core->debug()->get_log();
	}

	public function get_log_size(): string {
		return $this->core->debug()->get_log_size();
	}

	public function get_log_delete_url(): string {
		return $this->core->debug()->get_log_delete_url();
	}

	public function delete_log(): void {
		$this->core->debug()->delete_log();
	}
}
