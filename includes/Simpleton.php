<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

/**
 * Manage project classes that used the "simpleton" pattern.
 */
class Simpleton extends Feature {

	protected $instances = array();

	public function validate( string $classname, bool $is_extendable = false ): bool {
		$has_instance = in_array( $classname, array_keys( $this->instances ), true );

		$this->instances[ $classname ] = array(
			'classname'     => $classname,
			'is_extendable' => $is_extendable,
		);

		if ( $this->app->debug()->is_enabled() ) {
			if ( ! $is_extendable ) {
				$reflection = new \ReflectionClass( $classname );

				if ( ! $reflection->isFinal() ) {
					wpt_die(
						'Simpleton class <code>' . $classname . '</code> must be final.',
						'Not final Simpleton class found',
						$this->app->get_key()
					);
				}
			}

			if ( $has_instance ) {
				wpt_die(
					'Simpleton class <code>' . $classname . '</code> must have just one instance call.',
					'Duplicate Simpleton class instance found',
					$this->app->get_key()
				);
			}
		}

		return $has_instance;
	}

	public function get_instance_list(): array {
		return $this->instances;
	}
}
