<?php
namespace OmgCore\Plugin;

defined( 'ABSPATH' ) || exit;

class Info extends \OmgCore\Info {
	public function __construct( string $file_with_headers ) {
		$this->headers['name'] = 'Plugin Name';
		$this->headers['url']  = 'Plugin URI';

		parent::__construct( $file_with_headers );
	}
}
