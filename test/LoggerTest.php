<?php
class LoggerTest extends WP_UnitTestCase {
	public static function setUpBeforeClass(): void {
		update_option( TestPlugin\app()->logger()->get_enabled_option_key(), 'yes' );
	}

	public static function tearDownAfterClass(): void {
		delete_option( TestPlugin\app()->logger()->get_enabled_option_key() );
	}

	public function test_logger_enabling(): void {
		$this->assertEquals(
			'yes',
			get_option( TestPlugin\app()->logger()->get_enabled_option_key(), 'no' )
		);
	}

	public function log_message_provider(): array {
		return array(
			'success_message' => array( 'Test success message', 'success' ),
			'info_message'    => array( 'Test info message', 'info' ),
			'warning_message' => array( 'Test warning message', 'warning' ),
			'error_message'   => array( 'Test error message', 'error' ),
			'array_message'   => array( array( 'key' => 'value' ), 'info' ),
			'object_message'  => array( (object) array( 'key' => 'value' ), 'info' ),
			'int_message'     => array( 12345, 'info' ),
			'float_message'   => array( 123.45, 'info' ),
			'bool_message'    => array( true, 'info' ),
			'null_message'    => array( null, 'info' ),
		);
	}

	/**
	 * @param mixed $message
	 * @throws ReflectionException
	 */
	protected function format_log_line( $message, string $level ): string {
		$reflection = new ReflectionClass( TestPlugin\app()->logger() );
		$method     = $reflection->getMethod( 'format_message' );

		$method->setAccessible( true );

		return $method->invoke( TestPlugin\app()->logger(), $message, $level );
	}

	/**
	 * @dataProvider log_message_provider
	 * @throws ReflectionException
	 */
	public function test_base_logging( $message, string $level ): void {
		TestPlugin\app()->logger()->{$level}( $message );
		$this->assertStringContainsString(
			$this->format_log_line( $message, $level ),
			TestPlugin\app()->logger()->get_content()
		);
	}

	/**
	 * @dataProvider log_message_provider
	 * @throws ReflectionException
	 */
	public function test_custom_group_logging( $message, string $level ): void {
		TestPlugin\app()->logger()->{$level}( $message, 'custom' );
		$this->assertStringContainsString(
			$this->format_log_line( $message, $level ),
			TestPlugin\app()->logger()->get_content( 'custom' )
		);
	}

	public function test_deleting(): void {
		TestPlugin\app()->logger()->delete_log_file( 'custom' );
		$this->assertFalse( file_exists( WP_CONTENT_DIR . '/uploads/test-plugin-log/custom.log' ) );

		TestPlugin\app()->logger()->delete_log_dir();
		$this->assertFalse( is_dir( WP_CONTENT_DIR . '/uploads/test-plugin-log' ) );
	}
}
