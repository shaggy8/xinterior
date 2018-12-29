<?php 
if(isset($portfolio_cal)){
	$limit=$portfolio_cal['limit'];
	$order_by=$portfolio_cal['order'];
	$portfolio = new WP_Query(array('post_type' => 'portfolio','posts_per_page'=>$limit,'order'=>$order_by,'post_status' => 'publish')); 
}else{
	$portfolio = new WP_Query(array('post_type' => 'portfolio','posts_per_page'=>-1,'post_status' => 'publish')); 
?>
        <ul class="ep_filter ep_group">
             <li class="current all">
                <a href="#" rel="all">All</a>
             </li>
                <?php ep_portfolio_list_categories(); ?>
        </ul>
   		<?php } ?>
<ul class="ep_portfolio ep_group">
<?php $i=1; global $post; while ($portfolio->have_posts()) : $portfolio->the_post();
	$imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "Full"); 
	$portfoliourl = get_post_meta( $post->ID, '_ep_portfoliourl', true); ?>
      <li class="ep_item" data-id="id-<?php echo $i; ?>" data-type="<?php ep_portfolio_get_item_slug(get_the_ID()); ?>">
		<?php if(get_the_post_thumbnail($post->ID, 'medium')){?>
               <?php echo get_the_post_thumbnail($post->ID, 'medium',array( 'class' => 'portfolio_img' ) ); ?>
               <?php }else{ ?> 
               <img width="150" height="150" class="attachment-thumbnail wp-post-image" src="<?php echo PORTFOLIO_URL; ?>/images/no_images.jpg" />
               <?php }?>
               <div class="ep_portfoliourl">
					<div class="ep_portfoliourl_wrap">
                        <div class="ep_portfoliourl_cont">
                        <h4 class="item_title"><?php the_title(); ?></h4>
                            <div class="item_more">
                             <span><a class="zoom" target="_blank" href="<?php echo $imgsrc[0]; ?>" rel="prettyPhoto[portfolio]"></a></span>
                             <span><a class="link_post" href="<?php echo get_permalink($post->ID); ?>"></a></span>                     
                           </div>   
                        </div>
                    </div>
               </div>
 			</li> 
<?php $i++; endwhile;?>
</ul>