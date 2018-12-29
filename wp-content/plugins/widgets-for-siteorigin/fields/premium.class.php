<?php

/**
 * Class Inked_Widget_Field_Premium
 */
class Inked_Widget_Field_Premium extends SiteOrigin_Widget_Field_Base {

	protected function render_field( $value, $instance ) {
		?>
		<p class="so-premium-link">Available with <a class="premium-field-link button" href="http://widgets.wpinked.com/" target="_blank">Widgets for SiteOrigin Pro</a></p>
		<?php
	}

	protected function sanitize_field_input( $value, $instance ) {
		return ! empty( $value );
	}

}
