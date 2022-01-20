<?php

namespace WP_Titan_0_9_2\Customizer\Control;

defined( 'ABSPATH' ) || exit;

class TinyMCE extends \WP_Customize_Control {

	public $type = 'wpt_tinymce';

	public function to_json() {
		parent::to_json();

		$this->json['auroobamakes_tinymce_toolbar1'] = isset( $this->input_attrs['toolbar1'] ) ? esc_attr( $this->input_attrs['toolbar1'] ) : 'bold italic bullist numlist alignleft aligncenter alignright link';

		$this->json['auroobamakes_tinymce_toolbar2'] = isset( $this->input_attrs['toolbar2'] ) ? esc_attr( $this->input_attrs['toolbar2'] ) : '';

		$this->json['auroobamakes_tinymce_mediabuttons'] = isset( $this->input_attrs['mediaButtons'] ) && ( true === $this->input_attrs['mediaButtons'] );

		$this->json['auroobamakes_tinymce_height'] = isset( $this->input_attrs['height'] ) ? esc_attr( $this->input_attrs['height'] ) : 200;
	}

	public function render_content() {
		?>
		<div class="tinymce-control">
			<span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
			</span>

			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description">
					<?php echo esc_html( $this->description ); ?>
				</span>
			<?php } ?>

			<textarea id="<?php echo esc_attr( $this->id ); ?>" class="wpt-tinymce-editor" <?php $this->link(); ?> aria-label="<?php echo esc_attr( $this->label ); ?>"><?php echo esc_attr( $this->value() ); ?></textarea>
		</div>
		<?php
	}
}
