<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

abstract class View extends Feature {
	protected array $config_props = array(
		'dir' => 'view',
	);

	abstract public function get( string $name, array $args = array() ): string;
	abstract public function render( string $name, array $args = array() ): self;
}
