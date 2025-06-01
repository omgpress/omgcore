<?php
namespace OmgCore;

use OmgCore\Admin\Notice;

defined( 'ABSPATH' ) || exit;

class Admin {
	protected Notice $notice;

	public function __construct( string $key ) {
		$this->notice = new Notice( $key );
	}

	public function notice(): Notice {
		return $this->notice;
	}
}
