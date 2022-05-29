<?php

namespace WP_Titan_1_1_0;

use ReflectionClass;

defined( 'ABSPATH' ) || exit;

/**
 * Manage application classes that used the simpleton pattern.
 *
 * The simpleton can be called everywhere.\
 * Simpleton is guarantee that a functionality of this class (part of logic) has be called only once.\
 * The simpleton `__constructor` is mainly used to call WordPress hooks and other simpleton classes.\
 * In most cases' simpleton classes are called without assignment to a variable. But you can do it if you have the need.\
 * Methods that calling by hook needs to be public. Because of this, we consider that all non-static methods of the simpleton class are reserved for a hook-based logic.\
 * Simpleton may also contain public static methods associated to the logic of the current class.\
 * <a href="https://github.com/dpripa/wp-titan#setupphp" target="_blank">Explore an example</a> of the simpleton usage for more details.
 */
class Simpleton extends Feature {

	protected $instances = array();

	/**
	 * Validate if current simpleton class was called.
	 *
	 * To declare current class as simpleton, just paste the following code at the beginning of the class `__constructor`:
	 * ```php
	 * if ( app()->simpleton()->validate( self::class ) ) {
	 *   return;
	 * }
	 * ```
	 *
	 * @param bool $is_extendable Is current class extendable. Default is `false` because in most cases' simpleton class isn't extended-friendly and should be `final`.
	 */
	public function validate( string $classname, bool $is_extendable = false ): bool {
		$has_instance = in_array( $classname, array_keys( $this->instances ), true );

		$this->instances[ $classname ] = array(
			'classname'     => $classname,
			'is_extendable' => $is_extendable,
		);

		if ( Core\Debugger::is_enabled() ) {
			if ( ! $is_extendable ) {
				$reflection = new ReflectionClass( $classname );

				if ( ! $reflection->isFinal() ) {
					$this->core->debugger()->die( "Simpleton class <code>$classname</code> must be final." );
				}
			}

			if ( $has_instance ) {
				$this->core->debugger()->die( "Simpleton class <code>$classname</code> must have just one instance call." );
			}
		}

		return $has_instance;
	}

	/**
	 * Get a list of simpleton classes that was called.
	 */
	public function get_instance_list(): array {
		return $this->instances;
	}
}
