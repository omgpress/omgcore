<?php

namespace WP_Titan_1_0_16\Customizer\Section;

defined( 'ABSPATH' ) || exit;

class Link extends \WP_Customize_Section {

	public $type = 'wpt_link';

	public $link   = '';
	public $target = '';

	protected function render() {
		?>
		<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="wpt-section-link accordion-section control-section control-section-<?php echo esc_attr( $this->type ); ?>">
			<a href="<?php echo esc_url( $this->link ); ?>" <?php echo esc_html( $this->target ? ( 'target="' . $this->target . '"' ) : '' ); ?>>
				<h3 class="accordion-section-title" tabindex="0">
					<?php echo esc_html( $this->title ); ?>
				</h3>
			</a>
		</li>
		<?php
	}
}
