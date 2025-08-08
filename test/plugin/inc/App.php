<?php
namespace TestPlugin;

use OmgCore\Asset;
use OmgCore\OmgApp;

defined( 'ABSPATH' ) || exit;

class App extends OmgApp {
	protected function __construct() {
		parent::__construct( ROOT_FILE, KEY );
	}

	public function asset(): Asset {
		return $this->asset;
	}

	protected function init(): callable {
		return function (): void {
			parent::init()();
			add_action( 'wp_enqueue_scripts', $this->enqueue_assets() );
		};
	}

	protected function enqueue_assets(): callable {
		return function (): void {
			$this->asset
				->enqueue_style( 'main' )
				->enqueue_script( 'main' );
		};
	}

	protected function activate(): callable {
		return function (): void {
			parent::activate()();
		};
	}

	protected function deactivate(): callable {
		return function (): void {
			parent::deactivate()();
		};
	}
}
