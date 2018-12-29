<?php
/**
 * Displays the content on the plugin settings page
 */

require_once( dirname( dirname( __FILE__ ) ) . '/bws_menu/class-bws-settings.php' );

if ( ! class_exists( 'Gglstmp_Settings_Tabs' ) ) {
	class Gglstmp_Settings_Tabs extends Bws_Settings_Tabs {
		public $htaccess_options = false,
			$htaccess_active = false,
			$robots, $htaccess, $client, $blog_prefix,
			$manage_info = '';
		/**
		 * Constructor.
		 *
		 * @access public
		 *
		 * @see Bws_Settings_Tabs::__construct() for more information on default arguments.
		 *
		 * @param string $plugin_basename
		 */
		public function __construct( $plugin_basename ) {
			global $gglstmp_options, $gglstmp_plugin_info;

			$tabs = array(
				'settings' 		=> array( 'label' => __( 'Settings', 'google-sitemap-plugin' ) ),
				'display' 		=> array( 'label' => __( 'Structure', 'google-sitemap-plugin' ) ),
				'misc' 			=> array( 'label' => __( 'Misc', 'google-sitemap-plugin' ) ),
				'license'		=> array( 'label' => __( 'License Key', 'google-sitemap-plugin' ) )
			);

			parent::__construct( array(
				'plugin_basename' 	 => $plugin_basename,
				'plugins_info'		 => $gglstmp_plugin_info,
				'prefix' 			 => 'gglstmp',
				'default_options' 	 => gglstmp_get_options_default(),
				'options' 			 => $gglstmp_options,
				'tabs' 				 => $tabs,
				'wp_slug'			 => 'google-sitemap-plugin',
				'pro_page' 			 => 'admin.php?page=google-sitemap-pro.php',
				'bws_license_plugin' => 'google-sitemap-pro/google-sitemap-pro.php',
				'link_key' 			 => '28d4cf0b4ab6f56e703f46f60d34d039',
				'link_pn' 			 => '83'
			) );

			$this->robots = get_option( 'gglstmp_robots' );

			/* Check htaccess plugin */
			if ( $this->is_multisite && ! is_subdomain_install() ) {
				$all_plugins = get_plugins();
				$this->htaccess = gglstmp_plugin_status( array( 'htaccess/htaccess.php', 'htaccess-pro/htaccess-pro.php' ), $all_plugins, false );
				$this->htaccess_options = false;
				if ( 'actived' == $this->htaccess['status'] ) {
					global $htccss_options;
					register_htccss_settings();
					$this->htaccess_options = &$htccss_options;
					$this->htaccess_active = true;
					if ( function_exists( 'htccss_check_xml_access' ) ) {
						$htaccess_check = htccss_check_xml_access();
						if ( $htaccess_check != $this->htaccess_options['allow_xml'] ) {
							$this->htaccess_options['allow_xml'] = $htaccess_check;
							update_site_option( 'htccss_options', $this->htaccess_options );
						}
					}
				}
			}

			if ( function_exists( 'curl_init' ) ) {
				$this->client = gglstmp_client();
				$this->blog_prefix = '_' . get_current_blog_id();
				if ( ! isset( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] ) && isset( $this->options['authorization_code'] ) ) {
					$_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] = $this->options['authorization_code'];
				}
				if ( isset( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] ) ) {
					$this->client->setAccessToken( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] );
				}
			}

			add_filter( get_parent_class( $this ) . '_additional_restore_options', array( $this, 'additional_restore_options' ) );
			add_filter( get_parent_class( $this ) . '_display_custom_messages', array( $this, 'display_custom_messages' ) );
		}

		/**
		 * Save plugin options to the database
		 * @access public
		 * @param  void
		 * @return array    The action results
		 */
		public function save_options() {
			global $wpdb;

			if ( isset( $_POST['gglstmp_logout'] ) ) {
				unset( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ], $this->options['authorization_code'] );
				update_option( 'gglstmp_options', $this->options );
			} elseif ( isset( $_POST['gglstmp_authorize'] ) && ! empty( $_POST['gglstmp_authorization_code'] ) ) {
				try {
					$this->client->authenticate( $_POST['gglstmp_authorization_code'] );
					$this->options['authorization_code'] = $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] = $this->client->getAccessToken();
					update_option( 'gglstmp_options', $this->options );
				} catch ( Exception $e ) {}
			} elseif ( isset( $_POST['gglstmp_menu_add'] ) || isset( $_POST['gglstmp_menu_delete'] ) || isset( $_POST['gglstmp_menu_info'] ) ) {
				if ( $this->client->getAccessToken() ) {
					$webmasters = new Google_Service_Webmasters( $this->client );
					$site_verification  = new Google_Service_SiteVerification( $this->client );
					if ( isset( $_POST['gglstmp_menu_info'] ) ) {
						$this->manage_info .= gglstmp_get_site_info( $webmasters, $site_verification );
					} elseif ( isset( $_POST['gglstmp_menu_add'] ) ) {
						$this->manage_info .= gglstmp_add_site( $webmasters, $site_verification );
					} else {
						$this->manage_info .= gglstmp_delete_site( $webmasters, $site_verification );
					}
				}
			} else {

				if ( $this->htaccess_active && $this->htaccess_options && function_exists( 'htccss_generate_htaccess' ) ) {
					$gglstmp_allow_xml = ( isset( $_POST['gglstmp_allow_xml'] ) && ! empty( $_POST['gglstmp_allow_xml'] ) ) ? 1 : 0;
					if ( $gglstmp_allow_xml != $this->htaccess_options['allow_xml']  ) {
						$this->htaccess_options['allow_xml'] = $gglstmp_allow_xml;
						update_site_option( 'htccss_options', $this->htaccess_options );
						htccss_generate_htaccess();
					}
				}

				$post_types = isset( $_REQUEST['gglstmp_post_types'] ) ? $_REQUEST['gglstmp_post_types'] : array();
				$taxonomies = isset( $_REQUEST['gglstmp_taxonomies'] ) ? $_REQUEST['gglstmp_taxonomies'] : array();
				if ( $this->options['post_type'] != $post_types || $this->options['taxonomy'] != $taxonomies ) {
					$sitemapcreate = true;
				}

				$this->options['post_type'] = $post_types;
				$this->options['taxonomy'] = $taxonomies;

				if ( isset( $_POST['gglstmp_limit'] ) ) {
					if ( $this->options['limit'] != absint( $_POST['gglstmp_limit'] ) ) {
						$sitemapcreate = true;
					}

					$this->options['limit'] = ( absint( $_POST['gglstmp_limit'] ) >= 1000 && absint( $_POST['gglstmp_limit'] ) <= 50000 ) ? absint( $_POST['gglstmp_limit'] ) : 50000;
				}

				if ( ( empty( $this->options['alternate_language'] ) && isset( $_POST['gglstmp_alternate_language'] ) ) || ( ! empty( $this->options['alternate_language'] ) && ! isset( $_POST['gglstmp_alternate_language'] ) ) ) {
						$sitemapcreate = true;
				}

				$this->robots = isset( $_POST['gglstmp_checkbox'] ) ? 1 : 0;
				$this->options['alternate_language'] = isset( $_POST['gglstmp_alternate_language'] ) ? 1 : 0;

				update_option( 'gglstmp_robots', $this->robots );
				update_option( 'gglstmp_options', $this->options );

				if ( isset( $sitemapcreate ) ) {
					gglstmp_schedule_sitemap();
				}

				$message = __( 'Settings saved.', 'google-sitemap-plugin' );
			}

			return compact( 'message', 'notice', 'error' );
		}

		/**
		 *
		 */
		public function tab_settings() { ?>
			<h3 class="bws_tab_label"><?php _e( 'Google Sitemap Settings', 'google-sitemap-plugin' ); ?></h3>
			<?php $this->help_phrase();
			global $wp_version;
			if ( ! $this->all_plugins ) {
				if ( ! function_exists( 'get_plugins' ) )
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
					$this->all_plugins = get_plugins();
			} ?>
			<hr>
			<table class="form-table gglstmp_settings_form">
				<tr>
					<th>Robots.txt</th>
					<td>
						<input type='checkbox' name="gglstmp_checkbox" value="1" <?php checked( $this->robots, 1 ); ?> />
						<span class="bws_info">
							<?php printf(
								_x( "Enable to add a sitemap file path to the %s file.", '%robots.txt file link%', 'google-sitemap-plugin' ),
								'<a href="' . home_url( '/robots.txt' ) . '" target="_blank">robots.txt</a>'
							); ?>
						</span>
						<?php $tooltip_text = sprintf(
							_x( '"Search Engine Visibility" option have to be unmarked on the %s.', '%reading settings page link%', 'google-sitemap-plugin' ),
							sprintf(
								'<a href="%s" target="_blank">%s</a>',
								admin_url( '/options-reading.php#blog_public' ),
								_x( 'Reading Settings page', '...on the reading settings page.', 'google-sitemap-plugin' )
							)
						);
						if ( file_exists( ABSPATH . 'robots.txt' ) ) {
							$tooltip_text .= "<br />" . __( 'Also, please add the following code to the beginning of your ".htaccess" file:', 'google-sitemap-plugin' ) . "<br />" .
								"<pre><code>" .
								"&lt;IfModule mod_rewrite.c&gt;<br />" .
								"RewriteEngine On<br />" .
								"RewriteBase /<br />" .
								"RewriteRule robots\.txt$ index.php?gglstmp_robots=1<br />" .
								"&lt;/IfModule&gt;" .
								"</code></pre>";
						}
						echo bws_add_help_box( $tooltip_text, 'bws-hide-for-mobile bws-auto-width' ); ?>
					</td>
				</tr>
				<?php if ( $this->is_multisite && ! is_subdomain_install() ) {
					$attr_checked = $attr_disabled = '';
					$htaccess_plugin_notice = __( 'This option will be applied to all websites in the network.', 'google-sitemap-plugin' );
					if ( 'deactivated' == $this->htaccess['status'] ) {
						$attr_disabled = 'disabled="disabled"';
						$htaccess_plugin_notice = '<a href="' . network_admin_url( '/plugins.php' ) . '">' . __( 'Activate', 'google-sitemap-plugin' ) . '</a>';
					} elseif ( 'not_installed' == $this->htaccess['status'] ) {
						$attr_disabled = 'disabled="disabled"';
						$htaccess_plugin_notice = '<a href="https://bestwebsoft.com/products/wordpress/plugins/htaccess/?k=bc745b0c9d4b19ba95ae2c861418e0df&pn=106&v=' . $this->plugins_info["Version"] . '&wp_v=' . $wp_version . '">' . __( 'Install Now', 'google-sitemap-plugin' ) . '</a>';
					}
					if ( ! empty( $this->htaccess_options['allow_xml'] ) && empty( $attr_disabled ) ) {
						$attr_checked = 'checked="checked"';
					} ?>
					<tr id="gglstmp_allow_xml_block">
						<th><?php printf( __( '%s Plugin', 'google-sitemap-plugin' ), 'Htaccess' ); ?></th>
						<td>
							<input <?php printf( "%s %s", $attr_checked, $attr_disabled ); ?> type="checkbox" name="gglstmp_allow_xml" value="1" /> <span class="bws_info"><?php printf( __( 'Enable to allow XML files access using %s plugin.', 'google-sitemap-plugin' ), 'Htaccess' ); ?> <?php echo $htaccess_plugin_notice; ?></span>
							<?php echo bws_add_help_box( __( 'The following string will be added to your .htaccess file', 'google-sitemap-plugin' ) . ': <code>RewriteRule ([^/]+\.xml)$ $1 [L]</code>' ); ?>
						</td>
					</tr>
				<?php } ?>
			</table>
			<?php if ( ! $this->hide_pro_tabs ) { ?>
				<div class="bws_pro_version_bloc">
					<div class="bws_pro_version_table_bloc">
						<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php _e( 'Close', 'google-sitemap-plugin' ); ?>"></button>
						<div class="bws_table_bg"></div>
						<table class="form-table bws_pro_version">
							<?php gglstmp_frequency_block(); ?>
						</table>
					</div>
					<?php $this->bws_pro_block_links(); ?>
				</div>
			<?php } ?>
			<table class="form-table gglstmp_settings_form">
				<tr>
					<th><?php _e( 'URLs Limit', 'google-sitemap-plugin' ); ?></th>
					<td>
						<input type="number" name="gglstmp_limit" min="1000" max="50000" value="<?php echo absint( $this->options['limit'] ); ?>" />
						<div class="bws_info">
							<?php _e( "A sitemap file can't contain more than 50,000 URLs and must be no larger than 50 MB uncompressed.", 'google-sitemap-plugin' ); ?>&nbsp;<a href="https://support.google.com/webmasters/answer/183668?ref_topic=4581190#general-guidelines" target="_blank"><?php _e( 'Learn More', 'google-sitemap-plugin' ); ?></a><br />
							<?php _e( 'Decrease the limit if your sitemap exceeds file size limit.', 'google-sitemap-plugin' ); ?>
							<?php _e( 'When the limit is reached, the sitemap will be splitted into multiple files.', 'google-sitemap-plugin' ); ?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Google Webmaster Tools', 'google-sitemap-plugin' ); ?></th>
					<td>
						<?php if ( ! $this->client ) { ?>
							<?php _e( "This hosting does not support сURL, so you cannot add a sitemap file automatically.", 'google-sitemap-plugin' ); ?>
						<?php } else { ?>
							<?php if ( ! isset( $_POST['gglstmp_logout'] ) && $this->client->getAccessToken() ) { ?>
								<input class="button-secondary bws_no_bind_notice" name="gglstmp_logout" type="submit" value="<?php _e( 'Logout from Google Webmaster Tools', 'google-sitemap-plugin' ); ?>" />
							</td>
						</tr>
						<tr>
							<th><?php _e( 'Manage Website with Google Webmaster Tools', 'google-sitemap-plugin' ); ?></th>
							<td>
								<input class="button-secondary bws_no_bind_notice" type='submit' name='gglstmp_menu_add' value="<?php _e( 'Add', 'google-sitemap-plugin' ); ?>" />
								<input class="button-secondary bws_no_bind_notice" type='submit' name='gglstmp_menu_delete' value="<?php _e( 'Delete', 'google-sitemap-plugin' ); ?>" />
								<input class="button-secondary bws_no_bind_notice" type='submit' name='gglstmp_menu_info' value="<?php _e( 'Get Info', 'google-sitemap-plugin' ); ?>" />
								<div class="bws_info">
									<?php _e( "Add, delete or get info about this website using your Google Webmaster Tools account.", 'google-sitemap-plugin' ); ?>
								</div>
								<?php echo $this->manage_info;
							} else {
								$gglstmp_state = mt_rand();
								$this->client->setState( $gglstmp_state );
								$_SESSION[ 'gglstmp_state' . $this->blog_prefix ] = $this->client;
								$gglstmp_auth_url = $this->client->createAuthUrl(); ?>
								<a id="gglstmp_authorization_button" class="button-secondary button" href="<?php echo $gglstmp_auth_url; ?>" target="_blank" onclick="window.open(this.href,'','top='+(screen.height/2-560/2)+',left='+(screen.width/2-640/2)+',width=640,height=560,resizable=0,scrollbars=0,menubar=0,toolbar=0,status=1,location=0').focus(); return false;"><?php _e( 'Get Authorization Code', 'google-sitemap-plugin' ); ?></a>
								<div id="gglstmp_authorization_form">
									<input id="gglstmp_authorization_code" class="bws_no_bind_notice" name="gglstmp_authorization_code" type="text" maxlength="100" autocomplete="off" />
									<input id="gglstmp_authorize" class="button-secondary button bws_no_bind_notice" name="gglstmp_authorize" type="submit" value="<?php _e( 'Authorize', 'google-sitemap-plugin' ); ?>" />
								</div>
								<?php if ( isset( $_POST['gglstmp_authorization_code'] ) && isset( $_POST['gglstmp_authorize'] ) ) { ?>
									<div id="gglstmp_authorize_error"><?php _e( 'Invalid authorization code. Please try again.', 'google-sitemap-plugin' ); ?></div>
								<?php }
							}
						} ?>
						<div class="bws_info">
							<?php _e( 'You can also add your sitemap to Google Webmaster Tools manually.', 'google-sitemap-plugin' ); ?>&nbsp;<a target="_blank" href="https://docs.google.com/document/d/1VOJx_OaasVskCqi9fsAbUmxfsckoagPU5Py97yjha9w/"><?php _e( 'Read the instruction', 'google-sitemap-plugin' ); ?></a>
						</div>
					</td>
				</tr>
				<tr>
						<th><?php _e( 'Alternate Language Pages', 'google-sitemap-plugin' ); ?></th>
						<td>
							<?php $disabled = $link = '';
							if ( array_key_exists( 'multilanguage/multilanguage.php', $this->all_plugins ) || array_key_exists( 'multilanguage-pro/multilanguage-pro.php', $this->all_plugins ) ) {
								if ( ! is_plugin_active( 'multilanguage/multilanguage.php' ) && ! is_plugin_active( 'multilanguage-pro/multilanguage-pro.php' ) ) {
									$disabled = ' disabled="disabled"';
									$link = '<a href="' . admin_url( 'plugins.php' ) . '">' . __( 'Activate', 'google-sitemap-plugin' ) . '</a>';
								}
							} else {
								$disabled = ' disabled="disabled"';
								$link = '<a href="https://bestwebsoft.com/products/wordpress/plugins/multilanguage/?k=84702fda886c65861801c52644d1ee11&amp;pn=83&amp;v=' . $this->plugins_info["Version"] . '&amp;wp_v=' . $wp_version . '" target="_blank">' . __( 'Install Now', 'google-sitemap-plugin' ) . '</a>';
							} ?>
							<input type="checkbox" name="gglstmp_alternate_language" value="1" <?php echo $disabled; checked( $this->options['alternate_language'] ) ?> />
							<span class="bws_info"> <?php _e( "Enable to add alternate language pages using Multilanguage plugin.", 'google-sitemap-plugin' ); ?> <?php echo $link; ?></span>
						</td>
					</tr>
			</table>
		<?php }

		/**
		 *
		 */
		public function tab_display() {
			$post_types = get_post_types( array( 'public' => true ), 'objects' );
			unset( $post_types['attachment'] );

			$taxonomies = array(
				'category' => __( 'Post category', 'google-sitemap-plugin' ),
				'post_tag' => __( 'Post tag', 'google-sitemap-plugin' )
			); ?>
			<h3 class="bws_tab_label"><?php _e( 'Sitemap Structure', 'google-sitemap-plugin' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table gglstmp_settings_form">
				<tr>
					<th><?php _e( 'Post Types', 'google-sitemap-plugin' ); ?></th>
					<td>
						<fieldset>
							<?php foreach ( $post_types as $post_type => $post_type_object ) { ?>
								<label><input type="checkbox" <?php if ( in_array( $post_type, $this->options['post_type'] ) ) echo 'checked="checked"'; ?> name="gglstmp_post_types[]" value="<?php echo $post_type; ?>"/><span style="text-transform: capitalize; padding-left: 5px;"><?php echo $post_type_object->labels->name; ?></span></label><br />
							<?php } ?>
						</fieldset>
						<span class="bws_info"><?php _e( 'Enable to add post type links to the sitemap.', 'google-sitemap-plugin' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Taxonomies', 'google-sitemap-plugin' ); ?></th>
					<td>
						<fieldset>
							<?php foreach ( $taxonomies as $key => $value ) { ?>
								<label><input type="checkbox" <?php if ( in_array( $key, $this->options['taxonomy'] ) ) echo 'checked="checked"'; ?> name="gglstmp_taxonomies[]" value="<?php echo $key; ?>"/><span style="padding-left: 5px;"><?php echo $value; ?></span></label><br />
							<?php } ?>
						</fieldset>
						<span class="bws_info"><?php _e( 'Enable to taxonomy links to the sitemap.', 'google-sitemap-plugin' ); ?></span>
					</td>
				</tr>
			</table>
			<?php if ( ! $this->hide_pro_tabs ) { ?>
				<div class="bws_pro_version_bloc">
					<div class="bws_pro_version_table_bloc">
						<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php _e( 'Close', 'google-sitemap-plugin' ); ?>"></button>
						<div class="bws_table_bg"></div>
						<?php gglstmp_extra_block(); ?>
					</div>
					<?php $this->bws_pro_block_links(); ?>
				</div>
			<?php }
		}

		/**
		 * Custom functions for "Restore plugin options to defaults"
		 * @access public
		 */
		public function additional_restore_options( $default_options ) {

			if ( $this->is_multisite ) {
				$blog_id = get_current_blog_id();
				$mask = "sitemap_{$blog_id}*.xml";
			} else {
				$mask = "sitemap*.xml";
			}
			/* remove all sitemap files */
			array_map( "unlink", glob( ABSPATH . $mask ) );

			/* clear robots.txt */
			$this->robots = 0;
			update_option( 'gglstmp_robots', $this->robots );

			$this->client->revokeToken();
			unset( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ], $this->options['authorization_code'] );

			return $default_options;
		}

		public function display_custom_messages( $save_results ) {

			if ( $this->is_multisite ) {
				$blog_id = get_current_blog_id();
				$xml_file = 'sitemap_' . $blog_id . '.xml';
			} else {
				$xml_file = 'sitemap.xml';
			}
			$xml_url  = home_url( '/' . $xml_file );

			if ( isset( $xml_file ) && file_exists( ABSPATH . $xml_file ) ) {

				printf(
					'<div class="updated bws-notice inline"><p><strong>%s</strong></p></div>',
					sprintf(
						__( "%s is in the site root directory.", 'google-sitemap-plugin' ),
						'<a href="' . $xml_url . '" target="_blank">' . __( 'The Sitemap file', 'google-sitemap-plugin' ) . '</a>'
					)
				);

				if ( ! $this->is_multisite ) {
						$status = gglstmp_check_sitemap( home_url( "/sitemap.xml" ) );
				} else {
						$status = gglstmp_check_sitemap( home_url( "/sitemap_{$blog_id}.xml" ) );
				}

				if ( ! is_wp_error( $status ) && '200' != $status['code'] ) {
					if ( $this->is_multisite ) {
						$home_url = network_home_url();
						$site_url = network_site_url();
					} else {
						$home_url = home_url();
						$site_url = site_url();
					}
					$home_dir = str_replace( $home_url, '', $site_url );
					if ( '' != $home_dir ) {
						$home_dir .= '/';
					}
					$replace = $home_dir . '$1'; ?>
					<div class="error below-h2">
						<p>
							<strong><?php _e( 'Error', 'google-sitemap-plugin' ); ?>:</strong> <?php
							printf( __( "Can't access XML files. Try to add the following rule %s to your %s file which is located in the root of your website to resolve this error. Find the following line %s and paste the code just after it.", 'google-sitemap-plugin' ),
								'<code>RewriteRule ([^/]+\.xml)$ ' . $replace . ' [L]</code>',
								'<strong>.htaccess</strong>',
								'<strong>"RewriteBase /"</strong>'
							); ?>
						</p>
					</div>
				<?php }

			} else {
				gglstmp_schedule_sitemap();
			}

			if ( class_exists( 'Google_Client' ) && version_compare( Google_Client::LIBVER, '1.1.3', '!=' ) ) {
				/* Google Client library of some other product is used! */ ?>
				<div class="updated bws-notice inline">
					<p><strong><?php _e( 'Note', 'google-sitemap-plugin' ); ?>:&nbsp;</strong><?php _e( 'Another plugin is providing Google Client functionality and may interrupt proper plugin work.', 'google-sitemap-plugin' ); ?></p>
				</div>
			<?php }
		}
	}
}