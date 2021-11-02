<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WP_Titan_1_0_0\App' ) ) {
	return;
}

require_once dirname( __FILE__ ) . '/vendor/autoload.php';
require_once dirname( __FILE__ ) . '/functions.php';
