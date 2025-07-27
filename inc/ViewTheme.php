<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class ViewTheme extends View {
	protected string $dir;

	/**
	 * Returns content of a template file.
	 *
	 * @param string $name The name of the template file (without extension).
	 * @param array $args Optional. An associative array of variables to be extracted into the template.
	 *
	 * @return string The rendered content of the template file.
	 */
	public function get( string $name, array $args = array() ): string {
		ob_start();
		$this->render( $name, $args );

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
		get_template_part( "$this->dir/$name", null, $args );

		return $this;
	}
}
