<?php

namespace Wpappy_1_0_6;

defined( 'ABSPATH' ) || exit;

/**
 * Manage media.
 */
class Media extends Feature {

	protected $upload;
	protected $image_sizes = array();

	public function add_image_size( string $slug, int $width, int $height = 0, bool $crop = false ): App {
		$key = $this->app->get_key( $slug );

		$this->image_sizes[ $slug ] = array(
			'key'    => $key,
			'slug'   => $slug,
			'width'  => $width,
			'height' => $height,
			'crop'   => $crop,
		);

		add_image_size( $key, $width, $height, $crop );

		return $this->app;
	}

	/**
	 * Manage media uploads.
	 */
	public function upload(): Media\Upload {
		return $this->get_feature( $this->app, $this->core, 'upload', Media\Upload::class );
	}

	public function get_image_size( string $slug, bool $need_dataset = false ) /* string|array */ {
		$size_keys = array_keys( $this->image_sizes );

		if ( ! in_array( $slug, $size_keys, true ) ) {
			$this->core->debug()->die( "The <code>'$slug'</code> image size needs to be added to the application setup." );

			return $need_dataset ?
				array(
					'key'  => 'post-thumbnail',
					'slug' => 'post-thumbnail',
					'width'  => 0,
					'height' => 0,
					'crop'   => false,
				) :
				'post-thumbnail';
		}

		if ( $need_dataset ) {
			return $this->image_sizes[ $slug ];
		}

		return $this->image_sizes[ $slug ]['key'];
	}
}
