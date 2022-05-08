<?php

namespace WP_Titan_1_0_5;

defined( 'ABSPATH' ) || exit;

/**
 * Manage static assets.
 */
class Asset extends Feature {

	protected $dirname        = 'assets';
	protected $font_dirname   = 'fonts';
	protected $image_dirname  = 'images';
	protected $script_dirname = 'scripts';
	protected $style_dirname  = 'styles';
	protected $postfix        = '.min';

	protected $fs;

	/** @ignore */
	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->set_fs();
	}

	/**
	 * Optional. Default: 'assets'.
	 */
	public function set_dirname( string $name ): App {
		$this->dirname = $name;

		return $this->app;
	}

	/**
	 * Optional. Default: 'fonts'.
	 */
	public function set_font_dirname( string $name ): App {
		$this->font_dirname = $name;

		return $this->app;
	}

	/**
	 * Optional. Default: 'images'.
	 */
	public function set_image_dirname( string $name ): App {
		$this->image_dirname = $name;

		return $this->app;
	}

	/**
	 * Optional. Default: 'scripts'.
	 */
	public function set_script_dirname( string $name ): App {
		$this->script_dirname = $name;

		return $this->app;
	}

	/**
	 * Optional. Default: 'styles'.
	 */
	public function set_style_dirname( string $name ): App {
		$this->style_dirname = $name;

		return $this->app;
	}

	/**
	 * Optional. Default: '.min'.
	 */
	public function set_postfix( string $postfix ): App {
		$this->postfix = $postfix;

		return $this->app;
	}

	protected function set_fs(): void {
		$this->fs = $this->app->fs();
	}

	protected function get_raw_path( string $path = '' ): string {
		return $this->dirname . ( $this->dirname ? DIRECTORY_SEPARATOR : '' ) . ( $path ? DIRECTORY_SEPARATOR . $path : '' );
	}

	public function get_path( string $path = '', bool $raw = false ): string {
		return $raw ? $this->get_raw_path( $path ) : $this->fs->get_path( $this->get_raw_path( $path ) );
	}

	public function get_url( string $url = '', bool $stamp = false ): string {
		return $this->fs->get_url( $this->get_raw_path( $url ), $stamp );
	}

	public function get_font_path( string $path = '', bool $raw = false ): string {
		return $this->get_path( $this->font_dirname . ( $path ? DIRECTORY_SEPARATOR . $path : '' ), $raw );
	}

	public function get_font_url( string $url = '', bool $stamp = false ): string {
		return $this->get_url( $this->font_dirname . ( $url ? DIRECTORY_SEPARATOR . $url : '' ), $stamp );
	}

	public function get_image_path( string $path = '', bool $raw = false ): string {
		return $this->get_path( $this->image_dirname . ( $path ? DIRECTORY_SEPARATOR . $path : '' ), $raw );
	}

	public function get_image_url( string $url = '', bool $stamp = false ): string {
		return $this->get_url( $this->image_dirname . ( $url ? DIRECTORY_SEPARATOR . $url : '' ), $stamp );
	}

	public function get_script_path( string $path = '', bool $raw = false ): string {
		return $this->get_path( $this->script_dirname . ( $path ? DIRECTORY_SEPARATOR . $path : '' ), $raw );
	}

	public function get_script_url( string $url = '', bool $stamp = false ): string {
		return $this->get_url( $this->script_dirname . ( $url ? DIRECTORY_SEPARATOR . $url : '' ), $stamp );
	}

	public function get_style_path( string $path = '', bool $raw = false ): string {
		return $this->get_path( $this->style_dirname . ( $path ? DIRECTORY_SEPARATOR . $path : '' ), $raw );
	}

	public function get_style_url( string $url = '', bool $stamp = false ): string {
		return $this->get_url( $this->style_dirname . ( $url ? DIRECTORY_SEPARATOR . $url : '' ), $stamp );
	}

	public function get_key( string $slug ): string {
		return $this->app->get_key( str_replace( '-', '_', $slug ) );
	}

	public function enqueue_script( string $slug, array $deps = array(), array $args = array(), ?string $args_object_name = null, bool $in_footer = true ): App {
		$key      = $this->get_key( $slug );
		$filename = $slug . $this->postfix . '.js';
		$url      = $this->get_script_url( $filename );
		$path     = $this->get_script_path( $filename );

		if ( ! file_exists( $path ) ) {
			return $this->app;
		}

		wp_enqueue_script( $key, $url, $deps, filemtime( $path ), $in_footer );

		if ( $args ) {
			$args_object_name = $args_object_name ?: $this->app->str()->to_camelcase( $key );

			wp_localize_script( $key, $args_object_name, $args );
		}

		return $this->app;
	}

	public function enqueue_style( string $slug, array $deps = array(), /* string|array */ $addition = null ): App {
		$key      = $this->get_key( $slug );
		$filename = $slug . $this->postfix . '.css';
		$url      = $this->get_style_url( $filename );
		$path     = $this->get_style_path( $filename );

		if ( ! file_exists( $path ) ) {
			return $this->app;
		}

		wp_enqueue_style( $key, $url, $deps, filemtime( $path ) );

		if ( empty( $addition ) ) {
			return $this->app;
		}

		if ( is_string( $addition ) ) {
			wp_add_inline_style( $key, $addition );

		} elseif ( is_array( $addition ) ) {
			$css_vars = ':root{';

			foreach ( $addition as $var_slug => $var_val ) {
				$css_vars .= '--' . str_replace( '_', '-', $this->app->get_key( $var_slug ) ) . ':' . $var_val . ';';
			}

			wp_add_inline_style( $key, $css_vars . '}' );
		}

		return $this->app;
	}

	public function get_script_args_key( string $object_name ): string {
		return $this->app->get_key( $object_name );
	}

	public function enqueue_script_args( string $object_name, array $args, bool $in_footer = true ): App {
		$key = $this->get_script_args_key( $object_name );

		wp_enqueue_script( $key, null, array(), null, $in_footer ); // phpcs:ignore
		wp_localize_script( $key, $object_name, $args );

		return $this->app;
	}

	public function external_script( string $slug, string $url, bool $in_footer = true ): App {
		wp_enqueue_script( $this->app->get_key( $slug ), $url, false, null, $in_footer ); // phpcs:ignore

		return $this->app;
	}

	public function external_style( string $slug, string $url ): App {
		wp_enqueue_style( $this->app->get_key( $slug ), $url, false, null ); // phpcs:ignore

		return $this->app;
	}
}
