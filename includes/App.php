<?php

namespace WP_Titan_1_0_14;

defined( 'ABSPATH' ) || exit;

/**
 * The Entry Point. The best point to start exploring the library.
 *
 * ### Getting Started
 * A method that returns instance of a class that extends the `Feature` class is a specific feature, such as helpers, managers, etc.\
 * Of course, a feature contains its own methods and may also contain its own sub-features.\
 * Don't worry about optimization, each feature (and sub-feature) will be initialized only by the first call to its method.\
 * <a href="https://github.com/dpripa/wp-titan#example" target="_blank">Explore an example</a> of the WP Titan usage for more details.
 *
 * ### Setup Methods
 * Some features have `::setup()` method. Calling of this method is __required__ when you want to start use this feature.\
 * Don't hide its call in the any hooks, as this may ruin the work of the feature.
 *
 * ### Setter Methods
 * Some features have setter methods, like `::set_<name>(<property>)`. These methods can be optionally used for configure the feature and can change its behavior.\
 * It must be called before any `::setup()` methods are called.
 */
class App {

	use Helper\Featured;
	use Helper\Single_Call;

	protected static $namespaces = array();

	protected $key;
	protected $root_file;
	protected $env;
	protected $priority = PRIORITY;
	protected $core;

	protected $admin;
	protected $ajax;
	protected $arr;
	protected $asset;
	protected $customizer;
	protected $debugger;
	protected $fs;
	protected $hook;
	protected $http;
	protected $i18n;
	protected $integration;
	protected $logger;
	protected $nav_menu;
	protected $simpleton;
	protected $str;
	protected $template;
	protected $uploader;
	protected $writer;

	/** @ignore */
	protected function __construct( string $key, string $root_file ) {
		$this->key       = $key;
		$this->root_file = $root_file;
		$root_file_paths = explode( DIRECTORY_SEPARATOR, $root_file );
		$root_dir_number = count( $root_file_paths ) - 3;
		$isset_root_dir  = isset( $root_file_paths[ $root_dir_number ] );
		$is_theme        = $isset_root_dir && 'themes' === $root_file_paths[ $root_dir_number ];
		$is_plugin       = $isset_root_dir && 'plugins' === $root_file_paths[ $root_dir_number ];
		$this->env       = $is_theme ? 'theme' : 'plugin';

		if ( ! $is_theme && ! $is_plugin ) {
			wpt_die( 'Wrong application root file.', null, $key );
		}
	}

	protected function __clone() {}

	/** @ignore */
	public function __wakeup() {
		wpt_die( 'Cannot unserialize a singleton.', null, $this->key );
	}

	/**
	 * Get the singleton instance of WP Titan for your application.
	 *
	 * @param string $namespace The namespace of your application to be used as the key of WP Titan instance.
	 */
	public static function get( string $namespace, string $root_file = '' ): self {
		if ( empty( self::$namespaces[ $namespace ] ) ) {
			if ( empty( $root_file ) ) {
				wpt_die( 'Application root file is required on initial call.', null, $namespace );
			}

			self::$namespaces[ $namespace ] = new self( $namespace, $root_file );
		}

		return self::$namespaces[ $namespace ];
	}

	/**
	 * Get the key for the application.
	 *
	 * It's the namespace that you passed to the `App::get()` method.
	 */
	public function get_key( string $slug = '', string $separator = '_' ): string {
		switch ( $separator ) {
			case 'camel':
				return $this->str()->to_camelcase( $this->key . ( $slug ? ( '_' . $slug ) : '' ) );

			default:
			case '_':
				return $this->key . ( $slug ? ( '_' . $slug ) : '' );
		}
	}

	/**
	 * Get the application root file.
	 */
	public function get_root_file(): string {
		return $this->root_file;
	}

	/**
	 * Get the application environment.
	 *
	 * @return string `'plugin'` or `'theme'`
	 */
	public function get_env(): string {
		return $this->env;
	}

	protected function is_theme(): bool {
		return 'theme' === $this->get_env();
	}

	public function set_priority( int $priority ): self {
		if ( $this->is_setted_up() ) {
			wpt_die( 'It\'s too late to change the priority since the application has already been setted up.', null, $this->key );

			return $this;
		}

		$this->priority = $priority;

		return $this;
	}

