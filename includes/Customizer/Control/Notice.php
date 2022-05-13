<?php

namespace WP_Titan_1_0_18\Customizer\Control;

defined( 'ABSPATH' ) || exit;

class Notice extends \WP_Customize_Control {

	public $type = 'wpt_notice';

	protected function render_content() {
		$allowed_html = array(
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'class'  => array(),
				'target' => array(),
			),
			'b'      => array(),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'i'      => array(
				'class' => array(),
			),
			'span'   => array(
				'class' => array(),
			),
			'code'   => array(),
		);

		?>
		<div class="wpt-notice">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="wpt-notice__title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="wpt-notice__description customize-control-description">
							<?php
							echo wp_kses(
								$this->description,
								$allowed_html
							);
							?>
						</span>
			<?php } ?>
		</div>
		<?php
	}
}
