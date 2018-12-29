<?php
if ( ! defined( 'ABSPATH' ) )
	exit;
function xyz_ihs_network_install($networkwide) {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				xyz_ihs_install();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	xyz_ihs_install();
}


function xyz_ihs_install(){

	global $wpdb;

	$pluginName = 'xyz-wp-insert-code-snippet/xyz-wp-insert-code-snippet.php';
if (is_plugin_active($pluginName)) {
wp_die( "The plugin Insert HTML Snippet cannot be activated unless the premium version of this plugin is deactivated. Back to <a href='".admin_url()."plugins.php'>Plugin Installation</a>." );
}
	if(get_option('xyz_ihs_sort_order')=='')
	{
		add_option('xyz_ihs_sort_order','desc');
	}
	if(get_option('xyz_ihs_sort_field_name')=='')
	{
		add_option('xyz_ihs_sort_field_name','id');
	}

	$xyz_ihs_installed_date = get_option('xyz_ihs_installed_date');
	if ($xyz_ihs_installed_date=="") {
		$xyz_ihs_installed_date = time();
		update_option('xyz_ihs_installed_date', $xyz_ihs_installed_date);
	}

	if(get_option('xyz_credit_link') == "")
	{
			add_option("xyz_credit_link",0);
	}

	if(get_option('xyz_ihs_dismiss')=='')
	{
		add_option('xyz_ihs_dismiss',0);
	}

	if(get_option('xyz_ihs_premium_version_ads')==""){
    	add_option('xyz_ihs_premium_version_ads',1);
	}

	add_option('xyz_ihs_limit',20);
	$queryInsertHtml = "CREATE TABLE IF NOT EXISTS  ".$wpdb->prefix."xyz_ihs_short_code (
	  `id` int NOT NULL AUTO_INCREMENT,
		  `title` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
		  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
		  `short_code` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
		  `status` int NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
	$wpdb->query($queryInsertHtml);
}

register_activation_hook( XYZ_INSERT_HTML_PLUGIN_FILE ,'xyz_ihs_network_install');
