<?php
/*
* Template - Portfolio post
* Version: 1.4.6
*/
get_header(); ?>
	<div class="wrap">
		<div id="primary" class="content-area">
			<div id="container" class="<?php if ( 'twentyfourteen' == get_stylesheet() || 'twentytwelve' == get_stylesheet() ) echo 'site-content'; ?> site-main">
				<div id="content" class="hentry">
					<div class="breadcrumbs home_page_title entry-header">
						<?php prtfl_post_template_title(); ?>
					</div>
					<?php do_action( 'bwsplgns_display_pdf_print_buttons', 'top' );
					/* Display template content */
					echo prtfl_post_get_content();

					do_action( 'bwsplgns_display_pdf_print_buttons', 'bottom' ); ?>
				</div><!-- #content -->
			</div><!-- #container -->
		</div><!-- .content-area -->
	<?php get_sidebar(); ?>
	</div><!-- .wrap -->
<?php get_footer(); ?>