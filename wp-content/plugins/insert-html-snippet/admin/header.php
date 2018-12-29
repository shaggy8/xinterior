<?php
	if ( ! defined( 'ABSPATH' ) )
		 exit;
?>
<style>
	a.xyz_header_link:hover{text-decoration:underline;}
	.xyz_header_link{text-decoration:none;}
</style>

<?php
if(isset($_POST['xyz_ihs_pre_ads'])){
	$xyz_ihs_pre_ads = intval($_POST['xyz_ihs_pre_ads']);
	update_option('xyz_ihs_premium_version_ads',$xyz_ihs_pre_ads);
}

/*
if(get_option('xyz_ihs_premium_version_ads')==1){?>
<div id="xyz-ihs-premium">

	<div style="float: left; padding: 0 5px">
		<h2 style="vertical-align: middle;">
			<a target="_blank" href="https://xyzscripts.com/wordpress-plugins/xyz-wp-insert-code-snippet/details">Fully Featured XYZ WP Insert Code Snippet Premium Plugin</a> - Just 19 USD
		</h2>
	</div>
	<div style="float: left; margin-top: 3px">
		<a target="_blank"
			href="https://xyzscripts.com/members/product/purchase/XYZWPICSPRE"><img
			src="<?php  echo plugins_url("images/orange_buynow.png",XYZ_INSERT_HTML_PLUGIN_FILE); ?>">
		</a>
	</div>
	<div style="float: left; padding: 0 5px">
	<h2 style="vertical-align: middle;text-shadow: 1px 1px 1px #686868">
			( <a 	href="<?php echo admin_url('admin.php?page=insert-html-snippet-about');?>">Compare Features</a> )
	</h2>
	</div>
</div>
<?php
}
*/

if(!$_POST && (isset($_GET['ihs_blink'])&&isset($_GET['ihs_blink'])=='en')){

	if (! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'],'ihs-blk')){
		wp_nonce_ays( 'ihs-blk');
		exit;
	}
	update_option('xyz_credit_link',"ihs");



?>
<div class="xyz_system_notice_area_style1" id="xyz_system_notice_area">
Thank you for enabling backlink.
 &nbsp;&nbsp;&nbsp;<span id="xyz_system_notice_area_dismiss">Dismiss</span>
</div>
<style type="text/css">
	.xyz_blink{
		display:none !important;
	}
</style>
<?php
}

if($_POST && isset($_POST['xyz_ihs_credit']))
{
	if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'],'ihs-setting_')){
		wp_nonce_ays( 'ihs-setting_' );
		exit;
	}
	$xyz_ihs_credit_link=sanitize_text_field($_POST['xyz_ihs_credit']);
	update_option('xyz_credit_link', $xyz_ihs_credit_link);
}

if((get_option('xyz_credit_link')=="0")&&(get_option('xyz_ihs_credit_dismiss')=="0")){
	?>
<div style="float:left;background-color: #FFECB3;border-radius:5px;padding: 0px 5px;margin-top: 10px;border: 1px solid #E0AB1B" id="xyz_ihs_backlink_div">

	Please do a favour by enabling backlink to our site. <a id="xyz_ihs_backlink" style="cursor: pointer;" >Okay, Enable</a>. <a id="xyz_ihs_dismiss" style="cursor: pointer;" >Dismiss</a>.
<script type="text/javascript">

	jQuery(document).ready(function() {
			jQuery('#xyz_ihs_backlink').click(function(){
				xyz_ihs_filter_blink(1)
			});

			jQuery('#xyz_ihs_dismiss').click(function(){
				xyz_ihs_filter_blink(-1)
			});

			function xyz_ihs_filter_blink(stat){

				<?php $ajax_ihs_nonce = wp_create_nonce( "xyz-ihs-blink" );?>
				var dataString = {
					action: 'ihs_backlink',
					security:'<?php echo $ajax_ihs_nonce; ?>',
					enable: stat
				};

				jQuery.post(ajaxurl, dataString, function(response) {

					if(response==1){
						jQuery("#xyz_ihs_backlink_div").html('Thank you for enabling backlink!');
					 	jQuery("#xyz_ihs_backlink_div").css('background-color', '#D8E8DA');
						jQuery("#xyz_ihs_backlink_div").css('border', '1px solid #0F801C');
						jQuery("select[id=xyz_ihs_credit] option[value=ihs]").attr("selected", true);
					}

					if(response==-1){
						jQuery("#xyz_ihs_backlink_div").remove();

					}

				});
			}
		});

</script>
</div>
	<?php
}
?>

<style>
#text {margin:50px auto; width:500px}
.hotspot {color:#900; padding-bottom:1px; border-bottom:1px dotted #900; cursor:pointer}

#tt {position:absolute; display:block; }
#tttop {display:block; height:5px; margin-left:5px;}
#ttcont {display:block; padding:2px 10px 3px 7px;  margin-left:-400px; background:#666; color:#FFF}
#ttbot {display:block; height:5px; margin-left:5px; }
</style>





<div style="margin-top: 10px">
<table style="float:right; ">
<tr>
<td  style="float:right;" >
	<a  class="xyz_header_link" style="margin-left:8px;margin-right:12px;"   target="_blank" href="https://xyzscripts.com/donate/5">Donate</a>
</td>
<td style="float:right;">
	<a class="xyz_header_link" style="margin-left:8px;" target="_blank" href="http://help.xyzscripts.com/docs/insert-html-snippet/faq/">FAQ</a>
</td>
<td style="float:right;">
	<a class="xyz_header_link" style="margin-left:8px;" target="_blank" href="http://help.xyzscripts.com/docs/insert-html-snippet/user-guide/introduction/">Readme</a>
</td>
<td style="float:right;">
	<a class="xyz_header_link" style="margin-left:8px;" target="_blank" href="http://xyzscripts.com/wordpress-plugins/insert-html-snippet/details">About</a>
</td>
<td style="float:right;">
	<a class="xyz_header_link" target="_blank" href="http://xyzscripts.com">XYZScripts</a>
</td>

</tr>
</table>
</div>

<div style="clear: both"></div>
