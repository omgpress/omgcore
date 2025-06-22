<?php
namespace OmgCore;

use InvalidArgumentException;
use OmgCore\Dependency\SilentUpgraderSkin;
use Plugin_Upgrader;

defined( 'ABSPATH' ) || exit;

class Dependency extends Feature {
	protected Info $info;
	protected AdminNotice $admin_notice;
	protected ActionQuery $action_query;
	protected array $plugins = array();
	protected string $notice_title_required_singular;
	protected string $notice_title_optional_singular;
	protected string $notice_title_required_plural;
	protected string $notice_title_optional_plural;
	protected string $notice_item_not_installed;
	protected string $notice_item_undefiled_installation_url;
	protected string $notice_btn_activate;
	protected string $notice_btn_install_and_activate;
	protected string $notice_btn_activate_only_required;
	protected string $notice_btn_install_and_activate_only_required;
	protected string $notice_success_activate;
	protected string $notice_success_install_and_activate;
	protected string $notice_error_install;
	protected string $install_and_activate_plugins_action_query_key = 'install_and_activate_plugins';

	protected array $config_props = array(
		'notice_title_required_singular'                => 'The "%1$s" plugin is required for the "%2$s" features to function.',
		'notice_title_optional_singular'                => 'The "%1$s" plugin is recommended for the all "%2$s" features to function.',
		'notice_title_required_plural'                  => 'The following plugins are required for the "%s" features to function:',
		'notice_title_optional_plural'                  => 'The following plugins are recommended for the all "%s" features to function:',
		'notice_item_not_installed'                     => 'not installed',
		'notice_item_undefiled_installation_url'        => 'not installed, can\'t be installed automatically',
		'notice_btn_activate'                           => 'Activate',
		'notice_btn_install_and_activate'               => 'Install and activate',
		'notice_btn_activate_only_required'             => 'Activate only required',
		'notice_btn_install_and_activate_only_required' => 'Install and activate only required',
		'notice_success_activate'                       => 'Required plugins activated.',
		'notice_success_install_and_activate'           => 'Required plugins installed and activated.',
		'notice_error_install'                          => 'The "%1$s" plugin can\'t be installed automatically. Please install it manually.',
	);

