<?php

namespace WP_Titan_1_0_3\Integration;

use WP_Titan_1_0_3\App;

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
	 * Required for theme.
	 */
	public function setup(): App {
		if ( $this->validate_single_call( __FUNCTION__ ) ) {
			return $this->app;
		}

		$this->add_setup_action(
			function (): void {
				if ( ! $this->is_active() ) {
					return;
				}

				if ( $this->is_theme() ) {
					$this->enable_blocks();
				}
			}
		);

		return $this->app;
	}

	protected function enable_blocks(): void {
		$filter = function ( array $args ): array {
			$args['show_in_rest'] = true;

			return $args;
		};

		add_filter( 'woocommerce_taxonomy_args_product_cat', $filter );
		add_filter( 'woocommerce_taxonomy_args_product_tag', $filter );
		add_filter( 'woocommerce_register_post_type_product', $filter );

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
}
