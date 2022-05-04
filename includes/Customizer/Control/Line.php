<?php

namespace WP_Titan_1_0_0\Customizer\Control;

defined( 'ABSPATH' ) || exit;

class Line extends \WP_Customize_Control {

	public $type = 'wpt_line';

	protected function render_content() {
		?>
		<div class="wpt-line"></div>
		<?php
	}
}
