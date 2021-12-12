<?php

namespace WP_Titan_0_9_1\Admin;

use WP_Titan_0_9_1\App;
use WP_Titan_0_9_1\Core;
use WP_Titan_0_9_1\Feature;

defined( 'ABSPATH' ) || exit;

class Notice extends Feature {

	protected $transient_key;

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->transient_key = $app->get_key() . '_transient_admin_notices';

		$this->render_transients();
	}

	protected function render_transients(): void {
		$notices = $this->get_transients();

		if ( empty( $notices ) ) {
			return;
		}

		foreach ( $notices as $level => $messages ) {
			foreach ( $messages as $message ) {
				$this->render( $message, $level );
			}
		}

		$this->update_transients( array() );
	}

	protected function get_transients(): array {
		return get_option( $this->transient_key, array() );
	}

	protected function update_transients( array $notices ): void {
		update_option( $this->transient_key, $notices );
	}

	public function add_transient( string $message, string $level = 'warning' ): void {
		$notices = $this->get_transients();

		$notices[ $level ][] = $message;

		$this->update_transients( $notices );
	}

	public function render( string $message, string $level = 'warning' ): void {
		add_action(
			'admin_notices',
			function () use ( $message, $level ): void {
				?>
				<div class="notice notice-<?php echo esc_attr( $level ); ?> is-dismissible">
					<p><?php echo wp_kses_post( $message ); ?></p>
				</div>
				<?php
			}
		);
	}
}
