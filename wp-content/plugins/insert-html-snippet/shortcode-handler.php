<?php 
if ( ! defined( 'ABSPATH' ) ) 
	exit;
	
global $wpdb;

add_shortcode('xyz-ihs','xyz_ihs_display_content');		

function xyz_ihs_display_content($xyz_snippet_name){
	global $wpdb;

	if(is_array($xyz_snippet_name)){
		$snippet_name = $xyz_snippet_name['snippet'];
		
		$query = $wpdb->get_results($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."xyz_ihs_short_code WHERE title=%s" ,$snippet_name));
		
		if(count($query)>0){
			
			foreach ($query as $sippetdetails){
			if($sippetdetails->status==1)
				return do_shortcode($sippetdetails->content) ;
			else 
				return '';
				break;
			}
			
		}else{

			return '';		
		}
		
	}
}


add_filter('widget_text', 'do_shortcode'); // to run shortcodes in text widgets