<?php
/**
 * @package Sydney
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-single_post">
		<div class="row">
			<div class="col-xs-12">
				<header class="entry-header">
					<?php if (get_theme_mod('hide_meta_single') != 1 ) : ?>
					<div class="meta-post">
						<?php sydney_posted_on(); ?>
					</div><!-- .entry-meta -->
					<?php endif; ?>
					<?php the_title( '<h1 class="title-post">', '</h1>' ); ?>
				</header><!-- .entry-header -->
			</div>
			<div class="col-xs-12">
				<div class="single-post_content">
					<div class="row">
						<div class="col-xs-12">
							<?php if ( has_post_thumbnail() && ( get_theme_mod( 'post_feat_image' ) != 1 ) ) : ?>
								<div class="entry-thumb">
									<?php the_post_thumbnail('sydney-large-thumb'); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="entry-content">
								<div class="col-xs-12">
									<?php the_content(); ?>
									<?php
										wp_link_pages( array(
											'before' => '<div class="page-links">' . __( 'Pages:', 'sydney' ),
											'after'  => '</div>',
										) );
									?>
								</div>
							</div><!-- .entry-content -->
						</div>
						<div class="col-md-4">
							<div class="col-xs-12">
								<div class="single-post_sidebar-section">
									<div class="col-xs-12">
										<?php get_sidebar('2'); ?>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- <footer class="entry-footer">
		<?php sydney_entry_footer(); ?>
	</footer> -->
</article><!-- #post-## -->
