<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

class Asset extends Feature {

	protected $base_path   = 'assets';
	protected $style_path  = 'styles';
	protected $script_path = 'scripts';
	protected $postfix     = '.min';

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$config = $app->get_config();

		if ( isset( $config['asset']['base_path'] ) ) {
			$this->base_path = $config['asset']['base_path'];
		}

		if ( isset( $config['asset']['style_path'] ) ) {
			$this->base_path = $config['asset']['style_path'];
		}

		if ( isset( $config['asset']['script_path'] ) ) {
			$this->base_path = $config['asset']['script_path'];
		}

		if ( isset( $config['asset']['postfix'] ) ) {
			$this->base_path = $config['asset']['postfix'];
		}
	}

	public function enqueue_style( string $slug, array $deps = array(), string $addition = '' ): void {
		$key      = $this->app->get_key() . '_' . $slug;
		$raw_path = $this->base_path . ( $this->style_path ? ( DIRECTORY_SEPARATOR . $this->style_path ) : '' ) . ( $this->base_path ? DIRECTORY_SEPARATOR : '' );
		$url      = $this->app->fs()->get_url( $raw_path . $slug . $this->postfix . '.css' );
		$path     = $this->app->fs()->get_path( $raw_path . $slug . $this->postfix . '.css' );

		if ( ! file_exists( $path ) ) {
			return;
		}

		wp_enqueue_style( $key, $url, $deps, filemtime( $path ) );

		if ( $addition ) {
			wp_add_inline_style( $key, $addition );
		}
	}

	public function enqueue_script( string $slug, array $deps = array(), array $args = array(), bool $in_footer = true ): void {
		$key      = $this->app->get_key() . '_' . $slug;
		$raw_path = $this->base_path . ( $this->script_path ? ( DIRECTORY_SEPARATOR . $this->script_path ) : '' ) . ( $this->base_path ? DIRECTORY_SEPARATOR : '' );
		$url      = $this->app->fs()->get_url( $raw_path . $slug . $this->postfix . '.js' );
		$path     = $this->app->fs()->get_path( $raw_path . $slug . $this->postfix . '.js' );

		if ( ! file_exists( $path ) ) {
			return;
		}

		wp_enqueue_script( $key, $url, $deps, filemtime( $path ), $in_footer );

		if ( $args ) {
			wp_localize_script( $key, $this->app->text()->to_camelcase( $key ), $args );
		}
	}

	public function external_style( string $slug, string $url ): void {
		wp_enqueue_style( $this->app->get_key() . '_' . $slug, $url, false, null ); // phpcs:ignore
	}

	public function external_script( string $slug, string $url, bool $in_footer = true ): void {
		wp_enqueue_script( $this->app->get_key() . '_' . $slug, $url, false, null, $in_footer ); // phpcs:ignore
	}
}
