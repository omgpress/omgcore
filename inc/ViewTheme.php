<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class ViewTheme extends View {
	public function get( string $rel = '' ): string {
		return '';
	}

	public function render( string $rel = '' ): self {
		return $this;
	}
}
