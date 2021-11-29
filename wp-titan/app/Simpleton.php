<?php

namespace WP_Titan_0_9_0;

defined( 'ABSPATH' ) || exit;

class Simpleton extends Feature {

	protected $instances = array();

	public function has_instance( string $classname, bool $extendable = false ): bool {
		$has_instance      = in_array( $classname, $this->instances, true );
		$this->instances[] = $classname;

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$this->use_debug( $classname, $extendable, $has_instance );
		}

		return $has_instance;
	}

	protected function use_debug( string $classname, bool $extendable, bool $has_instance ): void {
		if ( ! $extendable ) {
			$reflection = new \ReflectionClass( $classname );

			if ( ! $reflection->isFinal() ) {
				$this->app->debug()->die(
					sprintf( 'Simpleton class <code>%s</code> must be final.', $classname ),
					'Not final Simpleton class found'
				);
			}
		}

		if ( $has_instance ) {
			$this->app->debug()->die(
				sprintf( 'Simpleton class <code>%s</code> must have just one instance call.', $classname ),
				'Duplicate Simpleton class instance found'
			);
		}
	}
}
