<?php
function ep_add_portfolio_metaboxes() {
  	add_meta_box('ep_portfolio_url', 'Portfolio Settings', 'ep_portfolio_urls', 'portfolio', 'normal', 'high');	 
}
function ep_portfolio_urls() {
	global $post;
 	$portfoliourl = get_post_meta($post->ID, '_ep_portfoliourl', true);
	$metatitle = get_post_meta($post->ID, '_ep_metatitle', true);
	$metadescription = get_post_meta($post->ID, '_ep_metadesc', true); ?>
        <table>
          <tr>
            <th scope="row"><label>Portfolio URL</label></th>
             <td><input size="50" type="text" name="_ep_portfoliourl" value="<?php echo $portfoliourl; ?>"/></td>
              <td><span>Enter Your Portfolio URL optional)</span></td>
          </tr>
          <tr>
            <th scope="row"><label>Meta Title</label></th>
             <td><input size="50" type="text" name="_ep_metatitle" value="<?php echo $metatitle; ?>"/></td>
              <td><span>Enter Portfolio Meta Title (optional)</span></td>
          </tr>
          <tr>
            <th scope="row"><label>Meta Description</label></th>   
            <td><textarea cols="50" rows="5" name="_ep_metadesc"><?php echo $metadescription; ?></textarea></td>
            <td><span>Enter Portfolio Meta Description(optional)</span></td>
          </tr>
        </table>
<?php } ?>
<?php 
function ep_save_portfolio($post_id, $post) {
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	$data = array();
	if(isset($_POST['_ep_portfoliourl'])){
		$data['_ep_portfoliourl'] = $_POST['_ep_portfoliourl'];
 	}
	if(isset($_POST['_ep_metatitle'])){
 		$data['_ep_metatitle'] = $_POST['_ep_metatitle'];
 	}
	if(isset($_POST['_ep_metadesc'])){
 		$data['_ep_metadesc'] = $_POST['_ep_metadesc'];
	}
	foreach ($data as $key => $value) { 
		if( $post->post_type == 'revision' ) return; 
		$value = implode(',', (array)$value);  
		if(get_post_meta($post->ID, $key, FALSE)) { 
			update_post_meta($post->ID, $key, $value);
		} else { 
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key);  
	}
}
add_action('save_post', 'ep_save_portfolio', 1, 2);  
?>