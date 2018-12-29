<?php
$btn_1_classes = array( 'iw-so-button', 'iw-so-button-1' );
if ( ! empty( $btn_hover ) ) $btn_1_classes[] = 'iw-so-button-hover';
if ( ! empty( $btn_click ) ) $btn_1_classes[] = 'iw-so-button-click';
if ( ! empty( $btn_1_class ) ) $btn_1_classes[] = esc_attr( $btn_1_class );
$btn_1_attributes = array( 'class' => esc_attr ( implode ( ' ', $btn_1_classes ) ) );
if ( ! empty( $btn_1_target ) ) $btn_1_attributes['target'] = '_blank';
if ( ! empty( $btn_1_url ) ) $btn_1_attributes['href'] = sow_esc_url( $btn_1_url );
if ( ! empty( $btn_1_id ) ) $btn_1_attributes['id'] = esc_attr( $btn_1_id );
if ( ! empty( $btn_1_title ) ) $btn_1_attributes['title'] = esc_attr( $btn_1_title );
if ( ! empty( $btn_1_onclick ) ) $btn_1_attributes['onclick'] = esc_attr( $btn_1_onclick );

$btn_2_classes = array( 'iw-so-button', 'iw-so-button-2' );
if ( ! empty( $btn_hover ) ) $btn_2_classes[] = 'iw-so-button-hover';
if ( ! empty( $btn_click ) ) $btn_2_classes[] = 'iw-so-button-click';
if ( ! empty( $btn_2_class ) ) $btn_2_classes[] = esc_attr( $btn_2_class );
$btn_2_attributes = array( 'class' => esc_attr ( implode ( ' ', $btn_2_classes ) ) );
if ( ! empty( $btn_2_target ) ) $btn_2_attributes['target'] = '_blank';
if ( ! empty( $btn_2_url ) ) $btn_2_attributes['href'] = sow_esc_url( $btn_2_url );
if ( ! empty( $btn_2_id ) ) $btn_2_attributes['id'] = esc_attr( $btn_2_id );
if ( ! empty( $btn_2_title ) ) $btn_2_attributes['title'] = esc_attr( $btn_2_title );
if ( ! empty( $btn_2_onclick ) ) $btn_2_attributes['onclick'] = esc_attr( $btn_2_onclick );

$icon_styles = array();
?>

<div class="iw-so-call-to-action iw-so-c2a-<?php echo esc_attr( $orientation ); ?>">

	<div class="iw-so-c2a-text">

		<?php if ( $title ): ?>
			<h3 class="iw-so-c2a-title iw-text-<?php echo esc_attr( $align ); ?>"><?php echo wp_kses_post( $title ); ?></h3>
		<?php endif; ?>
		<?php if ( $subtitle ): ?>
			<p class="iw-so-c2a-subtitle iw-text-<?php echo esc_attr( $align ); ?>"><?php echo wp_kses_post( $subtitle ); ?></p>
		<?php endif; ?>

	</div>

	<div class="iw-so-c2a-buttons">
		<?php if ( $btn_1_text ): ?>
			<div class="iw-so-c2a-btn-base iw-text-center">
				<a <?php foreach($btn_1_attributes as $name => $val) echo $name . '="' . $val . '" ' ?>>
					<?php echo siteorigin_widget_get_icon( $btn_1_icon, $icon_styles ); ?>
					<?php echo esc_html( $btn_1_text ); ?>
				</a>
			</div>
		<?php endif; ?>
		<?php if ( $btn_2_text ): ?>
			<div class="iw-so-c2a-btn-base iw-text-center">
				<a <?php foreach($btn_2_attributes as $name => $val) echo $name . '="' . $val . '" ' ?>>
					<?php echo siteorigin_widget_get_icon( $btn_2_icon, $icon_styles ); ?>
					<?php echo esc_html( $btn_2_text ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>

</div>
