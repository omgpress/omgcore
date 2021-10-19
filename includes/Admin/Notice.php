<?php

namespace WP_Titan\Admin;

defined( 'ABSPATH' ) || exit;

class Notice {

	protected $instance_key;
	protected $key;

	public function __construct( string $instance_key ) {
		$this->instance_key = $instance_key;
		$this->key          = $this->instance_key . '_transient_admin_notices';

		$this->render_transients();
	}

	private function render_transients(): void {
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
		return get_option( $this->key, array() );
	}

	protected function update_transients( array $notices ): void {
		update_option( $this->key, $notices );
	}

	public function add_transient( string $message, string $level = 'error' ): void {
		$notices = $this->get_transients();

		$notices[ $level ][] = $message;

		$this->update_transients( $notices );
	}

	public function render( string $message, string $level = 'error' ): void {
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
