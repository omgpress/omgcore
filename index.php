<?php

namespace WP_Titan_1_1_0;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_Titan_1_1_0\App' ) ) {
	define( 'WP_Titan_1_1_0\ROOT_FILE', __FILE__ );

	require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
}
