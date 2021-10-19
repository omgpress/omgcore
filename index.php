<?php
/**
 * Plugin Name: WP Titan
 * Plugin URI:  https://github.com/dpripa/wp-titan
 * Description: Library for relaxed development of WordPress themes and plugins.
 * Version:     1.0.0
 * Author:      Dmitry Pripa
 * Author URI:  https://dpripa.com
 */

namespace WP_Titan;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WP_Titan\App' ) ) {
	return;
}

require_once dirname( __FILE__ ) . '/vendor/autoload.php';
require_once dirname( __FILE__ ) . '/functions.php';
