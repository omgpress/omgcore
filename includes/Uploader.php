<?php

namespace WP_Titan_1_0_19;

defined( 'ABSPATH' ) || exit;

/**
 * Manage uploads.
 */
class Uploader extends Feature {

	public function add_image_size( string $slug, int $width, int $height = 0, bool $crop = false ): App {
		add_image_size( $this->app->get_key( $slug ), $width, $height, $crop );

		return $this->app;
	}

	public function get_path( $path = '' ): string {
		return WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads' . ( $path ? ( DIRECTORY_SEPARATOR . $path ) : '' );
	}

	public function get_url( $url = '' ): string {
		return WP_CONTENT_URL . DIRECTORY_SEPARATOR . 'uploads' . ( $url ? ( DIRECTORY_SEPARATOR . $url ) : '' );
	}
}
