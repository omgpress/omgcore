<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

class Asset extends Feature {

	protected $fs;

	protected $base_path   = 'assets';
	protected $style_path  = 'styles';
	protected $script_path = 'scripts';

	protected $postfix = '.min';

	public function __construct( string $instance_key, Fs $fs ) {
		parent::__construct( $instance_key );

		$this->fs = $fs;
	}

	public function enqueue_style( string $slug, array $deps = array(), string $addition = '' ): void {
		$key   = $this->instance_key . '_' . $slug;
		$path  = $this->base_path . ( $this->style_path ? ( '/' . $this->style_path ) : '' ) . ( $this->base_path ? '/' : '' );
		$url   = $this->fs->get_url( $path . $slug . $this->postfix . '.css' );
		$stamp = filemtime( $this->fs->get_path( $path . $slug . $this->postfix . '.css' ) );

		wp_enqueue_style( $key, $url, $deps, $stamp );

		if ( $addition ) {
			wp_add_inline_style( $key, $addition );
		}
	}

	public function enqueue_script( string $slug, array $deps = array(), array $args = array(), bool $in_footer = true ): void {
		$key   = $this->instance_key . '_' . $slug;
		$path  = $this->base_path . ( $this->script_path ? ( '/' . $this->script_path ) : '' ) . ( $this->base_path ? '/' : '' );
		$url   = $this->fs->get_url( $path . $slug . $this->postfix . '.js' );
		$stamp = filemtime( $this->fs->get_path( $path . $slug . $this->postfix . '.js' ) );

		wp_enqueue_script( $key, $url, $deps, $stamp, $in_footer );

		if ( $args ) {
			$key_words  = explode( '_', $key );
			$first_word = $key_words[0];

			unset( $key_words[0] );

			$object_name = $first_word . array_reduce(
				$key_words,
				function ( string $result, string $item ): string {
					return $result . ucfirst( $item );
				},
				''
			);

			wp_localize_script( $key, $object_name, $args );
		}
	}

	public function external_style( string $slug, string $url ): void {
		wp_enqueue_style( $this->instance_key . '_' . $slug, $url, false, null ); // phpcs:ignore
	}

	public function external_script( string $slug, string $url, bool $in_footer = true ): void {
		wp_enqueue_script( $this->instance_key . '_' . $slug, $url, false, null, $in_footer ); // phpcs:ignore
	}
}
