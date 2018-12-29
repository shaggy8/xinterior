<?php
/*
Plugin Name: Easy Portfolio
Plugin URI: #
Description: Easy Portfolio plugin is a wordpress plugin designed to create interactive portfolio functionality with category filteration in to your wordpress website.
Version: 1.3
Author: Tushar Patel
Author URI: https://in.linkedin.com/in/tush03
License: GPLv2 or later
*/
define('PORTFOLIO_DIR', dirname(__FILE__));
define('PORTFOLIO_THEMES_DIR', PORTFOLIO_DIR . "/themes");
define('PORTFOLIO_URL', WP_PLUGIN_URL . "/" . basename(PORTFOLIO_DIR));
define('EP_PORTFOLIO_VERSION', '1.0');

//admin Custom Field Are Call
include("inc/add_custom_field.php");

//All ShortCode are show
add_shortcode('easy_portfolio', 'ep_portfolio');
add_shortcode('ep_latest_portfolio', 'ep_portfolio_latest_item');
add_shortcode('ep_portfolio_category', 'ep_portfolio_cat');
add_shortcode('ep_latest_portfolio_list', 'ep_latest_portfolio_list');
//Method And Action Are Call
add_filter('manage_edit-portfolio_columns', 'ep_add_new_portfolio_columns');
add_action('manage_portfolio_posts_custom_column', 'ep_manage_portfolio_columns', 5, 2);
add_action('init', 'ep_portfolio_register');
add_action('add_meta_boxes', 'ep_add_portfolio_metaboxes');
add_action('template_redirect', 'ep_template_post_detailspage');
add_action('wp_enqueue_scripts', 'portfolio_ep_scripts');

add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');

//All js and Css Are call
function portfolio_ep_scripts() {
	wp_enqueue_script('ep_prettyphoto',PORTFOLIO_URL.'/js/jquery.prettyPhoto.js', array('jquery'), EP_PORTFOLIO_VERSION);
	wp_enqueue_script('quicksand',PORTFOLIO_URL.'/js/jquery.quicksand.js', EP_PORTFOLIO_VERSION);
	wp_enqueue_script('easing',PORTFOLIO_URL.'/js/jquery.easing.1.3.js', EP_PORTFOLIO_VERSION);
	wp_enqueue_script('ep_portfolio_scripts',PORTFOLIO_URL.'/js/script.js', array('jquery'), EP_PORTFOLIO_VERSION); 
	wp_enqueue_style('prettyphoto_style', PORTFOLIO_URL . "/css/prettyPhoto.css");
	wp_enqueue_style('ep_portfolio_style', PORTFOLIO_URL . '/css/portfolio-style.css');
}

