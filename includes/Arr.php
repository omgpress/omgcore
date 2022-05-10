<?php

namespace WP_Titan_1_0_11;

defined( 'ABSPATH' ) || exit;

/**
 * Helpers for working with arrays.
 */
class Arr extends Feature {

	public function map_associative( callable $callback, array $array ): array {
		$result = array();

		foreach ( $array as $key => $val ) {
			$result[ $key ] = $callback( $key, $val );
		}

		return $result;
	}
}
