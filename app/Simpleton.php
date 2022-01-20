<?php

namespace WP_Titan_0_9_2;

defined( 'ABSPATH' ) || exit;

class Simpleton extends Feature {

	protected $instances = array();

	public function has_instance( string $classname, bool $extendable = false ): bool {
		$has_instance      = in_array( $classname, $this->instances, true );
		$this->instances[] = $classname;

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$key = $this->app->get_key();

			if ( ! $extendable ) {
				$reflection = new \ReflectionClass( $classname );

				if ( ! $reflection->isFinal() ) {
					wpt_die(
						'Simpleton class <code>' . $classname . '</code> must be final.',
						'Not final Simpleton class found',
						$key
					);
				}
			}

			if ( $has_instance ) {
				wpt_die(
					'Simpleton class <code>' . $classname . '</code> must have just one instance call.',
					'Duplicate Simpleton class instance found',
					$key
				);
			}
		}

		return $has_instance;
	}
}
