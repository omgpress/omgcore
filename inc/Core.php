<?php
namespace OmgCore;

use Exception;

defined( 'ABSPATH' ) || exit;

class Core {
	protected string $root_file;
	protected string $key;
	protected bool $is_plugin;
	protected Admin $admin;
	protected Asset $asset;
	protected Env $env;
	protected Fs $fs;
	protected Info $info;
	protected Requirement $requirement;
	protected View $view;

	/**
	 * @throws Exception
	 */
	public function __construct( string $root_file, string $key ) {
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

		$this->admin       = new Admin( $key );
		$this->fs          = $this->is_plugin ?
			new Plugin\Fs( $root_file ) :
			new Theme\Fs( $root_file );
		$this->asset       = new Asset( $key, $this->fs );
		$this->env         = new Env();
		$this->info        = $this->is_plugin ?
			new Plugin\Info( $this->get_root_file() ) :
			new Theme\Info( $this->fs->get_path( 'style.css' ) );
		$this->requirement = new Requirement( $this->info, $this->admin->notice() );
		$this->view        = $this->is_plugin ?
			new Plugin\View() :
			new Theme\View();
	}

	public function get_root_file(): string {
		return $this->root_file;
	}

	public function get_key(): string {
		return $this->key;
	}

	public function admin(): Admin {
		return $this->admin;
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
