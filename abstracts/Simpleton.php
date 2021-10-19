<?php

namespace WPT_Abstracts;

use function WP_Titan\crash;

defined( 'ABSPATH' ) || exit;

trait Simpleton {

	private static $has_instance = false;

	abstract public function __construct();

	private function validate_simpleton( bool $extendable = false ): bool {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

			if ( ! $extendable ) {
				$classname  = static::class;
				$reflection = new \ReflectionClass( $classname );

				if ( ! $reflection->isFinal() ) {
					crash( sprintf( 'Simpleton class <code>%s</code> must be final.', $classname ), 'Not final Simpleton class found' );
				}
			}

			if ( static::$has_instance ) {
				crash( sprintf( 'Simpleton class <code>%s</code> must have just one instance call.', $classname ), 'Duplicate Simpleton class instance found' );
			}
		}

		$has_instance         = static::$has_instance;
		static::$has_instance = true;

		return ! $has_instance;
	}
}
