<?php
if( !defined('ABSPATH') ){ exit();}
global $wpdb;$xyz_ihs_message='';
if(isset($_GET['xyz_ihs_msg']))
	$xyz_ihs_message = $_GET['xyz_ihs_msg'];
	if($xyz_ihs_message == 1){
		?>
	<div class="xyz_system_notice_area_style1" id="xyz_system_notice_area">
	Thank you for the suggestion.&nbsp;&nbsp;&nbsp;<span
	id="xyz_system_notice_area_dismiss">Dismiss</span>
	</div>
	<?php
	}
else if($xyz_ihs_message == 2){
		?>
		<div class="xyz_system_notice_area_style0" id="xyz_system_notice_area">
		wp_mail not able to process the request.&nbsp;&nbsp;&nbsp;<span
		id="xyz_system_notice_area_dismiss">Dismiss</span>
		</div>
		<?php
	}
else if($xyz_ihs_message == 3){
	?>
	<div class="xyz_system_notice_area_style0" id="xyz_system_notice_area">
	Please suggest a feature.&nbsp;&nbsp;&nbsp;<span
	id="xyz_system_notice_area_dismiss">Dismiss</span>
	</div>
	<?php
}
if (isset($_POST) && isset($_POST['xyz_ihs_send_mail']))
{
	
	if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_ihs_suggest_feature_form_nonce' ))
	{
		wp_nonce_ays( 'xyz_ihs_suggest_feature_form_nonce' );
		exit();
	}
	if (isset($_POST['xyz_ihs_suggested_feature']) && $_POST['xyz_ihs_suggested_feature']!='')
	{
		
		$xyz_ihs_feature_content=$_POST['xyz_ihs_suggested_feature'];
		$xyz_ihs_sender_email = get_option('admin_email');
		$entries0 = $wpdb->get_results( $wpdb->prepare( 'SELECT display_name FROM '.$wpdb->base_prefix.'users WHERE user_email=%s',array($xyz_ihs_sender_email)));
		foreach( $entries0 as $entry ) {
			$xyz_ihs_admin_username=$entry->display_name;
		}
		$xyz_ihs_recv_email='support@xyzscripts.com';
		$xyz_ihs_mail_subject="INSERT HTML SNIPPET - FEATURE SUGGESTION";
		$xyz_ihs_headers = array('From: '.$xyz_ihs_admin_username.' <'. $xyz_ihs_sender_email .'>' ,'Content-Type: text/html; charset=UTF-8');
		$wp_mail_processed=wp_mail( $xyz_ihs_recv_email, $xyz_ihs_mail_subject, $xyz_ihs_feature_content, $xyz_ihs_headers );
		if ($wp_mail_processed==true){
		 header("Location:".admin_url('admin.php?page=insert-html-snippet-suggest-features&xyz_ihs_msg=1'));
		 exit();
		}
		else 
		{
			header("Location:".admin_url('admin.php?page=insert-html-snippet-suggest-features&xyz_ihs_msg=2'));exit();
		}
	}
	else {
		header("Location:".admin_url('admin.php?page=insert-html-snippet-suggest-features&xyz_ihs_msg=3'));exit();
	}
}?>
<form method="post" >
<?php wp_nonce_field( 'xyz_ihs_suggest_feature_form_nonce' );?>
<h3>Contribute And Get Rewarded</h3>
<span style="color: #1A87B9;font-size:13px;padding-left: 10px;" >* Suggest a feature for this plugin and stand a chance to get a free copy of premium version of this plugin.</span>
<table  class="widefat" style="width:98%;padding-top: 10px;">
<tr><td>
<textarea name="xyz_ihs_suggested_feature" id="xyz_ihs_suggested_feature" style="width:750px;height:250px !important;"></textarea>
</td></tr>
<tr>
<td><input name="xyz_ihs_send_mail" class="button-primary" style="color:#FFFFFF;border-radius:4px;margin-bottom:10px;" type="submit" value="Send Mail To Us">
</td></tr>
</table>
</form>