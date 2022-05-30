<?php

namespace WP_Titan_1_1_1;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_Titan_1_1_1\App' ) ) {
	define( 'WP_Titan_1_1_1\ROOT_FILE', __FILE__ );

	require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
}
