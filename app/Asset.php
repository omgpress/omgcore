<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

class Asset extends Feature {

	protected $feature_key = 'asset';

	protected $root_url;
	protected $root_path;
	protected $base_path   = 'assets';
	protected $style_path  = 'styles';
	protected $script_path = 'scripts';
	protected $postfix     = '.min';

	protected $config_keys = array(
		'base_path',
		'style_path',
		'script_path',
		'postfix',
	);

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->setup_root_url();
		$this->setup_root_path();
	}

	protected function setup_root_url(): void {
		$this->root_url = $this->app->fs()->get_url();
	}

	protected function setup_root_path(): void {
		$this->root_path = $this->app->fs()->get_path();
	}

	protected function get_key( string $slug ): string {
		return $this->app->get_key( str_replace( '-', '_', $slug ) );
	}

	public function enqueue_style( string $slug, array $deps = array(), string $addition = '' ): void {
		$key      = $this->get_key( $slug );
		$raw_path = $this->base_path . ( $this->style_path ? ( DIRECTORY_SEPARATOR . $this->style_path ) : '' ) . ( $this->base_path ? DIRECTORY_SEPARATOR : '' );
		$url      = $this->root_url . $raw_path . $slug . $this->postfix . '.css';
		$path     = $this->root_path . $raw_path . $slug . $this->postfix . '.css';

		if ( ! file_exists( $path ) ) {
			return;
		}

		wp_enqueue_style( $key, $url, $deps, filemtime( $path ) );

		if ( $addition ) {
			wp_add_inline_style( $key, $addition );
		}
	}

	public function enqueue_script( string $slug, array $deps = array(), array $args = array(), bool $in_footer = true ): void {
		$key      = $this->get_key( $slug );
		$raw_path = $this->base_path . ( $this->script_path ? ( DIRECTORY_SEPARATOR . $this->script_path ) : '' ) . ( $this->base_path ? DIRECTORY_SEPARATOR : '' );
		$url      = $this->root_url . $raw_path . $slug . $this->postfix . '.js';
		$path     = $this->root_path . $raw_path . $slug . $this->postfix . '.js';

		if ( ! file_exists( $path ) ) {
			return;
		}

		wp_enqueue_script( $key, $url, $deps, filemtime( $path ), $in_footer );

		if ( $args ) {
			wp_localize_script( $key, $this->app->str()->to_camelcase( $key ), $args );
		}
	}

	public function external_style( string $slug, string $url ): void {
		wp_enqueue_style( $this->app->get_key( $slug ), $url, false, null ); // phpcs:ignore
	}

	public function external_script( string $slug, string $url, bool $in_footer = true ): void {
		wp_enqueue_script( $this->app->get_key( $slug ), $url, false, null, $in_footer ); // phpcs:ignore
	}
}
