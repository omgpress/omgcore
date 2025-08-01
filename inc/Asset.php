<?php
namespace OmgCore;

use Exception;

defined( 'ABSPATH' ) || exit;

class Asset extends OmgFeature {
	protected string $asset_dir;
	protected string $js_dir;
	protected string $css_dir;
	protected string $postfix;

	protected string $key;
	protected Fs $fs;

	protected array $config_props = array(
		'asset_dir' => 'asset',
		'js_dir'    => 'js',
		'css_dir'   => 'css',
		'postfix'   => '.min',
	);

	public function __construct( string $key, Fs $fs, array $config = array() ) {
		parent::__construct( $config );

		$this->key = $key;
		$this->fs  = $fs;
	}

	/**
	 * @throws Exception
	 */
	public function enqueue_script(
		string $name,
		array $deps = array(),
		array $args = array(),
		bool $in_footer = true,
		?string $args_object_name = null
	): self {
		$key      = $this->get_key( $name );
		$filename = $name . $this->postfix . '.js';
		$rel      = $this->asset_dir . '/' . $this->js_dir . '/' . $filename;
		$url      = $this->fs->get_url( $rel );
		$path     = $this->fs->get_path( $rel );

		if ( ! file_exists( $path ) ) {
			throw new Exception( esc_html( "The \"$path\" script asset file does not exist" ) );
		}

		wp_enqueue_script( $key, $url, $deps, filemtime( $path ), $in_footer );

		if ( $args ) {
			wp_localize_script( $key, is_string( $args_object_name ) ? $args_object_name : $key, $args );
		}

		return $this;
	}

	public function enqueue_inline_script( string $parent_name, string $js_code, string $position = 'after' ): self {
		wp_add_inline_script( $this->key . "_$parent_name", $js_code, $position );

		return $this;
	}

	/**
	 * @param string|array|null $addition
	 * @throws Exception
	 */
	public function enqueue_style( string $name, array $deps = array(), $addition = null ): self {
		$key      = $this->get_key( $name );
		$filename = $name . $this->postfix . '.css';
		$rel      = $this->asset_dir . '/' . $this->css_dir . '/' . $filename;
		$url      = $this->fs->get_url( $rel );
		$path     = $this->fs->get_path( $rel );

		if ( ! file_exists( $path ) ) {
			throw new Exception( esc_html( "The \"$path\" style asset file does not exist" ) );
		}

		wp_enqueue_style( $key, $url, $deps, filemtime( $path ) );

		if ( is_string( $addition ) ) {
			wp_add_inline_style( $key, $addition );

		} elseif ( is_array( $addition ) ) {
			$css_vars = ':root{';

			foreach ( $addition as $var_name => $var_val ) {
				$css_vars .= '--' . str_replace( '_', '-', $this->key . "_$var_name" ) . ':' . $var_val . ';';
			}

			wp_add_inline_style( $key, "$css_vars}" );

		} elseif ( ! is_null( $addition ) ) {
			throw new Exception( 'The $addition parameter must be a string, array or null' );
		}

		return $this;
	}

	public function enqueue_external_script( string $name, string $url, bool $in_footer = true ): self {
		wp_enqueue_script( $this->get_key( $name ), $url, false, null, $in_footer ); // phpcs:ignore

		return $this;
	}

	public function enqueue_external_style( string $name, string $url ): self {
		wp_enqueue_style( $this->get_key( $name ), $url, false, null ); // phpcs:ignore

		return $this;
	}

	public function get_key( string $name ): string {
		return $this->key . '_' . str_replace( '-', '_', $name );
	}
}
