<?php

namespace WP_Titan_1_0_7;

defined( 'ABSPATH' ) || exit;

/**
 * Manage uploads.
 */
class Uploader extends Feature {

	public function add_image_size( string $slug, int $width, int $height = 0, bool $crop = false ): App {
		add_image_size( $this->app->get_key( $slug ), $width, $height, $crop );

		return $this->app;
	}
}
