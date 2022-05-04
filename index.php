<?php

namespace WP_Titan_1_0_0;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WP_Titan_1_0_0\App' ) ) {
	return;
}

const ROOT_FILE = __FILE__;

require_once dirname( ROOT_FILE ) . DIRECTORY_SEPARATOR . 'functions.php';
require_once dirname( ROOT_FILE ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
