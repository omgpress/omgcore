<?php

namespace WP_Titan_1_0_2\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce.
 */
class WC extends Plugin {

	/**
	 * Is WooCommerce active.
	 */
	public function is_active(): bool {
		return class_exists( 'woocommerce' );
	}

	/**
	 * Required. Set up the feature.
	 *
	 * Do not hide the call in the late hooks, as this may ruin the work of this feature.\
	 * The best way to call it directly in the "plugins_loaded" or "after_setup_theme" hooks.
	 */
	public function setup(): self {
		if ( ! $this->is_active() || $this->validate_single_call( __FUNCTION__ ) ) {
			return $this;
		}

		$this->enable_blocks();
		$this->enable_rest();

		return $this;
	}

	protected function enable_blocks(): void {
		add_filter(
			'use_block_editor_for_post_type',
			function ( bool $can_edit, string $post_type ): bool {
				if ( 'product' === $post_type ) {
					$can_edit = true;
				}

				return $can_edit;
			},
			10,
			2
		);
	}

	protected function enable_rest(): void {
		$filter = function ( array $args ): array {
			$args['show_in_rest'] = true;

			return $args;
		};

		add_filter( 'woocommerce_taxonomy_args_product_cat', $filter );
		add_filter( 'woocommerce_taxonomy_args_product_tag', $filter );
		add_filter( 'woocommerce_register_post_type_product', $filter );
	}
}
