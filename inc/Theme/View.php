<?php
namespace OmgCore\Theme;

defined( 'ABSPATH' ) || exit;

class View extends \OmgCore\View {
	public function get( string $rel = '' ): string {
		return '';
	}

	public function render( string $rel = '' ): self {
		return $this;
	}
}
