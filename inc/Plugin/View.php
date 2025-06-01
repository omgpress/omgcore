<?php
namespace OmgCore\Plugin;

defined( 'ABSPATH' ) || exit;

class View extends \OmgCore\View {
	public function get( string $rel = '' ): string {
		return '';
	}

	public function render( string $rel = '' ): self {
		return $this;
	}
}
