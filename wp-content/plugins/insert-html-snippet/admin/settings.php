<?php
if ( ! defined( 'ABSPATH' ) )
	 exit;
global $wpdb;
// Load the options


if(!$_POST && isset($_GET['ihs_notice'])&& $_GET['ihs_notice'] == 'hide')
{
	if (! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'],'ihs-shw')){
		wp_nonce_ays( 'ihs-shw');
		exit;
	}
	update_option('xyz_ihs_dnt_shw_notice', "hide");
	?>
<style type='text/css'>
#xyz_ihs_notice_td
{
display:none !important;
}
</style>
<div class="xyz_system_notice_area_style1" id="xyz_system_notice_area">
Thanks again for using the plugin. We will never show the message again.
 &nbsp;&nbsp;&nbsp;<span id="xyz_system_notice_area_dismiss">Dismiss</span>
</div>
<?php
}

	if($_POST){
		if (! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'ihs-setting_' )) {
			wp_nonce_ays( 'ihs-setting_' );
			exit;
		}
		else {
			$_POST=xyz_trim_deep($_POST);
			$_POST = stripslashes_deep($_POST);
			$xyz_ihs_limit = abs(intval($_POST['xyz_ihs_limit']));
			$xyz_ihs_pre_ads = intval($_POST['xyz_ihs_pre_ads']);
			if($xyz_ihs_limit==0)$xyz_ihs_limit=20;
			$xyz_ihs_credit = sanitize_text_field($_POST['xyz_ihs_credit']);
			if($xyz_ihs_credit=="ihs" || $xyz_ihs_credit==0)
				{
					update_option('xyz_credit_link',$xyz_ihs_credit);
				}

			$xyz_ihs_sortfield=sanitize_text_field($_POST['xyz_ihs_sort_by_field']);
			if(($xyz_ihs_sortfield=="title")||($xyz_ihs_sortfield=="id"))
				{
					update_option('xyz_ihs_sort_field_name',$xyz_ihs_sortfield);
				}

			$xyz_ihs_sortorder=sanitize_text_field($_POST['xyz_ihs_sort_by_order']);
			if(($xyz_ihs_sortorder=="asc")||($xyz_ihs_sortorder=="desc"))
				{
					update_option('xyz_ihs_sort_order',$xyz_ihs_sortorder);
				}


			update_option('xyz_ihs_limit',$xyz_ihs_limit);

?>
<div class="xyz_system_notice_area_style1" id="xyz_system_notice_area">
	Settings updated successfully. &nbsp;&nbsp;&nbsp;
	<span id="xyz_system_notice_area_dismiss">Dismiss</span>
</div>
<?php
		}
	}
?>
<div>
	<form method="post">
		<?php wp_nonce_field('ihs-setting_');?>
		<div style="float: left;width: 98%">
			<fieldset style=" width:100%; border:1px solid #F7F7F7; padding:10px 0px 15px 10px;">
				<legend ><h3>
					Settings
					</h3></legend>
				<table class="widefat"  style="width:99%;">
					<tr valign="top">
						<td scope="row" >
							<label for="xyz_ihs_sort">Sorting of snippets</label>
						</td>
						<td>
							<select id="xyz_ihs_sort_by_field" name="xyz_ihs_sort_by_field">
								<option value="id" <?php if(isset($_POST['xyz_ihs_sort_by_field']) && $_POST['xyz_ihs_sort_by_field']=='id'){echo 'selected';} else if(get_option('xyz_ihs_sort_field_name')=="id"){echo 'selected';} ?>>Based on create time</option>
								<option value="title" <?php if(isset($_POST['xyz_ihs_sort_by_field']) && $_POST['xyz_ihs_sort_by_field']=='title'){ echo 'selected';}elseif(get_option('xyz_ihs_sort_field_name')=="title"){echo 'selected';} ?>>Based on name</option>
							</select>
							&nbsp;
							<select id="xyz_ihs_sort_by_order" name="xyz_ihs_sort_by_order"  >
								<option value="asc" <?php if(isset($_POST['xyz_ihs_sort_by_order']) && $_POST['xyz_ihs_sort_by_order']=='asc'){ echo 'selected';}elseif(get_option('xyz_ihs_sort_order')=="asc"){echo 'selected';} ?>>Ascending</option>
								<option value="desc" <?php if(isset($_POST['xyz_ihs_sort_by_order']) && $_POST['xyz_ihs_sort_by_order']=='desc'){echo 'selected';} elseif(get_option('xyz_ihs_sort_order')=="desc"){echo 'selected';} ?>>Descending</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<td scope="row" >
							<label for="xyz_ihs_credit">Credit link to author</label>
						</td>
						<td>
							<select name="xyz_ihs_credit" id="xyz_ihs_credit">
								<option value="ihs"
								<?php selected(get_option('xyz_credit_link'),"ihs"); ?>>Enable</option>
								<option value="0"
								<?php if(get_option('xyz_credit_link')!="ihs"){echo 'selected';} ?>>Disable</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<td scope="row" class=" xyz_ihs_settingInput" id="">
							<label for="xyz_ihs_limit">Pagination limit</label>
						</td>
						<td id="">
							<input  name="xyz_ihs_limit" type="text"
							id="xyz_ihs_limit" value="
<?php if(isset($_POST['xyz_ihs_limit']) ){echo abs(intval($_POST['xyz_ihs_limit']));}else{print(get_option('xyz_ihs_limit'));} ?>" />
						</td>
					</tr>
					<tr valign="top">
						<td scope="row">
							<label for="xyz_ihs_pre_ads">Premium Version Ads</label>
						</td>
						<td>
							<select name="xyz_ihs_pre_ads" id="xyz_ihs_pre_ads">
								<option value="0">Disable</option>
								<option value="1" <?php selected(get_option('xyz_ihs_premium_version_ads'),1);?>>Enable</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<td scope="row" class=" xyz_ihs_settingInput" id="xyz_ihs_bottomBorderNone">
						</td>
						<td id="xyz_ihs_bottomBorderNone">
							<input style="margin:10px 0 20px 0;" id="submit" class="button-primary xyz_bottonWidth" type="submit" value=" Update Settings " />
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</form>
</div>
