<?php
namespace OmgCore;

use Exception;

defined( 'ABSPATH' ) || exit;

abstract class App {
	protected string $root_file;
	protected string $key;
	protected bool $is_plugin;
	protected AdminNotice $admin_notice;
	protected Asset $asset;
	protected Env $env;
	protected Fs $fs;
	protected Info $info;
	protected Requirement $requirement;
	protected View $view;

	protected static ?self $instance = null;

	public static function get_instance(): self {
		if ( ! self::$instance instanceof self ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * @throws Exception
	 */
	protected function __construct( string $root_file, string $key ) {
		$this->root_file = $root_file;
		$this->key       = $key;
		$root_file_paths = explode( DIRECTORY_SEPARATOR, $root_file );
		$root_dir_number = count( $root_file_paths ) - 3;
		$isset_root_dir  = isset( $root_file_paths[ $root_dir_number ] );
		$this->is_plugin = $isset_root_dir && 'plugins' === $root_file_paths[ $root_dir_number ];
		$is_theme        = $isset_root_dir && 'themes' === $root_file_paths[ $root_dir_number ];

		if ( ! $this->is_plugin && ! $is_theme ) {
			throw new Exception( 'Invalid root file path. Must be a plugin or theme.' );
		}

		$this->admin_notice = new AdminNotice( $this->key );
		$this->fs           = $this->is_plugin ?
			new FsPlugin( $root_file ) :
			new FsTheme( $root_file );
		$this->asset        = new Asset( $key, $this->fs );
		$this->env          = new Env();
		$this->info         = $this->is_plugin ?
			new InfoPlugin( $this->root_file ) :
			new InfoTheme( $this->fs->get_path( 'style.css' ) );
		$this->requirement  = new Requirement( $this->info, $this->admin_notice );
		$this->view         = $this->is_plugin ?
			new ViewPlugin( $this->fs ) :
			new ViewTheme();
	}

	public function admin_notice(): AdminNotice {
		return $this->admin_notice;
	}

	public function asset(): Asset {
		return $this->asset;
	}

	public function env(): Env {
		return $this->env;
	}

	public function fs(): Fs {
		return $this->fs;
	}

	public function info(): Info {
		return $this->info;
	}

	public function requirement(): Requirement {
		return $this->requirement;
	}

	public function view(): View {
		return $this->view;
	}
}
