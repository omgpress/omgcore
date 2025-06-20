<?php
namespace OmgCore;

use InvalidArgumentException;

defined( 'ABSPATH' ) || exit;

class Dependency extends Feature {
	protected Info $info;
	protected AdminNotice $admin_notice;
	protected ActionQuery $action_query;
	protected array $dependencies = array();
	protected string $error_message_title_required_singular;
	protected string $error_message_title_optional_singular;
	protected string $error_message_title_plural;

	protected array $config_props = array(
		'error_message_title_required_singular' => 'The "%1$s" plugin is required for the "%2$s" features to function.',
		'error_message_title_optional_singular' => 'The "%1$s" plugin is recommended for the all "%2$s" features to function.',
		'error_message_title_plural'            => 'The following plugins are required for the "%s" features to function:',
	);

	public function __construct( string $key, Info $info, AdminNotice $admin_notice, ActionQuery $action_query, array $config ) {
		parent::__construct( $config );

		$this->info         = $info;
		$this->admin_notice = $admin_notice;
		$this->action_query = $action_query;

		$action_query->add( "{$key}_install_and_activate_required_plugins", $this->handle_install_and_activate_required_plugins(), true, 'activate_plugins' );
		$action_query->add( "{$key}_install_and_activate_all_plugins", $this->handle_install_and_activate_all_plugins(), true, 'activate_plugins' );
	}

	/**
	 * @param string|array $filename
	 */
	public function require_plugin(
		string $key,
		string $title,
		$filename,
		bool $is_optional = false,
		?string $installation_url = null
	): self {
		if ( ! is_string( $filename ) && ! is_array( $filename ) ) {
			throw new InvalidArgumentException( '$filename must be a string or an array of strings' );
		}

		if ( is_array( $filename ) ) {
			foreach ( $filename as $file ) {
				if ( ! is_string( $file ) ) {
					throw new InvalidArgumentException( '$filename array can contain only strings' );
				}
			}
		}

		if ( isset( $this->dependencies[ $key ] ) ) {
			throw new InvalidArgumentException( esc_html( "Dependency with key \"$key\" already declared" ) );
		}

		$this->dependencies[ $key ] = array(
			'title'            => $title,
			'filename'         => $filename,
			'is_optional'      => $is_optional,
			'is_active'        => false,
			'is_installed'     => false,
			'is_validated'     => false,
			'installation_url' => $installation_url,
		);

		return $this;
	}

	public function is_active_all_plugins( bool $inc_optional ): bool {
		foreach ( $this->dependencies as $key => $dependency ) {
			if ( ! $this->is_active_plugin( $key ) ) {
				return false;
			}
		}

		return true;
	}

	public function is_active_plugin( string $key ): bool {
		return $this->validate( $key )['is_active'];
	}

	public function is_installed_plugin( string $key ): bool {
		return $this->validate( $key )['is_installed'];
	}

	public function maybe_render_notice( bool $recommend_optionals = true ): void {
		if ( empty( $this->dependencies ) ) {
			return;
		}

		$not_active    = array();
		$not_installed = array();

		foreach ( $this->dependencies as $key => $dependency ) {
			$dependency = $this->validate( $key );

			if ( ! $recommend_optionals && $dependency['is_optional'] ) {
				continue;
			}

			if ( ! $dependency['is_installed'] ) {
				$not_installed[] = $dependency;
			} elseif ( ! $dependency['is_active'] ) {
				$not_active[] = $dependency;
			}
		}

		$all_not_active = array_merge( $not_active, $not_installed );

		if ( empty( $all_not_active ) ) {
			return;
		}

		$name      = $this->info->get_name();
		$is_plural = 1 < count( $all_not_active );

		if ( $is_plural ) {
			$title = sprintf( $this->error_message_title_plural, $name );
		} else {
			$title = sprintf(
				$all_not_active[0]['is_optional'] ?
					$this->error_message_title_optional_singular :
					$this->error_message_title_required_singular,
				$all_not_active[0]['title'],
				$name
			);
		}

		ob_start();
		?>
		<div>
			<?php
			echo esc_html( $title );

			if ( $is_plural ) {
				?>
				<ul>
					<?php foreach ( $all_not_active as $dependency ) { ?>
						<li>
							<?php
							if ( ! empty( $dependency['installation_url'] ) ) {
								printf(
									'<a href="%s" target="_blank">%s</a>',
									esc_url( $dependency['installation_url'] ),
									esc_html( $dependency['title'] )
								);
							} else {
								echo esc_html( $dependency['title'] );
							}
							?>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
		<?php
		$this->admin_notice->render( ob_get_clean(), 'error' );
	}

	protected function validate( string $key ): array {
		if ( empty( $this->dependencies[ $key ] ) ) {
			throw new InvalidArgumentException( esc_html( "No dependency on plugin with $key key" ) );
		}

		if ( $this->dependencies[ $key ]['is_validated'] ) {
			return $this->dependencies[ $key ];
		}

		$requirement = $this->dependencies[ $key ];
		$filename    = $requirement['filename'];

		if ( is_array( $filename ) ) {
			foreach ( $filename as $file ) {
				$this->dependencies[ $key ]['is_active'] = is_plugin_active( $file );

				if ( $this->dependencies[ $key ]['is_active'] ) {
					$this->dependencies[ $key ]['is_installed'] = true;

					break;
				}

				$this->dependencies[ $key ]['is_installed'] = file_exists( WP_PLUGIN_DIR . '/' . $file );

				if ( $this->dependencies[ $key ]['is_installed'] ) {
					break;
				}
			}
		} else {
			$this->dependencies[ $key ]['is_active'] = is_plugin_active( $filename );

			if ( $this->dependencies[ $key ]['is_active'] ) {
				$this->dependencies[ $key ]['is_installed'] = true;
			} else {
				$this->dependencies[ $key ]['is_installed'] = file_exists( WP_PLUGIN_DIR . '/' . $filename );
			}
		}

		$this->dependencies[ $key ]['is_validated'] = true;

		return $this->dependencies[ $key ];
	}

	protected function handle_install_and_activate_required_plugins(): callable {
		return function (): void {};
	}

	protected function handle_install_and_activate_all_plugins(): callable {
		return function (): void {};
	}
}
