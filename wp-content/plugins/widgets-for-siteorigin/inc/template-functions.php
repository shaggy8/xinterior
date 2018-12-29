<?php

/*
 * Display the testimonial template
 */
if ( ! function_exists ( 'wpinked_so_testimonial_template' ) ) :
function wpinked_so_testimonial_template( $name, $testimonial, $styling ) {

	if ( in_array( $styling['design'], array( 'by-above', 'by-below', 'boxed-by-above', 'boxed-by-below' ), true ) )  : ?>

		<div class="iw-so-testimonial <?php echo 'iw-so-testimonial-' . $styling['design']; ?>">

			<?php if ( $styling['design'] == 'by-above' || $styling['design'] == 'boxed-by-above' ) : ?>
				<div class="iw-so-testimonial-byline iw-so-testi-<?php echo $styling['text'] ?>">
					<div class="iw-so-testimonial-byline-wrapper<?php echo ( $testimonial['image'] ? ' iw-so-testimonial-has-image' : '' ) ?>">
						<?php echo wp_get_attachment_image( $testimonial['image'], array( '50', '50' ) ); ?>
						<?php wpinked_so_testimonial_name( $name, $testimonial['company'], $testimonial['link'], $testimonial['target'], 'iw-text-left' ); ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="iw-so-testimonial-content boxed-<?php echo $styling['text'] ?>">
				<?php if ( $testimonial['content'] ) : ?>
					<div class="iw-so-testimonial-message <?php echo $testimonial['text']; ?>"><?php echo wpinked_so_editor( $testimonial['content'], $testimonial['autop'] ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( $styling['design'] == 'by-below' || $styling['design'] == 'boxed-by-below' ) : ?>
				<div class="iw-so-testimonial-byline iw-so-testi-<?php echo $styling['text'] ?>">
					<div class="iw-so-testimonial-byline-wrapper<?php echo ( $testimonial['image'] ? ' iw-so-testimonial-has-image' : '' ) ?>">
						<?php echo wp_get_attachment_image( $testimonial['image'], array( '50', '50' ) ); ?>
						<?php wpinked_so_testimonial_name( $name, $testimonial['company'], $testimonial['link'], $testimonial['target'], 'iw-text-left' ); ?>
					</div>
				</div>
			<?php endif; ?>

		</div>

	<?php else : ?>

		<div class="iw-so-testimonial <?php echo 'iw-so-testimonial-' . $styling['design']; ?>">

			<?php if ( $testimonial['image'] && ( $styling['design'] == 'above' || $styling['design'] == 'left' || $styling['design'] == 'right' ) ) : ?>
				<div class="iw-so-testimonial-img">
					<?php echo wp_get_attachment_image( $testimonial['image'], 'full' ); ?>
				</div>
			<?php endif; ?>

			<div class="iw-so-testimonial-content">
				<?php if ( $testimonial['content'] ) : ?>
					<div class="iw-so-testimonial-message <?php echo $testimonial['text']; ?>"><?php echo do_shortcode( $testimonial['content'] ); ?></div>
				<?php endif; ?>
				<?php if ( $testimonial['image'] && ( $styling['design'] == 'between' ) ) : ?>
					<div class="iw-so-testimonial-img">
						<?php echo wp_get_attachment_image( $testimonial['image'], 'full' ); ?>
					</div>
				<?php endif; ?>
				<?php wpinked_so_testimonial_name( $name, $testimonial['company'], $testimonial['link'], $testimonial['target'], $styling['text'] ); ?>
			</div>

			<?php if ( $testimonial['image'] && ( $styling['design'] == 'below' ) ) : ?>
				<div class="iw-so-testimonial-img">
					<?php echo wp_get_attachment_image( $testimonial['image'], 'full' ); ?>
				</div>
			<?php endif; ?>

		</div>

	<?php endif;

}
endif;

/*
 * Display the person template
 */
if ( ! function_exists ( 'wpinked_so_person_template' ) ) :
function wpinked_so_person_template( $name, $person, $social, $styling ) { ?>

	<div class="iw-so-person <?php echo ( $styling['img-width'] == false ? 'iw-so-person-fit' : '' ) ?> iw-so-person-<?php echo $styling['layout'] ?>">

		<div class="iw-so-person-img">
			<?php echo wp_get_attachment_image( $person['image'], 'full' ); ?>
			<?php echo wp_get_attachment_image( $person['image-hover'], 'full' ); ?>
			<div class="iw-so-person-ol">
				<?php if( $styling['design'] == 'about' ) : ?>
					<p class="iw-so-person-about <?php echo esc_attr( $styling['align'] );?>"><?php echo wp_kses_post( $person['about'] ); ?></p>
				<?php endif; ?>
				<?php if( $styling['design'] == 'icons' ) : ?>
					<?php wpinked_so_person_social( $social['profiles'], $styling['align'], $social['target'] ); ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="iw-so-person-content">
			<h4 class="iw-so-person-name <?php echo esc_attr( $styling['align'] );?>"><?php echo esc_html( $name ); ?></h4>
			<p class="iw-so-person-desig <?php echo esc_attr( $styling['align'] );?>"><?php echo esc_html( $person['designation'] ); ?></p>
			<?php if( $styling['design'] != 'about' ) : ?>
				<p class="iw-so-person-about <?php echo esc_attr( $styling['align'] );?>"><?php echo wp_kses_post( $person['about'] ); ?></p>
			<?php endif; ?>
			<?php if( $styling['design'] != 'icons' ) : ?>
				<?php wpinked_so_person_social( $social['profiles'], $styling['align'], $social['target'] ); ?>
			<?php endif; ?>
		</div>

	</div>

<?php }
endif;

/*
 * Display the editor content
 */
if ( ! function_exists ( 'wpinked_so_editor' ) ) :
function wpinked_so_editor( $content, $autop ) {
	if( $autop == 1 ) {
		$content = wpautop( $content );
	}
	$content = do_shortcode( $content );

	return $content;
}
endif;

/*
 * Display by content type
 */
if ( ! function_exists ( 'wpinked_so_pb_content' ) ) :
function wpinked_so_pb_content( $content, $autop, $type, $builder ) {

	if ( $type == 'content' || $type == '' ) {
		return wpinked_so_editor( $content, $autop );
	} elseif ( $type == 'builder' && function_exists( 'wpinked_pro_so_pb_content' ) ) {
		return wpinked_pro_so_pb_content( $builder );
	} else {
		return wpinked_so_editor( $content, $autop );
	}

}
endif;
