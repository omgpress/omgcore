<?php

namespace WP_Titan_1_0_21\Helper;

use WP_Titan_1_0_21\App;
use WP_Titan_1_0_21\Core;

defined( 'ABSPATH' ) || exit;

trait Featured {

	protected function get_feature( ?App $app, ?Core $core, string $prop, string $classname ) /* mixed */ {
		if ( ! is_a( $this->$prop, $classname ) ) {
			$this->$prop = $app ?
				( $core ?
					new $classname( $app, $core ) :
					new $classname( $app )
				) : new $classname( $core );
		}

		return $this->$prop;
	}
}
