<?php

namespace O0W7_1\Extension;

use O0W7_1\Helper\Singleton;

defined( 'ABSPATH' ) || exit;

class Env {
	use Singleton;

	protected $root_host;
	protected $development_hosts = array(
		'localhost',
		'local',
		'loc',
		'development',
		'dev',
	);

	protected function __construct() {
		$url             = Url::get_instance();
		$host            = explode( '.', wp_parse_url( $url->get_home(), PHP_URL_HOST ) );
		$this->root_host = end( $host );
	}

	public function is_development(): bool {
		return in_array( $this->root_host, $this->development_hosts, true );
	}
}
