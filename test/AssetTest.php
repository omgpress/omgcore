<?php
class AssetTest extends WP_UnitTestCase {
	public function test_asset(): void {
		try {
			$key = TestPlugin\app()->asset()->get_key( 'main' );

			TestPlugin\app()->asset()
				->enqueue_script( 'main' )
				->enqueue_style( 'main' );
			$this->assertEquals( 'test_plugin_main', $key );
			$this->assertTrue(
				isset( wp_scripts()->registered[ $key ] ),
				'JS asset is not registered'
			);
			$this->assertTrue(
				isset( wp_styles()->registered[ $key ] ),
				'CSS asset is not registered'
			);
		} catch ( Exception $e ) {
			$this->fail( 'Failed to create JS asset: ' . $e->getMessage() );
		}
	}
}
