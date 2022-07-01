<?php

namespace Wpappy_1_0_2\Setting;

use Wpappy_1_0_2\App;
use Wpappy_1_0_2\Core;

defined( 'ABSPATH' ) || exit;

abstract class Base {

	protected $app;
	protected $core;
	protected $storage;
	protected $page;

	public function __construct( App $app, Core $core, Storage $storage, string $page ) {
		$this->app     = $app;
		$this->core    = $core;
		$this->storage = $storage;
		$this->page    = $page;
	}
}
