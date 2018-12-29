<div class="iw-so-image-wrapper">
    <?php if ( $instance['image']['alignment'] == 'center' ) : ?><center><?php endif; ?>
    <div class="iw-so-image">

        <div class="iw-so-image-container">
            <?php echo wp_get_attachment_image( $instance['image']['image'], $instance['image']['img-size'] ); ?>
        </div>

        <div class="iw-so-caption-wrapper">
            <div class="iw-so-image-caption">
                <figcaption>
                    <?php echo $instance['caption']['title'] ? '<h3 class="iw-so-cap-title">' . $instance['caption']['title'] . '</h3>' : '' ?>
                    <?php echo $instance['caption']['desc'] ? '<p class="iw-so-cap-desc">' . $instance['caption']['desc'] . '</p>' : '' ?>
                </figcaption>
            </div>
        </div>

        <?php if ( $instance['image']['link-type'] == 'url' ) : ?>
            <a href="<?php echo sow_esc_url( $instance['link']['url'] ) ?>" target="<?php echo $instance['link']['window'] ? _blank : _self ?>"></a>
        <?php elseif ( $instance['image']['link-type'] == 'lightbox' && function_exists( 'wpinked_pro_so_widgets' ) ) : ?>
            <?php $lb_image_url = wp_get_attachment_image_src( $instance['image']['image'], $instance['lightbox']['img-size'], false ) ?>
            <a class="iw-so-image-popup" href="<?php echo $lb_image_url[0] ?>"></a>
        <?php endif; ?>

    </div>
    <?php if ( $instance['image']['alignment'] == 'center' ) : ?></center><?php endif; ?>
</div>
