<?php

namespace WP_Titan_1_1_1\Customizer\Control;

defined( 'ABSPATH' ) || exit;

/**
 * Type: 'line'.
 */
class Line extends \WP_Customize_Control {

	/** @ignore */
	public $type = 'wpt_line';

	protected function render_content() {
		?>
		<div class="wpt-control-line"></div>
		<?php
	}
}
