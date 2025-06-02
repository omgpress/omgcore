<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class ViewPlugin extends View {
	public function get( string $rel = '' ): string {
		return '';
	}

	public function render( string $rel = '' ): self {
		return $this;
	}
}