//Register Post Type
function ep_portfolio_register() {
    $labels = array(
        'name' => __('Easy Portfolio'),
        'singular_name' => __('Easy Portfolio'),
        'add_new' => __('Add Portfolio Item'),
        'add_new_item' => __('Add New Portfolio Item'),
        'edit_item' => __('Edit Portfolio Item'),
        'new_item' => __('New Portfolio Item'),
        'view_item' => __('View Portfolio Item'),
        'search_items' => __('Search Portfolio Item'),
        'not_found' => __('No Portfolio Items found'),
        'not_found_in_trash' => __('No Portfolio Items found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => __('Easy Portfolio')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => true,
        'rewrite' => array('slug' => 'portfolio'),
        'supports' => array(
            'title',
            'thumbnail',
            'editor',
           // 'excerpt',
			//'author',
			//'trackbacks',
			//'custom-fields',
 			//'revisions', 
			'page-attributes'
        ),
        'menu_position' => 23,
		'register_meta_box_cb' => 'ep_add_portfolio_metaboxes',
        'menu_icon' => 'dashicons-portfolio',
        'taxonomies' => array('portfolio_category')
    );
    register_post_type('portfolio', $args);
	ep_portfolio_register_taxonomies();
}
//Register Taxonomies
function ep_portfolio_register_taxonomies() {
    register_taxonomy('portfolio_category', 'portfolio', array('hierarchical' => true, 'label' => 'Portfolio Category', 'query_var' => true, 'rewrite' => array('slug' => 'portfolio-type')));
     if (count(get_terms('portfolio_category', 'hide_empty=0')) == 0) {
        register_taxonomy('type', 'portfolio', array('hierarchical' => true, 'label' => 'Item Type'));
        $_categories = get_categories('taxonomy=type&title_li=');
        foreach ($_categories as $_cat) {
            if (!term_exists($_cat->name, 'portfolio_category'))
                wp_insert_term($_cat->name, 'portfolio_category');
        }
        $portfolio = new WP_Query(array('post_type' => 'portfolio', 'posts_per_page' => '-1'));
        while ($portfolio->have_posts()) : $portfolio->the_post();
            $post_id = get_the_ID();
            $_terms = wp_get_post_terms($post_id, 'type');
            $terms = array();
            foreach ($_terms as $_term) {
                $terms[] = $_term->term_id;
            }
            wp_set_post_terms($post_id, $terms, 'portfolio_category');
        endwhile;
        wp_reset_query();
        register_taxonomy('type', array());
    } }
//Admin Dashobord Listing Portfolio Columns Title
function ep_add_new_portfolio_columns() {
	$columns['cb'] = '<input type="checkbox" />';
 	$columns['title'] = __('Title', 'easy_portfolio');
	$columns['thumbnail'] = __('Thumbnail', 'easy_portfolio' );
	$columns['author'] = __('Author', 'easy_portfolio' );
	$columns['portfolio_category'] = __('Portfolio Categories', 'easy_portfolio' );
	$columns['date'] = __('Date', 'easy_portfolio');
	return $columns; 
}
//Admin Dashobord Listing Portfolio Columns Manage
function ep_manage_portfolio_columns($columns) {
	global $post;
	switch ($columns) {
	case 'thumbnail':
	 	if(get_the_post_thumbnail( $post->ID, 'thumbnail' )){
				echo get_the_post_thumbnail( $post->ID, 'thumbnail' );
			}else{ 
				echo '<img width="150" height="150" src="'.PORTFOLIO_URL.'/images/no_images.jpg" class="attachment-thumbnail wp-post-image" alt="Penguins">';
		 }
	break;
 	case 'portfolio_category':
		$terms = wp_get_post_terms($post->ID, 'portfolio_category');  
		foreach ($terms as $term) {  
			echo $term->name .'&nbsp;&nbsp; ';  
		}  
	break;
	}
}
//Get All Portfolio Category
function ep_portfolio_list_categories() {
    $_categories = get_categories('taxonomy=portfolio_category');
    foreach ($_categories as $_cat){ ?>
         <li class="<?php echo $_cat->slug;?>">
            <a title="View all posts filed under <?php echo $_cat->name; ?>" href="<?php echo get_term_link($_cat->slug, 'portfolio_category'); ?>" rel="<?php echo $_cat->slug; ?>"><?php echo $_cat->name; ?></a>
        </li>
        <?php  }
}
//Get All Portfolio item Slug Front Side
function ep_portfolio_get_item_slug($post_id = null) {
    if ($post_id === null)
        return;
    $_terms = wp_get_post_terms($post_id, 'portfolio_category');
    foreach ($_terms as $_term) {
        echo $_term->slug.' ';
    }
}
//Get All Portfolio is show
function ep_portfolio() {
	ob_start();
	global $post;
  	require (PORTFOLIO_THEMES_DIR . "/default_template.php");
	return ob_get_clean();
}
//Get All Portfolio is show
function ep_portfolio_latest_item($portfolio_cal) {
	ob_start();
	global $post;
 	require (PORTFOLIO_THEMES_DIR . "/default_template.php");	
	return ob_get_clean();
}
//Get All Portfolio is show
function ep_portfolio_cat($pro_category) {
	ob_start();
 	require (PORTFOLIO_THEMES_DIR . "/category_template.php");
	return ob_get_clean();
}
//Portfolio Details Page 
function ep_template_post_detailspage(){
	global $post, $posts;
	  if('portfolio'== get_post_type()) {
		  	add_action('wp_head', 'ep_add_meta_tags');
 			require (PORTFOLIO_THEMES_DIR . "/portfolio_details.php");
			exit();
 	  }
}
//Get All Portfolio showing in List View
function ep_latest_portfolio_list($portfolio_list) {
	ob_start();
  	$limit=$portfolio_list['limit'];
	$order_by=$portfolio_list['order'];
  	$portfolio = new WP_Query(array('post_type' => 'portfolio','posts_per_page'=>$limit,'order'=>$order_by)); ?>
         <ul class="ep_portfolio_list">
        	<?php
			global $post;
			while ($portfolio->have_posts()) : $portfolio->the_post(); 
			$portfoliourl = get_post_meta( $post->ID, '_ep_portfoliourl', true);
			$imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "Full"); ?>
        	<li>
            	<div class="ep_portfolio_list_img">
                <?php if(get_the_post_thumbnail($post->ID, 'thumbnail')){ ?>
                     <a href="<?php echo $imgsrc[0]; ?>" rel="prettyPhoto[portfolio]">
                        <?php echo get_the_post_thumbnail($post->ID, 'thumbnail'); ?>
                    </a>
                  <?php }else{ ?> 
                 <img width="150" height="150" class="attachment-thumbnail wp-post-image" src="<?php echo PORTFOLIO_URL; ?>/images/no_images.jpg" />
                <?php }?>
                </div>
                <div class="ep_title">
                <a href="<?php echo get_permalink($post->ID); ?>"><?php the_title(); ?></a>
                 </div>
                <div class="ep_portfolio_desc">
                 <?php echo substr($post->post_content,0,200).'...'; ?>
                </div>
              <div class="ep_readmore">
              <a href="<?php echo get_permalink($post->ID); ?>">Read More →</a>&nbsp;&nbsp;
              <a target="_blank" href="<?php echo $portfoliourl; ?>">Go To Project</a>
              </div>
            </li>
            <?php endwhile; ?>
         </ul>
<?php return ob_get_clean(); }?>