<?php

namespace O0W7_1\Extension;

use O0W7_1\App;

defined( 'ABSPATH' ) || exit;

class Template {
	protected $app;
	protected $fs;
	protected $args;

	public function __construct( App $app, FS $fs, array $args = array() ) {
		$this->app  = $app;
		$this->fs   = $fs;
		$this->args = wp_parse_args(
			$args,
			array(
				'template_dir' => 'theme' === $app->get_type() ? 'template-part' : 'template',
			)
		);
	}

	public function get( string $name, array $args = array() ): string {
		ob_start();

		if ( 'theme' === $this->app->get_type() ) {
			$this->render( $name, $args );

		} else {
			include $this->fs->get_path( "{$this->args['template_dir']}/$name.php" );
		}

		return ob_get_clean();
	}

	public function render( string $name, array $args = array() ): void {
		if ( 'theme' === $this->app->get_type() ) {
			get_template_part( "{$this->args['template_dir']}/$name", null, $args );

		} else {
			echo $this->get( $name, $args ); // phpcs:ignore
		}
	}
}
