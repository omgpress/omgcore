<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class ViewPlugin extends View {
	protected Fs $fs;
	protected string $dir;

	public function __construct( Fs $fs, array $config = array() ) {
		parent::__construct( $config );

		$this->fs = $fs;
	}

	/**
	 * Returns content of a template file.
	 *
	 * @param string $name The name of the template file (without extension).
	 * @param array $args Optional. An associative array of variables to be extracted into the template.
	 *
	 * @return string The rendered content of the template file.
	 */
	public function get( string $name, array $args = array() ): string { // phpcs:ignore
		ob_start();
		include $this->fs->get_path( "$this->dir/$name.php" );

		return ob_get_clean();
	}

	/**
	 * Renders (echoes) a template file.
	 *
	 * @param string $name The name of the template file (without extension).
	 * @param array $args Optional. An associative array of variables to be extracted into the template.
	 *
	 * @return self
	 */
	public function render( string $name, array $args = array() ): self {
		echo wp_kses_post( $this->get( $name, $args ) );

		return $this;
	}
}
