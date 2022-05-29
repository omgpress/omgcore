<?php

namespace WP_Titan_1_0_21\Core;

use WP_Titan_1_0_21\Core;

defined( 'ABSPATH' ) || exit;

class Debugger extends Feature {

	protected $production_errors_notice_shown = false;

	protected $dev_hosts = array(
		'localhost',
		'loc',
		'dev',
	);

	public function __construct( Core $core ) {
		parent::__construct( $core );

		if ( empty( $GLOBALS['WP_TITAN_DEBUG_DEV_NOTICE_SHOWN'] ) ) {
			$GLOBALS['WP_TITAN_DEBUG_DEV_NOTICE_SHOWN'] = false;
		}
	}

	public function die( string $message, ?string $title = null ): void {
		if ( ! static::is_enabled() && ! $this->production_errors_notice_shown ) {
			$env      = $this->core->get_env();
			$name     = '"' . $this->core->info()->get_name() . '"';
			$message  = "<b>Some Error(s) was found in $name $env.</b><br/>";
			$message .= "They have been suppressed, but this can lead to unexpected application behavior. Please, contact the $env author.<br/><br/>";
			$message .= 'Or you can enable <code>WP_DEBUG</code> to see complete information.<br/>';
			$message .= '<i>Carefully! In debug mode the application will completely stop immediately at the first error.</i><br/>';
			$message .= static::get_wp_debug_link_item();

			$this->core->admin()->notice()->render( $message );

			$this->production_errors_notice_shown = true;

			return;
		}

		static::raw_die( $message, $title, $this->core->get_app_key() );
	}

	public static function is_enabled(): bool {
		return defined( 'WP_DEBUG' ) && WP_DEBUG;
	}

	public function is_dev_env(): bool {
		return in_array( $this->core->http()->get_root_host(), $this->dev_hosts, true );
	}

	public static function raw_die( string $message, ?string $title = null, ?string $key = null, bool $enable_backtrace = true, bool $is_core = true, string $footer_text = '' ): void {
		global $wp_query;

		if ( ! isset( $wp_query ) ) {
			remove_filter( 'wp_robots', 'wp_robots_noindex_search' );
			remove_filter( 'wp_robots', 'wp_robots_noindex_embeds' );
		}

		if ( ! static::is_enabled() ) {
			static::basic_die();
		}

		ob_start();

		$title = ( $is_core ? 'WP Titan Core Error' : 'Error' ) . ( $title ? ( ': ' . $title ) : '' );
		?>
		<h2><?php echo esc_html( $title ); ?></h2>
		<p><?php echo wp_kses_post( $message ); ?></p>
		<?php
		if ( $enable_backtrace ) {
			static::render_backtrace();
		}

		if ( $key || $footer_text ) {
			?>
			<hr style="margin-top: 35px; border-top: 1px solid #dadada; border-bottom: 0;">
			<p style="margin-top: 15px; font-size: 12px; color: #9b9b9b;">
				<?php if ( $key ) { ?>
					Application key: <code style="color: #444;"><?php echo esc_html( $key ); ?></code>
					<?php
				}

				if ( $footer_text ) {
					echo ' ' . wp_kses_post( $footer_text );
				}
				?>
			</p>
			<?php
		}

		wp_die( ob_get_clean(), 'Error' ); // phpcs:ignore
	}

	protected static function basic_die(): void {
		ob_start();
		?>
		<h2>Error</h2>
		<p>Something went wrong. Enable <code>WP_DEBUG</code> to see complete information.</p>
		<p><?php echo wp_kses_post( static::get_wp_debug_link_item() ); ?></p>
		<?php
		wp_die( ob_get_clean(), 'Error' ); // phpcs:ignore
	}

	protected static function render_backtrace(): void {
		$backtrace = array_values(
			array_filter(
				array_map(
					function ( array $caller ) /* mixed */ {
						if ( isset( $caller['class'] ) ) {
							$caller_namespace = explode( '\\', $caller['class'] )[0];

						} else {
							$caller_namespace = explode( '\\', $caller['function'] )[0];
						}

						if ( explode( '\\', __NAMESPACE__ )[0] === $caller_namespace ) {
							return false;

						} else {
							return array(
								'file'     => $caller['file'],
								'line'     => $caller['line'],
								'function' => $caller['function'],
								'class'    => $caller['class'] ?? null,
							);
						}
					},
					debug_backtrace() // phpcs:ignore
				)
			)
		);
		?>
		<div style="margin-top: 25px;">
			<h4 style="margin-bottom: 0;">Backtrace:</h4>
			<ul style="margin: 15px 0 0; padding-left: 0; list-style: none; color: #9b9b9b;">
				<?php foreach ( $backtrace as $caller ) { ?>
					<li style="font-size: 12px;">
						<?php echo esc_html( $caller['file'] . ':' . $caller['line'] . ': ' ); ?>
						<code style="color: #444;">
							<?php echo esc_html( ( $caller['class'] ? ( $caller['class'] . '::' ) : '' ) . $caller['function'] ); ?>
						</code>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php
	}

	protected static function get_wp_debug_link_item(): string {
		return '<a href="https://wordpress.org/support/article/debugging-in-wordpress/" target="_blank">Read about debugging in WordPress</a>';
	}

	public function setup(): void {
		add_action(
			$this->is_theme() ? 'after_setup_theme' : 'plugins_loaded',
			function (): void {
				$this->render_dev_notice();
			}
		);
	}

	protected function render_dev_notice(): void {
		if (
			! $this->is_dev_env() ||
			static::is_enabled() ||
			$GLOBALS['WP_TITAN_DEBUG_DEV_NOTICE_SHOWN']
		) {
			return;
		}

		$GLOBALS['WP_TITAN_DEBUG_DEV_NOTICE_SHOWN'] = true;

		$root_host = $this->core->http()->get_root_host();
		$message   = '<b>It looks like <code>WP_DEBUG</code> is disabled on the DEVELOPMENT environment.</b><br/>';
		$message  .= "Most errors and warnings will not be displayed. This may lead to the fact that you don't notice some kind of bug.<br/>";
		$message  .= "<i>This message is displayed because the website root host</i> <code>$root_host</code> <i>isn't public.</i><br/><br/>";
		$message  .= static::get_wp_debug_link_item();

		$this->core->admin()->notice()->render( $message );
	}
}
