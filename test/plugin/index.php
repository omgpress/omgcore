<?php
/**
 * Plugin Name: Test Plugin
 * Plugin URI: https://omgpress.com/
 * Description: Plugin for OmgCore development purposes
 * Version: 1.0.0
 * Text Domain: test-plugin
 * Author: OmgPress
 * Author URI: https://omgpress.com
 * Requires PHP: 7.4.0
 * Requires at least: 5.0.0
 */
namespace TestPlugin;

use Exception;

defined( 'ABSPATH' ) || exit;

const KEY       = 'test_plugin';
const ROOT_FILE = __FILE__;

$autoload = __DIR__ . '/vendor/autoload.php';
$omgcore  = dirname( __DIR__, 2 ) . '/vendor/autoload.php';

if ( ! file_exists( $autoload ) ) {
	throw new Exception( 'Autoloader not exists' );
}

if ( ! file_exists( $omgcore ) ) {
	throw new Exception( 'OmgCore not exists' );
}

require_once $autoload;
require_once $omgcore;

function app(): App {
	return App::get_instance();
}

app();
