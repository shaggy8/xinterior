<?php
global $wpinked_widget_count;

$acc_no = 1;

if( $id ):
	$unique = $id;
else :
	$unique = 'toggle-' . ++$wpinked_widget_count;
endif;
?>

<div class="iw-so-accordion-plain iw-so-accordion<?php echo $expand . $toggle; ?>">

	<?php foreach( $toggles as $i => $toggle ) { ?>

		<div class="iw-so-acc-item<?php echo ( $toggle['active'] == 1 ? ' iw-so-acc-item-active' : '' ); ?>"  id="<?php echo $unique . '-' . $acc_no; ?>">

			<a href="#" class="iw-so-acc-title <?php echo $title_align; ?>">
				<?php echo esc_html( $toggle['title'] ); ?>
				<span class="iw-so-tgl-open"><?php echo siteorigin_widget_get_icon( $icon_open, $icon_styles ); ?></span>
				<span class="iw-so-tgl-close"><?php echo siteorigin_widget_get_icon( $icon_close, $icon_styles ); ?></span>
			</a>

			<div class="iw-so-acc-content">
				<?php echo wpinked_so_pb_content( $toggle['content'], $toggle['autop'], $toggle['content_type'], $toggle['builder'] ); ?>
			</div>

		</div>

		<?php $acc_no++; ?>

	<?php } ?>

</div>
