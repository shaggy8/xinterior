<?php
/*
Template Name: Portfolio template
* Version: 1.4.6
*/
get_header(); ?>
	<div class="wrap">
		<div id="primary" class="content-area">
			<div id="container" class="<?php if ( 'twentyfourteen' == get_stylesheet() || 'twentytwelve' == get_stylesheet() ) echo 'site-content'; ?> site-main">
				<div id="content" class="hentry">
					<div class="breadcrumbs home_page_title entry-header">
						<?php prtfl_template_title(); ?>
					</div>
					<?php
					/* Get arguments for WP Query */
					$args = prtfl_get_query_args();

					do_action( 'bwsplgns_display_pdf_print_buttons', 'top' );

					$second_query = new WP_Query( $args );

					/* Display template content */
					echo prtfl_get_content( $second_query );

					wp_reset_query();

					do_action( 'bwsplgns_display_pdf_print_buttons', 'bottom' ); ?>
				</div><!-- #content -->

				<!-- Portfolio tmplate pagination -->
				<?php prtfl_pro_pagination( $second_query, $args ); ?>

				<?php comments_template(); ?>
			</div><!-- #container -->
		</div><!-- .content-area -->
		<?php get_sidebar(); ?>
	</div><!-- .wrap -->
<?php get_footer(); ?>