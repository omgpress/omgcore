<?php

namespace O0W7_1;

defined( 'ABSPATH' ) || exit;

trait Bootstrap {
	private static $instance;

	public static function get_instance( string $namespace, string $root_file ): self {
		if ( empty( self::$instance ) ) {
			self::$instance = new self( $namespace, $root_file );
		}

		return self::$instance;
	}

	private function __construct( string $namespace, string $root_file ) {}

	private $type;
	private $key;
	private $root_file;
	private $daemon;
	private $initialized_setups = array();

	private $valid_types = array(
		'plugin',
		'add-on',
		'theme',
	);

	private function init( string $namespace, string $root_file, string $type = 'plugin' ): void {
		if ( ! in_array( $type, $this->valid_types, true ) ) {
			$valid_types = array_reduce(
				$this->valid_types,
				function ( string $valid_types, $type ): string {
					return $valid_types . ( $valid_types ? ', ' : '' ) . $type;
				},
				''
			);

			throw new \Exception( "\"$type\" is an invalid type. Following types is valid: $valid_types" );
		}

		$this->type      = $type;
		$this->key       = strtolower( $namespace );
		$this->root_file = $root_file;
		$this->daemon    = \O0W7_1\Daemon\App::get_instance();
	}

	public function validate_setup( string $classname ): bool {
		$has_instance = in_array( $classname, $this->initialized_setups, true );

		$this->initialized_setups[] = $classname;

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$reflection = new \ReflectionClass( $classname );

			if ( ! $reflection->isFinal() ) {
				throw new \Exception( "The $classname class associated with the setup scope must be final." );
			}

			if ( $has_instance ) {
				throw new \Exception( "The $classname class associated with the setup scope must have just one instance call." );
			}
		}

		return $has_instance;
	}

	public function get_key( string $key = '' ): string {
		return $this->key . ( $key ? ( "_$key" ) : '' );
	}

	public function get_root_file(): string {
		return $this->root_file;
	}

	public function get_type(): string {
		return $this->type;
	}
}
