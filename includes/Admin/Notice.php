<?php

namespace WP_Titan_1_0_1\Admin;

use WP_Titan_1_0_1\App;
use WP_Titan_1_0_1\Core;
use WP_Titan_1_0_1\Feature;

defined( 'ABSPATH' ) || exit;

/**
 * Manage admin notices.
 */
class Notice extends Feature {

	protected $transient_key;

	/** @ignore */
	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->transient_key = $app->get_key( 'transient_admin_notices' );
	}

	protected function get_transients(): array {
		return get_option( $this->transient_key, array() );
	}

	protected function update_transients( array $notices ): self {
		update_option( $this->transient_key, $notices );

		return $this;
	}

	/**
	 * Add a notice to render it in the transient queue.
	 *
	 * @param string $level "info", "success", "warning" or "error".
	 */
	public function add_transient( string $message, string $level = 'warning' ): self {
		if ( $this->validate_setup() ) {
			return $this;
		}

		$notices = $this->get_transients();

		$notices[ $level ][] = $message;

		$this->update_transients( $notices );

		return $this;
	}

	/**
	 * Render a notice.
	 *
	 * @param string $level "info", "success", "warning" or "error".
	 */
	public function render( string $message, string $level = 'warning' ): self {
		if ( $this->validate_setup() ) {
			return $this;
		}

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

		return $this;
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

		$this->render_transients();

		return $this;
	}

	protected function render_transients(): void {
		$notices = $this->get_transients();

		if ( empty( $notices ) ) {
			return;
		}

		add_action(
			'admin_init',
			function () use ( $notices ): void {
				foreach ( $notices as $level => $messages ) {
					foreach ( $messages as $message ) {
						$this->render( $message, $level );
					}
				}

				$this->update_transients( array() );
			}
		);
	}
}
