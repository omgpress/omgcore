<?php

namespace WP_Titan_1_0_1;

defined( 'ABSPATH' ) || exit;

/**
 * Helpers for working with popular third-party WordPress plugins.
 */
class Integration extends Feature {

	protected $acf;
	protected $polylang;
	protected $wc;
	protected $yoast;

	/**
	 * Advanced Custom Fields / ACF Pro.
	 */
	public function acf(): Integration\ACF {
		return $this->get_feature( $this->app, $this->core, 'acf', Integration\ACF::class );
	}

	/**
	 * Polylang.
	 */
	public function polylang(): Integration\Polylang {
		return $this->get_feature( $this->app, $this->core, 'polylang', Integration\Polylang::class );
	}

	/**
	 * Yoast SEO.
	 */
	public function yoast(): Integration\Yoast {
		return $this->get_feature( $this->app, $this->core, 'yoast', Integration\Yoast::class );
	}

	/**
	 * WooCommerce.
	 */
	public function wc(): Integration\WC {
		return $this->get_feature( $this->app, $this->core, 'wc', Integration\WC::class );
	}
}
