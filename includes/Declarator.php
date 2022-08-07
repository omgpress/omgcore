<?php

namespace Wpappy_1_0_6;

use ReflectionClass;

defined( 'ABSPATH' ) || exit;

/**
 * Manage application classes that used the declarator pattern.
 *
 * Declarator is designed to improve the declarative approach to developing WordPress applications. This pattern ensures that a functionality of the current class (part of logic) has be called only once.\
 * One of the important and characteristic details is that declarator classes are called without assignment to a variable. Looks like it doesn't make sense, but we just need the constructor to be called, so there's no need to create a reference to the object. Below we will look at why this is necessary.\
 * Another detail is that the declarator `::__constructor()` is used to call a lot of logic, that is calls of the WordPress hooks, other declarator classes, some methods (for example, adding settings). Although this is an anti-pattern from the point of view of the PHP community, we consider this approach to be justified in the WordPress environment, where, at least, interaction with the core is based on hooks, the methods of interaction with which don't favor the classical structuring of the PHP applications.\
 * And the main detail is the `Declarator::validate()` method call at the beginning of the class `::__constructor()` - thus we declare the class as a declarator.\
 * In most cases we call declarator classes inside the application setup action (`app()->setup($callback)`), when the WordPress environment is fully loaded. But they can also be called earlier if necessary for the application logic. For example, it could be the application configuration or the database initialization, other logic based on the activation and deactivation hooks, etc.\
 * Also, methods that calling by the WordPress hooks needs to be public. Because of this, we consider that all non-static methods of the declarator class are reserved for a hook-based logic. Use the private non-static methods to destructure it.\
 * Declarator may also contain public constants, static methods and properties as the public APIs that associated to the logic of the current class.\
 * And since declarator classes are not designed to be extensible, they must be final.
 */
class Declarator extends Feature {

	protected $called = array();

	/**
	 * Validate if current declarator class was called.
	 *
	 * This will return `true` if the current declarator has already been called before. So this makes it possible to avoid calling the contents of the constructor again. With `WP_DEBUG` and `WP_DEBUG_DISPLAY` enabled, calling again will create a critical error, so you can catch it.\
	 * To declare current class as declarator, just paste the following code at the beginning of the class `::__constructor()`:
	 * ``` php
	 * if ( app()->declarator()->validate( self::class ) ) {
	 *   return;
	 * }
	 * ```
	 */
	public function validate( string $classname ): bool {
		$has_instance = in_array( $classname, array_keys( $this->called ), true );

		$this->called[ $classname ] = array(
			'classname' => $classname,
		);

		if ( Core\Debug::is_enabled() ) {
			$reflection = new ReflectionClass( $classname );

			if ( ! $reflection->isFinal() ) {
				$this->core->debug()->die( "Declarator class <code>$classname</code> must be final." );
			}

			if ( $has_instance ) {
				$this->core->debug()->die( "Declarator class <code>$classname</code> must have just one instance call." );
			}
		}

		return $has_instance;
	}

	/**
	 * Get a list of declarator classnames that was called.
	 */
	public function get_called(): array {
		return $this->called;
	}
}
