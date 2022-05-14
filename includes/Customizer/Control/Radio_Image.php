<?php

namespace WP_Titan_1_0_19\Customizer\Control;

defined( 'ABSPATH' ) || exit;

class Radio_Image extends \WP_Customize_Control {

	public $type       = 'wpt_radio_image';
	public $item_width = 100;

	protected function render_content() {
		?>
		<div class="wpt-radio-image">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>

			<div class="wpt-radio-image__items wpt-radio-image__items_width_<?php echo esc_html( $this->item_width ); ?>">
				<?php foreach ( $this->choices as $key => $value ) { ?>

					<label class="wpt-radio-image__item">
						<input
							type="radio"
							name="<?php echo esc_attr( $this->id ); ?>"
							value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>

						<img src="<?php echo esc_attr( $value['preview'] ); ?>" alt="<?php echo esc_attr( $value['name'] ); ?>" title="<?php echo esc_attr( $value['name'] ); ?>"/>
					</label>

				<?php } ?>
			</div>
		</div>
		<?php
	}
}
