<?php

namespace WP_Titan_1_0_21\Core;

defined( 'ABSPATH' ) || exit;

class Debugger extends Feature {

	protected $notice_shown = false;

	public function die( string $message, ?string $title = null ): void {
		if ( ! static::is_enabled() && ! $this->notice_shown ) {
			$env      = $this->core->get_env();
			$name     = '"' . $this->core->info()->get_name() . '"';
			$message  = "<b>Some Error(s) was found in $name $env.</b><br/>";
			$message .= "They have been suppressed, but this can lead to unexpected application behavior. Please, contact the $env author.<br/><br/>";
			$message .= 'Or you can enable <code>WP_DEBUG</code> to see complete information.<br/>';
			$message .= '<i>Carefully! In debug mode the application will completely stop immediately at the first error.</i><br/>';
			$message .= static::get_wp_debug_doc_url();

			$this->core->admin()->notice()->render( $message );

			$this->notice_shown = true;

			return;
		}

		static::raw_die( $message, $title, $this->core->get_app_key() );
	}

	public static function is_enabled(): bool {
		return defined( 'WP_DEBUG' ) && WP_DEBUG;
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
		<p><?php echo wp_kses_post( static::get_wp_debug_doc_url() ); ?></p>
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

	protected static function get_wp_debug_doc_url(): string {
		return '<a href="https://wordpress.org/support/article/debugging-in-wordpress/" target="_blank">Read about debugging in WordPress</a>';
	}
}
