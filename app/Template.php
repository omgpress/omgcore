<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

class Template extends Feature {

	protected $feature_key = 'template';

	protected $base_path = 'template-parts';

	protected $config_keys = array(
		'base_path',
	);

	protected $env;

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		$this->env = $this->app->get_env();
	}

	public function get( string $name, array $args = array() ): string {
		$args = wp_parse_args( $args );

		ob_start();

		if ( 'theme' === $this->env ) {
			$this->render( $name, $args );

		} else {
			include $this->app->fs()->get_path( $this->base_path . DIRECTORY_SEPARATOR . $name . '.php' );
		}

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): void {
		if ( 'theme' === $this->env ) {
			get_template_part( $this->base_path . DIRECTORY_SEPARATOR . $name, null, $args );

		} else {
			echo $this->get( $name, $args ); // phpcs:ignore
		}
	}
}
