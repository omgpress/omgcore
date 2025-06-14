<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class ViewTheme extends View {
	protected string $dir;

	public function __construct( array $config ) {
		$this->dir = $config['dir'] ?? 'view';
	}

	public function get( string $name, array $args = array() ): string {
		ob_start();
		static::render( $name, $args );

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): self {
		get_template_part( "$this->dir/$name", null, $args );

		return $this;
	}
}
