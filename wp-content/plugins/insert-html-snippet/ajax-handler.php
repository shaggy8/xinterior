<?php
if ( ! defined( 'ABSPATH' ) )
	exit;


add_action('wp_ajax_ihs_backlink', 'xyz_ihs_ajax_backlink');
function xyz_ihs_ajax_backlink() {
	check_ajax_referer('xyz-ihs-blink','security');
    if(current_user_can('administrator')){
        global $wpdb;
        if(isset($_POST)){
            if(intval($_POST['enable'])==1){
                update_option('xyz_credit_link','ihs');
                echo 1;
            }
            if(intval($_POST['enable'])==-1){
                update_option('xyz_ihs_credit_dismiss','dis');
                echo -1;
            }
        }
    }die;
}

?>
