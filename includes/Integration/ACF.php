<?php

namespace WP_Titan_1_0_5\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * Advanced Custom Fields / ACF Pro.
 */
class ACF extends Plugin {

	protected $block_category;

	/**
	 * Is ACF or ACF Pro active.
	 */
	public function is_active(): bool {
		return class_exists( 'ACF' );
	}

	/**
	 * Is ACF Pro active.
	 */
	public function is_active_pro(): bool {
		return class_exists( 'acf_pro' );
	}

	/**
	 * Is ACF (basic version) active.
	 */
	public function is_active_basic(): bool {
		return $this->is_active() && ! $this->is_active_pro();
	}

	/**
	 * Block categories manager.
	 */
	public function block_category(): ACF\Block_Category {
		return $this->get_feature( $this->app, $this->core, 'block_category', ACF\Block_Category::class );
	}
}
