<?php

$classes = array( 'iw-so-button' );
if ( ! empty( $hover ) ) $classes[] = 'iw-so-button-hover';
if ( ! empty( $click ) ) $classes[] = 'iw-so-button-click';
if ( ! empty( $class ) ) $classes[] = esc_attr( $class );

$button_attributes = array(
	'class' => esc_attr ( implode ( ' ', $classes ) )
);
if ( ! empty( $target ) ) $button_attributes['target'] = '_blank';
if ( ! empty( $url ) ) $button_attributes['href'] = sow_esc_url( $url );
if ( ! empty( $id ) ) $button_attributes['id'] = esc_attr( $id );
if ( ! empty( $title ) ) $button_attributes['title'] = esc_attr( $title );
if ( ! empty( $onclick ) ) $button_attributes['onclick'] = esc_attr( $onclick );
?>

<div class="iw-so-button-base">

	<a <?php foreach ( $button_attributes as $name => $val ) echo $name . '="' . $val . '" ' ?>>
		<?php echo siteorigin_widget_get_icon( $icon ); ?>
		<?php echo esc_html ( $text ); ?>
	</a>

</div>
