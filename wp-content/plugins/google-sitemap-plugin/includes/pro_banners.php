<?php
/**
 * Banners on plugin settings page
 * @package Google Sitemap by BestWebSoft
 * @since 3.0.3
 */

/**
 * Wrapper. Show ads for PRO on plugin settings page
 * @param     string     $func        function to call
 * @param     boolean    $show_cross  when it is 'false' ad will be displayed regardless of if other blocks are closed
 * @return    void
 */
if ( ! function_exists( 'gglstmp_pro_block' ) ) {
	function gglstmp_pro_block( $func, $show_cross = true ) {
		global $gglstmp_plugin_info, $wp_version, $gglstmp_options;
		if ( ! bws_hide_premium_options_check( $gglstmp_options ) || ! $show_cross ) { ?>
			<div class="bws_pro_version_bloc gglstmp_pro_block <?php echo $func;?>" title="<?php _e( 'This option is available in Pro version of plugin', 'google-sitemap-plugin' ); ?>">
				<div class="bws_pro_version_table_bloc">
					<?php if ( $show_cross ) { ?>
						<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php _e( 'Close', 'google-sitemap-plugin' ); ?>"></button>
					<?php } ?>
					<div class="bws_table_bg"></div>
					<?php call_user_func( $func ); ?>
				</div>
				<div class="bws_pro_version_tooltip">
					<a class="bws_button" href="https://bestwebsoft.com/products/wordpress/plugins/google-sitemap/?k=28d4cf0b4ab6f56e703f46f60d34d039&pn=83&v=<?php echo $gglstmp_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Google Sitemap Pro"><?php _e( 'Upgrade to Pro', 'google-sitemap-plugin' ); ?></a>
				</div>
			</div>
		<?php }
	}
}

/**
 * The content of ad block on the "Settings" tab
 * @param     void
 * @return    void
 */
if ( ! function_exists( 'gglstmp_frequency_block' ) ) {
	function gglstmp_frequency_block() { ?>
		<tr valign="top">
			<th><?php _e( 'Change Frequency', 'google-sitemap-plugin' ); ?></th>
			<td>
				<select disabled="disabled">
					<option><?php _e( 'Monthly', 'google-sitemap-plugin' ); ?></option>
				</select>
				<div class="bws_info"><?php _e( 'This value provides general information to search engines and tell them how frequently the page is likely to change. It may not correlate exactly to how often they crawl the website.', 'google-sitemap-plugin' ); ?>&nbsp;<a href="http://www.sitemaps.org/protocol.html#changefreqdef" target="_blank"><?php _e( 'Learn More', 'google-sitemap-plugin' ); ?></a></div>
			</td>
		</tr>
		<tr>
			<th><?php _e( 'External Sitemap Update Frequency', 'google-sitemap-plugin' ); ?></th>
			<td>
				<input disabled="disabled" class="small-text" type="number" value="7">&ensp;<?php _e( 'day(-s)', 'google-sitemap-plugin' ); ?>
				<div class="bws_info">
					<?php _e( 'This option sets how often the external index sitemap files should be checked for updates.', 'google-sitemap-plugin' ); ?>
				</div>
			</td>
		</tr>
	<?php }
}

/**
 * The content of ad block on the "Extra settings" tab
 * @param     void
 * @return    void
 */
if ( ! function_exists( 'gglstmp_extra_block' ) ) {
	function gglstmp_extra_block() { ?>
		<img style="max-width: 100%;" src="<?php echo plugins_url( 'images/pro_screen_1.png', dirname( __FILE__ ) ); ?>" alt="<?php _e( "Example of site pages' tree", 'google-sitemap-plugin' ); ?>" title="<?php _e( "Example of site pages' tree", 'google-sitemap-plugin' ); ?>" />
	<?php }
}

/**
 * The content of ad block on the "Custom links" tab
 * @param     void
 * @return    void
 */
