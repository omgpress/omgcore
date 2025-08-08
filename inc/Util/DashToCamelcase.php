<?php
namespace OmgCore\Util;

defined( 'ABSPATH' ) || exit;

trait DashToCamelcase {
	protected function dash_to_camelcase( string $str, bool $ucfirst = false ): string {
		$words    = explode( '-', $str );
		$words    = array_map( 'ucfirst', $words );
		$words[0] = $ucfirst ? ucfirst( $words[0] ) : lcfirst( $words[0] );

		return implode( '', $words );
	}
}