	public function get_priority(): int {
		return $this->priority;
	}

	public function setup( callable $callback ): self {
		if ( $this->validate_single_call( __FUNCTION__, $this ) ) {
			return $this;
		}

		if ( $this->is_theme() ) {
			add_action(
				'after_setup_theme',
				function (): void {
					add_theme_support( 'title-tag' );
					add_theme_support( 'automatic-feed-links' );
					add_theme_support( 'post-thumbnails' );
					add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );
					add_theme_support( 'customize-selective-refresh-widgets' );
				},
				H_PRIORITY
			);
		}

		add_action( $this->is_theme() ? 'after_setup_theme' : 'plugins_loaded', $callback, $this->priority );

		return $this;
	}

	public function is_setted_up(): bool {
		return $this->is_single_called( 'setup' );
	}

	protected function core(): Core {
		if ( ! is_a( $this->core, Core::class ) ) {
			$this->core = new Core( $this );
		}

		return $this->core;
	}

	/**
	 * Features used only for the admin panel.
	 */
	public function admin(): Admin {
		return $this->get_feature( $this, $this->core(), 'admin', Admin::class );
	}

	/**
	 * Manage Ajax actions.
	 */
	public function ajax(): Ajax {
		return $this->get_feature( $this, $this->core(), 'ajax', Ajax::class );
	}

	/**
	 * Helpers for working with arrays.
	 */
	public function arr(): Arr {
		return $this->get_feature( $this, $this->core(), 'arr', Arr::class );
	}

	/**
	 * Manage static assets.
	 */
	public function asset(): Asset {
		return $this->get_feature( $this, $this->core(), 'asset', Asset::class );
	}

	/**
	 * Manage customizer.
	 */
	public function customizer(): Customizer {
		return $this->get_feature( $this, $this->core(), 'customizer', Customizer::class );
	}

	/**
	 * Manage debugging.
	 */
	public function debugger(): Debugger {
		return $this->get_feature( $this, $this->core(), 'debugger', Debugger::class );
	}

	/**
	 * Manage file system.
	 */
	public function fs(): FS {
		return $this->get_feature( $this, $this->core(), 'fs', FS::class );
	}

	/**
	 * Manage the inner hooks for the application.
	 */
	public function hook(): Hook {
		return $this->get_feature( $this, $this->core(), 'hook', Hook::class );
	}

	/**
	 * Helpers for working with URLs and redirects.
	 */
	public function http(): Http {
		return $this->get_feature( $this, $this->core(), 'http', Http::class );
	}

	/**
	 * Manage i18n (translations) for the application.
	 */
	public function i18n(): I18n {
		return $this->get_feature( $this, $this->core(), 'i18n', I18n::class );
	}

	/**
	 * Helpers for working with popular third-party WordPress plugins.
	 */
	public function integration(): Integration {
		return $this->get_feature( $this, $this->core(), 'integration', Integration::class );
	}

	/**
	 * Manage logs.
	 */
	public function logger(): Logger {
		return $this->get_feature( $this, $this->core(), 'logger', Logger::class );
	}

	/**
	 * Manage navigation menus.
	 */
	public function nav_menu(): Nav_Menu {
		return $this->get_feature( $this, $this->core(), 'nav_menu', Nav_Menu::class );
	}

	/**
	 * Manage application classes that used the simpleton pattern.
	 */
	public function simpleton(): Simpleton {
		return $this->get_feature( $this, $this->core(), 'simpleton', Simpleton::class );
	}

	/**
	 * Helpers for working with text strings.
	 */
	public function str(): Str {
		return $this->get_feature( $this, $this->core(), 'str', Str::class );
	}

	/**
	 * Manage template parts.
	 */
	public function template(): Template {
		return $this->get_feature( $this, $this->core(), 'template', Template::class );
	}

	/**
	 * Manage uploads.
	 */
	public function uploader(): Uploader {
		return $this->get_feature( $this, $this->core(), 'uploader', Uploader::class );
	}

	/**
	 * Write content to file.
	 */
	public function writer(): Writer {
		return $this->get_feature( $this, $this->core(), 'writer', Writer::class );
	}
}
