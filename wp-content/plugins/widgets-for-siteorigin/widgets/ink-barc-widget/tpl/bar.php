<div class="iw-so-bar-counter <?php echo ( $animation ? 'iw-so-bars-animated' : '' ); ?>">

	<?php foreach( $bars as $i => $bar ) { ?>

		<div class="iw-so-bar info-position-<?php echo esc_attr( $info_position ); ?>">
			<?php if ( $info_position == 'above' ) : ?>
				<p class="iw-so-bar-title"><?php echo esc_html ( $bar['title'] ); ?></p>
				<?php if ( $show_percent ) : ?>
					<span class="iw-so-bar-percent"><?php echo esc_html ( $bar['percent'] ); ?>%</span>
				<?php endif; ?>
			<?php endif; ?>
			<div class="iw-so-bar-container">
				<span class="iw-so-bar-meter" <?php echo ( $animation ? 'aria-valuenow="' . esc_attr ( $bar['percent'] ) . '"' : 'style="width:' . esc_html ( $bar['percent'] ) . '%"' ); ?>></span>
			</div>
			<?php if ( $info_position == 'below' ) : ?>
				<p class="iw-so-bar-title"><?php echo esc_html ( $bar['title'] ); ?></p>
				<?php if ( $show_percent ) : ?>
					<span class="iw-so-bar-percent"><?php echo esc_html ( $bar['percent'] ); ?>%</span>
				<?php endif; ?>
			<?php endif; ?>
		</div>

	<?php } ?>

</div>
