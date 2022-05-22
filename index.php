<?php

namespace WP_Titan_1_0_20;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_Titan_1_0_20\App' ) ) {
	define( 'WP_Titan_1_0_20\ROOT_FILE', __FILE__ );
	define( 'WP_Titan_1_0_20\DEFAULT_PRIORITY', 10 );
	define( 'WP_Titan_1_0_20\HIGH_PRIORITY', 1 );
	define( 'WP_Titan_1_0_20\LOW_PRIORITY', 999999 );

	require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
}
