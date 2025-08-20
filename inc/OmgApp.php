<?php
namespace OmgCore;

use Exception;

defined( 'ABSPATH' ) || exit;

abstract class OmgApp {
	protected string $root_file;
	protected string $key;
	protected bool $is_plugin;
	protected ActionQuery $action_query;
	protected AdminNotice $admin_notice;
	protected Asset $asset;
	protected Dependency $dependency;
	protected Env $env;
	protected Fs $fs;
	protected Info $info;
	protected Logger $logger;
	protected View $view;

	protected static ?self $instance = null;

	/**
	 * Returns the singleton instance of the OmgApp class.
	 *
	 * @return self The singleton instance of the OmgApp class.
	 * @throws Exception
	 */
	public static function get_instance(): self {
		if ( ! static::$instance instanceof self ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Constructor for the OmgApp class.
	 *
	 * @param string $root_file The root file of the plugin or theme.
	 * @param string $key The unique key for the plugin or theme.
	 * @param bool $is_plugin Whether the application is a plugin (default: true).
	 *
	 * @throws Exception
	 */
	protected function __construct( string $root_file, string $key, bool $is_plugin = true ) {
		$this->root_file = $root_file;
		$this->key       = $key;
		$this->is_plugin = $is_plugin;

		add_action( 'init', $this->init() );
	}

	/**
	 * Return Fs class instance.
	 */
	public function fs(): Fs {
		return $this->fs;
	}

	/**
	 * Return Info class instance.
	 */
	public function info(): Info {
		return $this->info;
	}

	/**
	 * Return Logger class instance.
	 */
	public function logger(): Logger {
		return $this->logger;
	}

	/**
	 * Return View class instance.
	 */
	public function view(): View {
		return $this->view;
	}

	protected function init(): callable {
		return function (): void {
			$config             = $this->get_config();
			$this->action_query = new ActionQuery();
			$this->admin_notice = new AdminNotice( $this->key );
			$this->fs           = $this->is_plugin ?
				new FsPlugin( $this->root_file ) :
				new FsTheme();
			$this->asset        = new Asset(
				$this->key,
				$this->fs,
				$config[ Asset::class ] ?? array()
			);
			$this->info         = $this->is_plugin ?
				new InfoPlugin( $this->root_file ) :
				new InfoTheme( $this->fs->get_path( 'style.css' ) );
			$this->dependency   = new Dependency(
				$this->key,
				$this->info,
				$this->admin_notice,
				$this->action_query,
				$config[ Dependency::class ] ?? array()
			);
			$this->env          = new Env();
			$this->logger       = new Logger(
				$this->key,
				$this->fs,
				$this->action_query,
				$this->admin_notice,
				$this->info,
				$config[ Logger::class ] ?? array()
			);
			$this->view         = $this->is_plugin ?
				new ViewPlugin( $this->fs, $config[ View::class ] ?? array() ) :
				new ViewTheme( $config[ View::class ] ?? array() );

			if ( $this->is_plugin ) {
				load_plugin_textdomain(
					$this->info->get_textdomain(),
					false,
					$this->fs->get_path( 'lang' )
				);
				register_activation_hook( $this->root_file, $this->activate() );
				register_deactivation_hook( $this->root_file, $this->deactivate() );
			} else {
				load_theme_textdomain(
					$this->info->get_textdomain(),
					$this->fs->get_path( 'lang' )
				);
				add_action( 'after_switch_theme', $this->activate() );
				add_action( 'switch_theme', $this->deactivate() );
			}
		};
	}

	protected function activate(): callable {
		return function (): void {};
	}

	protected function deactivate(): callable {
		return function (): void {
			$this->admin_notice->reset();
			$this->logger->reset();
		};
	}

	protected function get_config(): array {
		return array();
	}
}
