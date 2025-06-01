<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

abstract class View {
	abstract public function get( string $rel = '' ): string;
	abstract public function render( string $rel = '' ): self;
}