if ( ! function_exists( 'gglstmp_custom_links_block' ) ) {
	function gglstmp_custom_links_block() {
		$date = date_i18n( get_option( 'date_format' ), 1458086400 ); ?>
		<ul class="subsubsub">
			<li class="all"><a class="current"><?php _e( 'All', 'google-sitemap-plugin' ); ?>&nbsp;<span class="count">(5)</span></a> |</li>
			<li class="enabled"><a><?php _e( 'Enabled', 'google-sitemap-plugin' ); ?>&nbsp;<span class="count">(3)</span></a> |</li>
			<li class="disabled"><a><?php _e( 'Disabled', 'google-sitemap-plugin' ); ?>&nbsp;<span class="count">(2)</span></a></li>
		</ul>
		<p class="search-box">
			<input type="search" disabled="disabled"/>
			<input class="button" value="<?php _e( 'Search', 'google-sitemap-plugin' ); ?>" type="submit" disabled="disabled" />
		</p>
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select disabled="disabled">
					<option value="-1"><?php _e( 'Bulk Actions', 'google-sitemap-plugin' ); ?></option>
				</select>
				<input disabled="disabled" id="doaction" class="button action" value="<?php _e( 'Apply', 'google-sitemap-plugin' ); ?>" type="submit">
			</div>
			<div class="tablenav-pages one-page"><span class="displaying-num">5 <?php _e( 'items', 'google-sitemap-plugin' ); ?></span></div>
		</div>
		<table class="wp-list-table widefat fixed striped links" id="gglstmp_table">
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all" type="checkbox" disabled="disabled" /></td>
					<th scope="col" id="url" class="manage-column column-url column-primary sortable asc"><a>URL</a></th>
					<th scope="col" id="is_sitemap" class="manage-column column-is_sitemap sortable desc"><a>Sitemap</a></th>
					<th scope="col" id="priority" class="manage-column column-priority sortable desc"><a><?php _e( 'Priority', 'google-sitemap-plugin' ); ?></a></th>
					<th scope="col" id="frequency" class="manage-column column-frequency"><?php _e( 'Change Frequency', 'google-sitemap-plugin' ); ?></th>
					<th scope="col" id="date" class="manage-column column-date sortable desc"><a><?php _e( 'Last Changed', 'google-sitemap-plugin' ); ?></a></th>
				</tr>
			</thead>
			<tbody data-wp-lists="list:link">
				<tr style="overflow: visible;">
					<th scope="row" class="check-column"></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">
						<input type="url" style="width: 100%; box-sizing: border-box;" disabled="disabled" />
						<div class="bws_info">
							<strong><?php _e( "Please note", "google-sitemap-plugin" ); ?>:</strong>
							<?php _e( "All URLs listed in the sitemap.xml must use the same protocol (HTTP or HTTPS) and must be located on the same host as the sitemap.xml. For more info see", "google-sitemap-plugin" ); ?>&nbsp;<a href="http://www.sitemaps.org/protocol.html#location" target="_blank"><?php _e( "here", "google-sitemap-plugin" ); ?></a>.
						</div>
					</td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><input type="checkbox" disabled="disabled" value="1"></td>
					<td class="priority column-priority" data-colname="Priority"><input class="small-text" value="100" type="number" disabled="disabled" />&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency">
						<select disabled="disabled" >
							<option value="always"><?php _e( "Always", "google-sitemap-plugin" ); ?></option>
						</select>
					</td>
					<td class="date column-date" data-colname="Last Changed">
						<input class="button button-primary" value="<?php _e( "Save", "google-sitemap-plugin" ); ?>" type="submit" disabled="disabled" />
					</td>
				</tr>
				<tr>
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">
						http://example.com/sitemap.xml
						<div class="row-actions visible">
							<span class="edit"><a><?php _e( 'Edit', 'google-sitemap-plugin' ); ?></a> | </span>
							<span class="disable"><a><?php _e( 'Disable', 'google-sitemap-plugin'  ); ?></a> | </span>
							<span class="delete"><a><?php _e( 'Delete', 'google-sitemap-plugin' ); ?></a></span>
						</div>
					</td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php _e( 'Yes', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php _e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo $date; ?></td>
				</tr>
				<tr>
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">http://example.com/lorem/ipsum/dolor/sit/amet<div class="row-actions">&nbsp;</div></td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php _e( 'No', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php _e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo $date; ?></td>
				</tr>
				<tr class="gglstmp_disabled">
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">http://example.com/donec-fringilla<div class="row-actions">&nbsp;</div></td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php _e( 'No', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php _e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo $date; ?></td>
				</tr>
				<tr class="gglstmp_disabled">
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">http://example.com/lorem-ipsum<div class="row-actions">&nbsp;</div></td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php _e( 'No', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php _e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo $date; ?></td>
				</tr>
				<tr>
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">http://example.com/?s_id=123&amp;p_id=2<div class="row-actions">&nbsp;</div></td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php _e( 'No', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php _e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo $date; ?></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox" disabled="disabled" /></td>
					<th scope="col" class="manage-column column-url column-primary sortable asc"><a>URL</a></th>
					<th scope="col" class="manage-column column-is_sitemap sortable desc"><a>Sitemap</a></th>
					<th scope="col" class="manage-column column-priority sortable desc"><a><?php _e( 'Priority', 'google-sitemap-plugin' ); ?></a></th>
					<th scope="col" class="manage-column column-frequency"><?php _e( 'Change Frequency', 'google-sitemap-plugin' ); ?></th>
					<th scope="col" class="manage-column column-date sortable desc"><a><?php _e( 'Last Changed', 'google-sitemap-plugin' ); ?></a></th>
				</tr>
			</tfoot>
		</table>
	<?php }
}
