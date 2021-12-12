<?php

namespace WP_Titan_0_9_1;

defined( 'ABSPATH' ) || exit;

class Simpleton extends Feature {

	protected $instances = array();

	public function has_instance( string $classname, bool $extendable = false ): bool {
		$has_instance      = in_array( $classname, $this->instances, true );
		$this->instances[] = $classname;
		$key               = $this->app->get_key();

		if ( ! $extendable ) {
			$reflection = new \ReflectionClass( $classname );

			if ( ! $reflection->isFinal() ) {
				wpt_die(
					sprintf( 'Simpleton class <code>%s</code> must be final.', $classname ),
					'Not final Simpleton class found',
					$key
				);
			}
		}

		if ( $has_instance ) {
			wpt_die(
				sprintf( 'Simpleton class <code>%s</code> must have just one instance call.', $classname ),
				'Duplicate Simpleton class instance found',
				$key
			);
		}

		return $has_instance;
	}
}
