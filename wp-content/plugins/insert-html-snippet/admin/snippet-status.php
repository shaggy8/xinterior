<?php
if ( ! defined( 'ABSPATH' ) )
	 exit;
global $wpdb;
$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);
$xyz_ihs_snippetId = intval($_GET['snippetId']);
$xyz_ihs_snippetStatus = intval($_GET['status']);
$xyz_ihs_pageno = intval($_GET['pageno']);


if (! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'ihs-stat_'.$xyz_ihs_snippetId )) {
	wp_nonce_ays( 'ihs-stat_'.$xyz_ihs_snippetId );
	exit;
} 
else {
	if($xyz_ihs_snippetId=="" || !is_numeric($xyz_ihs_snippetId)){
		header("Location:".admin_url('admin.php?page=insert-html-snippet-manage'));
		exit();
	}
	$snippetCount = $wpdb->query($wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix.'xyz_ihs_short_code WHERE id=%d LIMIT 0,1' ,$xyz_ihs_snippetId)) ;
	if($snippetCount==0){
		header("Location:".admin_url('admin.php?page=insert-html-snippet-manage&xyz_ihs_msg=2'));
		exit();
	}else{
		$wpdb->update($wpdb->prefix.'xyz_ihs_short_code', array('status'=>$xyz_ihs_snippetStatus), array('id'=>$xyz_ihs_snippetId));
		header("Location:".admin_url('admin.php?page=insert-html-snippet-manage&xyz_ihs_msg=4&pagenum='.$xyz_ihs_pageno));
		exit();
	}
}
?>