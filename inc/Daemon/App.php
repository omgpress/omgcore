<?php

namespace O0W7_1\Daemon;

use O0W7_1\Helper;
use O0W7_1\Extension;

defined( 'ABSPATH' ) || exit;

final class App implements \O0W7_1\App {
	use Helper\Singleton;

	private $key;
	private $root_file;

	public $fs;
	public $asset;
	public $hook;
	public $url;

	private function __construct() {
		$str             = Extension\Str::get_instance();
		$this->key       = $str->generate_random();
		$this->root_file = str_replace( 'Daemon', 'Bootstrap.php', __DIR__ );
		$this->fs        = new Extension\FS( $this );
		$this->asset     = new Extension\Asset( $this, $this->fs );
		$this->hook      = new Extension\Hook( $this );
		$this->url       = Extension\Url::get_instance();
	}

	public function validate_setup( string $namespace ): bool {
		return false;
	}

	public function get_key( string $key = '' ): string {
		return $this->key;
	}

	public function get_root_file(): string {
		return $this->root_file;
	}

	public function get_type(): string {
		return 'plugin';
	}
}