	public function __construct( Info $info, AdminNotice $admin_notice, ActionQuery $action_query, array $config ) {
		parent::__construct( $config );

		$this->info         = $info;
		$this->admin_notice = $admin_notice;
		$this->action_query = $action_query;

		$action_query->add(
			$this->install_and_activate_plugins_action_query_key,
			$this->handle_install_and_activate_plugins(),
			true,
			'activate_plugins'
		);
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

		if ( isset( $this->plugins[ $key ] ) ) {
			throw new InvalidArgumentException( esc_html( "Dependency plugin with key \"$key\" already declared" ) );
		}

		$this->plugins[ $key ] = array(
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

	public function is_active_all_plugins( bool $inc_optional = true ): bool {
		foreach ( $this->plugins as $key => $plugin ) {
			if ( $inc_optional && $plugin['is_optional'] ) {
				continue;
			}

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

	public function maybe_render_notice( bool $inc_optional = true ): void {
		if ( empty( $this->plugins ) ) {
			return;
		}

		$required_not_active = array();
		$optional_not_active = array();

		foreach ( $this->plugins as $key => $plugin ) {
			$plugin = $this->validate( $key );

			if ( ! $plugin['is_active'] ) {
				if ( $plugin['is_optional'] ) {
					if ( $inc_optional ) {
						$optional_not_active[] = $plugin;
					}
				} else {
					$required_not_active[] = $plugin;
				}
			}
		}

		if ( empty( $required_not_active ) && empty( $optional_not_active ) ) {
			return;
		}

		ob_start();
		?>
		<div>
			<?php
			if ( $required_not_active ) {
				$this->render_notice_content(
					$required_not_active,
					$this->notice_title_required_singular,
					$this->notice_title_required_plural
				);
			}

			if ( $optional_not_active ) {
				$this->render_notice_content(
					$optional_not_active,
					$this->notice_title_optional_singular,
					$this->notice_title_optional_plural
				);
			}

			$this->render_notice_actions( $required_not_active, $optional_not_active );
			?>
		</div>
		<?php
		$this->admin_notice->render(
			ob_get_clean(),
			empty( $required_not_active ) ? 'warning' : 'error'
		);
	}

	protected function render_notice_content(
		array $plugins,
		string $title_single,
		string $title_plural
	): void {
		$name      = $this->info->get_name();
		$is_plural = 1 < count( $plugins );
		$title     = $is_plural ?
			sprintf( $title_plural, $name ) :
			sprintf( $title_single, $plugins[0]['title'], $name );
		?>
		<div>
			<div><?php echo esc_html( $title ); ?></div>
			<?php if ( $is_plural ) { ?>
				<ul>
					<?php foreach ( $plugins as $plugin ) { ?>
						<li>
							<?php
							$hint = is_string( $plugin['installation_url'] ) ?
								$this->notice_item_not_installed :
								$this->notice_item_undefiled_installation_url;

							echo esc_html( "{$plugin['title']} ($hint)" );
							?>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
		<?php
	}

	protected function render_notice_actions( array $required_plugins, array $optional_plugins ): void {
		$has_required_to_activate = false;
		$has_required_to_install  = false;
		$has_optional_to_activate = false;
		$has_optional_to_install  = false;

		foreach ( $required_plugins as $plugin ) {
			if ( ! $plugin['is_installed'] && is_string( $plugin['installation_url'] ) ) {
				$has_required_to_install = true;
			} elseif ( ! $plugin['is_active'] ) {
				$has_required_to_activate = true;
			}
		}

		foreach ( $optional_plugins as $plugin ) {
			if ( ! $plugin['is_installed'] && is_string( $plugin['installation_url'] ) ) {
				$has_optional_to_install = true;
			} elseif ( ! $plugin['is_active'] ) {
				$has_optional_to_activate = true;
			}
		}

		if (
			! $has_required_to_activate && ! $has_optional_to_activate &&
			! $has_required_to_install && ! $has_optional_to_install
		) {
			return;
		}

		$all_url           = $this->action_query->get_url(
			$this->install_and_activate_plugins_action_query_key,
			null,
			'all'
		);
		$only_required_url = $this->action_query->get_url(
			$this->install_and_activate_plugins_action_query_key,
			null,
			'only_required'
		);
		?>
		<div>
			<a href="<?php echo esc_url( $all_url ); ?>">
				<?php
				echo esc_html(
					$has_required_to_install || $has_optional_to_install ?
						$this->notice_btn_install_and_activate :
						$this->notice_btn_activate
				);
				?>
			</a>
			<?php
			if (
				( $has_required_to_activate || $has_required_to_install ) &&
				( $has_optional_to_activate || $has_optional_to_install )
			) {
				?>
				<a href="<?php echo esc_url( $only_required_url ); ?>">
					<?php
					echo esc_html(
						$has_required_to_install ?
							$this->notice_btn_install_and_activate_only_required :
							$this->notice_btn_activate_only_required
					);
					?>
				</a>
			<?php } ?>
		</div>
		<?php
	}

	protected function validate( string $key ): array {
		if ( empty( $this->plugins[ $key ] ) ) {
			throw new InvalidArgumentException( esc_html( "No plugin on plugin with $key key" ) );
		}

		if ( $this->plugins[ $key ]['is_validated'] ) {
			return $this->plugins[ $key ];
		}

		$requirement = $this->plugins[ $key ];
		$filename    = $requirement['filename'];

		if ( is_array( $filename ) ) {
			foreach ( $filename as $file ) {
				$this->plugins[ $key ]['is_active'] = is_plugin_active( $file );

				if ( $this->plugins[ $key ]['is_active'] ) {
					$this->plugins[ $key ]['is_installed'] = true;

					break;
				}

				$this->plugins[ $key ]['is_installed'] = file_exists( WP_PLUGIN_DIR . '/' . $file );

				if ( $this->plugins[ $key ]['is_installed'] ) {
					break;
				}
			}
		} else {
			$this->plugins[ $key ]['is_active'] = is_plugin_active( $filename );

			if ( $this->plugins[ $key ]['is_active'] ) {
				$this->plugins[ $key ]['is_installed'] = true;
			} else {
				$this->plugins[ $key ]['is_installed'] = file_exists( WP_PLUGIN_DIR . '/' . $filename );
			}
		}

		$this->plugins[ $key ]['is_validated'] = true;

		return $this->plugins[ $key ];
	}

	protected function handle_install_and_activate_plugins(): callable {
		return function ( array $data, array $post_data, string $query_key ): void {
			$only_required    = 'only_required' === $data[ $query_key ];
			$was_installation = false;

			foreach ( $this->plugins as $key => $plugin ) {
				if ( $only_required && $plugin['is_optional'] ) {
					continue;
				}

				$plugin = $this->validate( $key );

				if ( $plugin['is_active'] ) {
					continue;
				}

				if ( $plugin['is_installed'] ) {
					activate_plugin( $plugin['filename'] );
				} elseif ( is_string( $plugin['installation_url'] ) ) {
					if (
						true === ( new Plugin_Upgrader( new SilentUpgraderSkin() ) )->install( $plugin['installation_url'] )
					) {
						$was_installation = true;

						activate_plugin( $plugin['filename'] );
					} else {
						$this->admin_notice->add_transient(
							sprintf( $this->notice_error_install, $plugin['title'] ),
							'error'
						);
					}
				}
			}

			$this->admin_notice->add_transient(
				$was_installation ?
					$this->notice_success_install_and_activate :
					$this->notice_success_activate,
				'success'
			);
		};
	}
}
