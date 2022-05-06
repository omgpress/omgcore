<?php

namespace WP_Titan_1_0_2;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WP_Titan_1_0_2\App' ) ) {
	return;
}

const ROOT_FILE = __FILE__;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
