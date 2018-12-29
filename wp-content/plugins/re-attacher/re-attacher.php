<?php
/*
Plugin Name: Re-attacher by BestWebSoft
Plugin URI: http://bestwebsoft.com/products/
Description: This plugin allows to attach, unattach or reattach media item in different post.
Author: BestWebSoft
Text Domain: re-attacher
Domain Path: /languages
Version: 1.0.5
Author URI: http://bestwebsoft.com/
License: GPLv3 or later
*/
/*
	© Copyright 2015  BestWebSoft  ( http://support.bestwebsoft.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! function_exists( 'rttchr_admin_menu' ) ) {
	function rttchr_admin_menu() {
		bws_general_menu();
		$settings = add_submenu_page( 'bws_plugins', 'Re-attacher ' . __( 'Settings', 're-attacher' ), 'Re-attacher', 'manage_options', "re-attacher.php", 'rttchr_settings_page' );
		add_action( 'load-' . $settings, 'rttchr_add_tabs' );	
	}
}
/**
 * Internationalization
 */
if ( ! function_exists( 'rttchr_plugins_loaded' ) ) {
	function rttchr_plugins_loaded() {
		load_plugin_textdomain( 're-attacher', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}
/**
* Check if plugin is compatible with current WP version
*/
if ( ! function_exists( 'rttchr_init' ) ) {
	function rttchr_init() {
		global $rttchr_plugin_info;	

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );

		if ( empty( $rttchr_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$rttchr_plugin_info = get_plugin_data( __FILE__ );
		}

		/* Function check if plugin is compatible with current WP version */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $rttchr_plugin_info, '3.8', '3.5' );
	}
}
/**
*  chek settings, check gallery version 
*/
if ( ! function_exists( 'rttchr_admin_init' ) ) {
	function rttchr_admin_init() {
		global $bws_plugin_info, $rttchr_plugin_info;
		/* Add variable for bws_menu */
		if ( ! isset( $bws_plugin_info ) || empty( $bws_plugin_info ) ) {
			$bws_plugin_info = array( 'id' => '182', 'version' => $rttchr_plugin_info['Version'] );
		}		
		/* Function check if plugin gallery version */
		rttchr_gallery_check();
		/* Call register settings function */
		$post_type = '';
		if ( ! empty( $_POST['post_id'] ) ) {
			$post = get_post( $_POST['post_id'] );
			if ( ! empty( $post ) ) {
				$post_type = $post->post_type;
			}
		}
		if ( ( isset( $_GET['page'] ) && "re-attacher.php" == $_GET['page'] ) || ( 'gallery' == $post_type || 'portfolio' == $post_type ) || false !== strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) ) {
			rttchr_settings();			
		}
		/* Call filter for author function */
		rttchr_use_filter_for_uthor();
	}
}
/* 
*	Сhecking Gallery Plugin version
*/
if ( ! function_exists( 'rttchr_gallery_check' ) ) {
	function rttchr_gallery_check() {
		global $rttchr_gallery_old_version;
		/* old version */
		if ( is_plugin_active( 'gallery-plugin/gallery-plugin.php' ) || is_plugin_active( 'gallery-plugin-pro/gallery-plugin-pro.php' ) ) {
			$all_plugins = get_plugins();
			if ( ( isset( $all_plugins['gallery-plugin/gallery-plugin.php']['Version'] ) && $all_plugins['gallery-plugin/gallery-plugin.php']['Version'] < '4.2.7' ) || 
				( isset( $all_plugins['gallery-plugin-pro/gallery-plugin-pro.php']['Version'] ) && $all_plugins['gallery-plugin-pro/gallery-plugin-pro.php']['Version'] < '1.4.3' ) ) {		
				$rttchr_gallery_old_version = __( 'Please update Gallery plugin to make sure it works correctly with Re-attacher plugin.', 're-attacher' );
			}
		}
	}
}
/* 
*	Register settings function
*/
if ( ! function_exists( 'rttchr_settings' ) ) {
	function rttchr_settings() {
		global $rttchr_options, $rttchr_plugin_info;

		$rttchr_option_defaults = array(
			'plugin_option_version' 	=> $rttchr_plugin_info['Version'],
			'media_only_author'			=> '0',
			'display_settings_notice'	=>	1
		);
		/* Install the option defaults */
		if ( ! get_option( 'rttchr_options' ) )
			add_option( 'rttchr_options', $rttchr_option_defaults );

		/* Get options from the database */
		$rttchr_options = get_option( 'rttchr_options' );
		/* Array merge incase this version has added new options */
		if ( ! isset( $rttchr_options['plugin_option_version'] ) || $rttchr_options['plugin_option_version'] != $rttchr_plugin_info['Version'] ) {
			$rttchr_option_defaults['display_settings_notice'] = 0;
			$rttchr_options = array_merge( $rttchr_option_defaults, $rttchr_options );
			$rttchr_options['plugin_option_version'] = $rttchr_plugin_info['Version'];
			update_option( 'rttchr_options', $rttchr_options );
		}		
	}
}
/*
*	Function to filter media files for author 
*
*/
if ( ! function_exists( 'rttchr_use_filter_for_uthor' ) ) {
	function rttchr_use_filter_for_uthor() {	
		global $rttchr_options;
		if ( ! empty( $rttchr_options ) ) {
			if ( 1 == $rttchr_options['media_only_author'] ) {
				add_action( 'pre_get_posts', 'rttchr_author_media_action' );
				add_filter( 'parse_query', 'rttchr_author_media_filter' );
			}
		}	
	}
}
/* 
*	Function is forming page of the settings of this plugin 
*
*/
if ( ! function_exists( 'rttchr_settings_page' ) ) {
	function rttchr_settings_page() { 
		global $wpdb, $rttchr_options, $rttchr_plugin_info;
		$message = '';
		/* Check if you pressed Save Changes */
		if ( isset( $_REQUEST['rttchr_submit'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'rttchr_nonce_name' ) ) {
			$rttchr_options['media_only_author'] = isset( $_POST['rttchr_media_only_author'] ) ? 1 : 0 ;
			update_option( 'rttchr_options', $rttchr_options );
			$message = __( "Settings saved", 're-attacher' );
		} ?>
		<!-- Create a page structure -->
		<div class="wrap">
			<h1>Re-attacher <?php _e( 'Settings', 're-attacher' ); ?></h1>
			<div class="updated fade" <?php if ( ! isset( $_POST['rttchr_submit'] ) ) echo "style='display:none'"; ?>>
				<p><strong><?php echo $message; ?></strong></p>
			</div>
			<?php bws_show_settings_notice(); ?>
			<form method="post" action="admin.php?page=re-attacher.php" class="bws_form">
				<table class="form-table">
					<tr>
						<th><?php _e( 'Show media files author their files only', 're-attacher' ); ?></th>
						<td>		
							<label><input type="checkbox" name="rttchr_media_only_author" value="1" <?php if ( 1 == $rttchr_options['media_only_author'] ) echo "checked='checked' "; ?>/></label>
						</td>
					</tr>
				</table>				
				<p class="submit">
					<input id="bws-submit-button" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 're-attacher' ) ?>" />
					<input type="hidden" name="rttchr_submit" value="submit" />
					<?php wp_nonce_field( plugin_basename( __FILE__ ), 'rttchr_nonce_name' ); ?>
				</p>
			</form>
			<?php bws_plugin_reviews_block( $rttchr_plugin_info['Name'], 're-attacher' ); ?>
		</div>		
	<?php }
}
/*
* Implements a bulk action for unattaching items in bulk.
*/
if ( ! function_exists( 'rttchr_custom_bulk_action' ) ) {
	function rttchr_custom_bulk_action() {
	/* Check which button was pressed */
		/*If the button unattach is pressed in the column */
		if ( isset ( $_GET['action'] ) && isset ( $_GET['rttchr'] ) ) {
			check_admin_referer( 'unattach' );
			global $wpdb;
			if ( ! empty( $_GET['id'] ) && 'unattach' == $_GET['action'] ) {
				$id = intval( $_GET['id'] );
				if ( current_user_can( 'edit_post', $id ) ) {
					$wpdb->update( $wpdb->posts, array( 'post_parent' => 0 ), array( 'id' => $id, 'post_type' => 'attachment' ) );
				}
			}
			/* Returns the user to the page from which the request came */
			if ( $referer = wp_get_referer() ) {
				if ( false !== strpos( $referer, 'post.php' ) ) { /* from metabox in Edit Media*/
					$place = add_query_arg( array( 'message' => '1' ) , $referer );
					wp_redirect( $place );
					exit;
				} elseif ( false !== strpos( $referer, 'upload.php' ) ) {
					$place = remove_query_arg( array( 'page', 'id' ), $referer ); /* clean for further actions*/
					$place = add_query_arg( array( 'message' => '1' ) , $place );
					wp_redirect( $place );
					exit;
				}
			}	
			exit;
		}
		/* If the button is pressed bulk action unattach */
		if ( ! isset( $_REQUEST['detached'] ) ) {
			/* get the action */
			$wp_list_table = _get_list_table( 'WP_Media_List_Table' );
			$action = $wp_list_table->current_action();
			$allowed_actions = array( 'unattach' );
			if ( ! in_array( $action, $allowed_actions ) ) {
				return;
			}
			/* security check */
			check_admin_referer( 'bulk-media' );
			/* make sure ids are submitted. depending on the resource type, this may be 'media' or 'ids'*/
			if ( isset( $_REQUEST['media'] ) ) {
				$post_ids = array_map( 'intval', $_REQUEST['media'] );
			}
			if ( empty( $post_ids ) ) {
				return; 
			}
			/* this is based on wp-admin/edit.php */
			$sendback = remove_query_arg( array( 'unattached', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );
			if ( ! $sendback ) {
				$sendback = admin_url( 'upload.php?post_type=$post_type' );
			}
			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );
			switch ( $action ) {
				case 'unattach':
					global $wpdb;
					if ( ! is_admin() ) {
						wp_die( __( 'You are not allowed to unattach files from this post.', 're-attacher' ) );
					}
					$unattached = 0;
					foreach ( $post_ids as $post_id ) {
						/* Alter post to unattach media file.*/
						if ( $wpdb->update( $wpdb->posts, array( 'post_parent' => 0 ), array( 'id' => intval( $post_id ), 'post_type' => 'attachment' ) ) === false ) {
							wp_die( __( 'Error unattaching files from the post.', 're-attacher' ) );
						}
						$unattached++;
					}
					$sendback = add_query_arg( array( 'unattached' => $unattached, 'ids' => join( ',', $post_ids ) ), $sendback );
					break;
				default: return;
			}
			$sendback = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view' ), $sendback );
			wp_redirect( $sendback );
			exit();
		}
	}
}
/**
* Save change result after click re-attach or attach in our metabox on edit image page
*/
if ( ! function_exists( 'rttchr_load_post' ) ) {	
	function rttchr_load_post (){
		global $wpdb;
		if ( isset ( $_REQUEST['_ajax_nonce'] ) && isset ( $_REQUEST['found_post_id'] ) && isset ( $_REQUEST['post_type'] ) && 'attachment' == $_REQUEST['post_type'] ) {
			wp_verify_nonce( $_REQUEST['_ajax_nonce'], 'find-posts' ); /* generated in find posts div requester*/	
			$parent_id = intval( $_REQUEST['found_post_id'] ); /* found so > 0 */
			$att_id = $_REQUEST['post_ID'];
			if ( $parent_id > 0 && ! current_user_can( 'edit_post', $parent_id ) ) {
				wp_die( __( 'You are not allowed to edit this post.', 're-attacher' ) );
			}
			$attached = $wpdb->update( $wpdb->posts, array( 'post_parent' => $parent_id ),array( 'ID' => intval( $att_id ), 'post_type' => 'attachment' ) );
			clean_attachment_cache( $att_id );
			$_GET['message'] = 1;
		}		
	}
}
/**
 * Enqueue admin scripts.
 * @param $hook string
 */
if ( ! function_exists( 'rttchr_attach_box_scripts_action' ) ) {	
	function rttchr_attach_box_scripts_action( $hook ) {
		global $post;
		/* check if it's a post edit page and not any other admin page */
		if ( $hook == 'post-new.php' || $hook == 'post.php' || $hook == 'bws-plugins_page_re-attacher' ) {
			if ( isset( $_GET['page'] ) && "re-attacher.php" == $_GET['page'] ) {
				wp_enqueue_style( 'rttchr_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
				wp_enqueue_script( 'rttchr_script', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery' ) );
			} else if ( ! empty ( $post ) ) {
				if ( $post->post_type == 'attachment' ) {
					wp_enqueue_style( 'rttchr_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
					wp_enqueue_script( 'wp-ajax-response' );
					wp_enqueue_script( 'media' );
				} elseif ( $post->post_type == 'portfolio' || $post->post_type == 'gallery' ) {
					/* load media library scripts */
					if ( function_exists( 'wp_enqueue_media' ) ) {
						wp_enqueue_media();
					}
					wp_enqueue_style( 'editor-buttons' );
					/* load our css file */
					wp_enqueue_style( 'rttchr_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
					/* load our js file */
					wp_enqueue_script( 'rttchr_script', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery' ) );
					wp_localize_script(
						'rttchr_script',
						'rttchr',
						array(
							'uploaderTitle' 	=> __( 'Select the item that needs to be attached', 're-attacher' ),
							'uploaderButton'	=> __( 'Attach item', 're-attacher' ),
							'nonce' 			=> wp_create_nonce( 'set_post_attach_item_' . $post->post_type )
						)
					);
				}
			}	
		}
	}
}
/**
*	Add notises installed or not active
*/
if ( ! function_exists( 'rttchr_show_notices' ) ) {
	function rttchr_show_notices() { 
		global $hook_suffix, $rttchr_gallery_old_version, $post, $rttchr_plugin_info;	
		$post_type = ( isset( $post ) ) ? $post->post_type == 'gallery' : false;
		if ( isset( $_GET['post_type'] ) && empty( $post ) ) {
			if ( $_GET['post_type'] == "gallery" )
				$post_type = true;
		}
		if ( 'plugins.php' == $hook_suffix || ( isset( $_REQUEST['page'] ) && 'bws_plugins' == $_REQUEST['page'] ) || $post_type ) { 
			if ( '' != $rttchr_gallery_old_version ) { ?>
				<div class="update-nag">
					<strong><?php _e( 'NOTICE', 're-attache' ); ?>: </strong><?php echo $rttchr_gallery_old_version ?>
				</div>
			<?php } ?>
			<noscript>
				<div class="error">
					<p><?php _e( 'If you want Re-attacher plugin to work correctly, please enable JavaScript in your browser!', 're-attacher' ); ?></p>
				</div>
			</noscript>
		<?php }
		if ( 'plugins.php' == $hook_suffix && ! is_network_admin() ) {
			bws_plugin_banner_to_settings( $rttchr_plugin_info, 'rttchr_options', 're-attacher', 'admin.php?page=re-attacher.php' );
		}
	}
}
/*
* Adds new buld actions 'unattach' and 're-attach' to the media lib.
*/
if ( ! function_exists( 'rttchr_custom_bulk_admin_footer' ) ) {
	function rttchr_custom_bulk_admin_footer() {
		global $post_type;
		$admin_page = get_current_screen();
		if ( is_admin() && $admin_page->id == 'upload' ) { ?>
			<script type="text/javascript">
				(function( $ ) {
					$( document ).ready(function() {
						$( '<option>' ).val( 'unattach' ).text( "<?php _e( 'Unattach', 're-attacher' )?>" ).appendTo( "select[name='action']" ).clone().appendTo( "select[name='action2']" );
						$( '<option>' ).val( 'reattach' ).text( "<?php _e( 'Reattach', 're-attacher' )?>" ).appendTo( "select[name='action']" ).clone().appendTo( "select[name='action2']" );
						$( '#doaction, #doaction2' ).click( function(e) {
							$( 'select[name^="action"]' ).each( function() {
								if ( $( this ).val() == 'reattach' ) {
									e.preventDefault();
									findPosts.open();
								}
							});
						});
					});
				})( jQuery );
			</script>
		<?php }
	}
}
/*
*	Show are Columns content
*
*/
if ( ! function_exists( 'rttchr_media_custom_columns' ) ) {
	function rttchr_media_custom_columns( $column_name, $id ) {
		if ( $column_name == 're-attacher' ) {
			global $wpdb;
			$post = get_post( $id );
			$author_id = $post->post_author;
			$user_id = get_current_user_id();
			if ( $post->post_parent > 0 ) {
				if ( get_post( $post->post_parent ) ) {
					$title =_draft_or_post_title( $post->post_parent );
					$url_rttchr = wp_nonce_url( admin_url( 'upload.php?action=unattach&rttchr=true&id=' . $post->ID ), 'unattach' );	
				} ?>
				<strong><a href="<?php echo get_edit_post_link( $post->post_parent ); ?>"><?php echo $title; ?></a></strong> <?php echo get_the_time( 'Y/m/d' ); ?>
				<br />
				<?php if ( current_user_can( 'manage_categories' ) || $author_id == $user_id ) { ?>
					<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','<?php echo $post->ID ?>' );return false;" href="#the-list" title="<?php _e( 'Reattach this media item', 're-attacher' ); ?>"><?php _e( 'Reattach', 're-attacher' ); ?></a>
					<br />
					<a class="hide-if-no-js" href="<?php echo esc_url( $url_rttchr ); ?>" title="<?php echo __( "Unattach this media item", 're-attacher' ); ?>"><?php _e( 'Unattach', 're-attacher' ) ?></a>
				<?php }
			} else { ?>
				(<?php _e( 'Unattached', 're-attacher' ); ?>)<br />
				<?php if ( current_user_can( 'manage_categories' ) || $author_id == $user_id ) { ?>
					<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','<?php echo $post->ID ?>' );return false;" href="#the-list" title="<?php _e( 'Attach this media item', 're-attacher' ); ?>"><?php _e( 'Attach', 're-attacher' ); ?></a>
				<?php }
			}
		}
	}
}
/**
*	Add our metabox on page edit image
*/
if ( ! function_exists( 'rttchr_add_custom_metabox' ) ) {
	function rttchr_add_custom_metabox(){
		add_meta_box( 'rttchr_metabox_in_edit', __( 'Attachment details', 're-attacher' ), 'rttchr_attach_box', 'attachment', 'side', 'low' );
		add_meta_box( 'rttchr_metabox_in_posts', __( 'Already attached', 're-attacher' ), 'rttchr_attach_box_in_post_callback', 'portfolio', 'side', 'low' );
	}
}
/**
*	Add buttons attach in portfolio
*/
if ( ! function_exists( 'rttchr_add_button' ) ) {
	function rttchr_add_button( $context ) {
		global $post;
		if ( isset( $post ) ) {
			if ( $post->post_type == 'portfolio' ) {
				$rttchr_button = "<a class='button' href='#' id='rttchr-attach-media-item'>" . __( 'Attach media item to the portfolio', 're-attacher' ) . "</a>";
				return $context . $rttchr_button;
			}
		}
	}
}
/**
*	Add place for notice in media upoader for portfolio	
*
*	See wp_print_media_templates() in "wp-includes/media-template.php"
*/
if ( ! function_exists( 'rttchr_print_media_templates' ) ) {
	function rttchr_print_media_templates() {
		global $post;
		if ( isset( $post ) ) {
			if ( $post->post_type == 'portfolio' || $post->post_type == 'gallery' ){	
				$image_info = '<# rttchr_portfolio_notice_wiev( data.id ); #><div id="rttchr_portfolio_notice" class="upload-errors"></div>'; ?>
				<script>
					( function ($) {
						$( '#tmpl-attachment-details' ).html(
							$( '#tmpl-attachment-details' ).html().replace( '<div class="attachment-info"', '<?php echo $image_info; ?>$&' )
						);
					} )(jQuery);
				</script>
			<?php }
		}
	}
}
/**
 *	Ajax callback for attaching/detaching alternative thumbnail portfolio
 */
if ( ! function_exists( 'rttchr_attach_box_ajax_action' ) ) {
	function rttchr_attach_box_ajax_action() {
		check_ajax_referer( 'set_post_attach_item_portfolio', 'nonce' );
		$post_ID = ( isset ( $_POST['post_id'] ) ) ? intval( $_POST['post_id'] ) : false;
		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			wp_die( -1 );
		}
		global $wpdb;
		$user_id = get_current_user_id();
		$thumbnail_ids = $_POST['thumbnail_id'];
		/* if pres unattach button change list in DB */
		if ( $thumbnail_ids == '-1' ) {
			$attachment_ID = ( isset ( $_POST['unattachId'] ) ) ? intval( $_POST['unattachId'] ) : false;
			if ( ! isset( $attachment_ID ) && empty( $attachment_ID ) ) {
				wp_die( 0 );
			}
			$atachment_author =	$wpdb->get_var( $wpdb->prepare( "SELECT `post_author` FROM $wpdb->posts WHERE `ID` = %d", $attachment_ID ) );
			if ( current_user_can( 'edit_others_posts' ) || ( $atachment_author == $user_id ) ) {
				$success = $wpdb->update( $wpdb->posts, array( 'post_parent' => 0 ), array( 'id' => $attachment_ID, 'post_type' => 'attachment' ) );
			}
		} else { /* if press attach button - check amt add item and change list in DB */
			if ( is_array( $thumbnail_ids ) ) {
				foreach ( $thumbnail_ids as $thumbnail_id ) {
					$atachment_author =	$wpdb->get_var( $wpdb->prepare( "SELECT `post_author` FROM $wpdb->posts WHERE `ID` = %d", $thumbnail_id ) );
					if ( current_user_can( 'edit_others_posts' ) || ( $atachment_author == $user_id ) ) {
						$wpdb->update( $wpdb->posts, array( 'post_parent' => $post_ID ), array( 'id' => $thumbnail_id, 'post_type' => 'attachment' ) );
					}
				}
				$success = true;
			} else {
				$atachment_author =	$wpdb->get_var( $wpdb->prepare( "SELECT `post_author` FROM $wpdb->posts WHERE `ID` = %d", $thumbnail_ids ) );
				if ( current_user_can( 'edit_others_posts' ) || ( $atachment_author == $user_id ) ) {
					$success = $wpdb->update( $wpdb->posts, array( 'post_parent' => $post_ID ), array( 'id' => $thumbnail_ids, 'post_type' => 'attachment' ) );
				}
			}
		}
		/* return answer on page */
		if ( $success ) { 
			$return = rttchr_metabox_content_in_post( $post_ID, $wpdb );
			wp_send_json_success( $return );
			exit;			
		}	
		wp_die( 0 );
	}
}
/**
*	Ajax callback for prepare to attaching in galery
*/
if ( ! function_exists( 'rttchr_attach_galery_ajax_action' ) ) {
	function rttchr_attach_galery_ajax_action() {
		check_ajax_referer( 'set_post_attach_item_gallery', 'nonce' );
		$post_ID = ( isset ( $_POST['post_id'] ) ) ? intval( $_POST['post_id'] ) : false;
		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			wp_die( -1 );
		}
		global $wpdb, $post;
		$user_id = get_current_user_id();
		$curent_detail = get_post( $post_ID );
		$curent_type = $curent_detail->post_type;
		$return = '';
		$thumbnail_ids = ( isset ( $_POST['thumbnail_id'] ) ) ? $_POST['thumbnail_id'] : false ;
		/* if pres unattach button change list in DB */
		if ( is_array( $thumbnail_ids ) ) {
			$content_width		= 150;
			$thumbnail_html		= '';
			$size				= array( $content_width, $content_width ) ;
			foreach ( $thumbnail_ids as $thumbnail_id ) {
				/* obtain parent*/
				$atachment_detail = get_post( $thumbnail_id );
				$atachment_parent =	$atachment_detail->post_parent;
				$atachment_author =	$atachment_detail->post_author;
				if ( current_user_can( 'edit_others_posts' ) || ( $atachment_author == $user_id ) ) { 
					/* Check whether there is a parent in the image of chosen if there is a warning is displayed */
					if ( isset( $atachment_parent ) && $atachment_parent != 0 && $atachment_parent != $post_ID ) {						
						$parent_detail = get_post( $atachment_parent );
						if ( ! empty( $parent_detail ) ) {
							if ( ( $parent_detail->post_type == 'gallery' || $parent_detail->post_type == 'portfolio' ) && $parent_detail->post_status != 'trash' ) {
								$parent_type = $parent_detail->post_type;
								$parent_title = ( ! empty ( $parent_detail->post_title ) ) ? $parent_detail->post_title : 'no title';
								$notice_attach = "<div class='upload-errors' id='rttchr_notice'><div class='upload-error'><strong>" . __( 'Notice', 're-attacher' ) . ": </strong>" . __( 'this item attached to', 're-attacher' ) . ' ' .$parent_type . ' ' . '&quot;' . $parent_title . '&quot;' . '. ' . __( 'If you attach it to the current ', 're-attacher' ) . ' '. $curent_type . ', ' . __( 'it will disappear from the', 're-attacher' ) . ' ' . $parent_type . ' ' . '&quot;' . $parent_title . '&quot;' . "</div></div>";
							} else
								$notice_attach = '';
						} else
							wp_die( 0 );
					} else
						$notice_attach ='';
					/* draw thumbnail*/ 
					if ( preg_match( '/image/', $atachment_detail->post_mime_type ) ) {
						$img_preview = wp_get_attachment_image( $thumbnail_id, $size, true );
						$meta_data = wp_get_attachment_metadata( $thumbnail_id );
						$atachment_title = $atachment_detail->post_title;
						$return .= "<div class='rttchr-details'><input type='hidden' name='img_attach[]' id='img_attach' value=" . $thumbnail_id . " /><span></span><a class='hide-if-no-js rttchr-noattach-media-item' id='" . $thumbnail_id . "' href='#' title='" . __( 'Don`t attach', 're-attacher' ) . "'>" . __( 'Don`t attach', 're-attacher' ) . "</a>" . $img_preview . "<div>" . $atachment_title . "</br>" . $meta_data['width'] . 'x' . $meta_data['height'] . "</div>" . $notice_attach . "</div>";
					}
				} else { /* If the user does not have sufficient rights to edit */
					if ( preg_match( '/image/', $atachment_detail->post_mime_type ) ) {
						$img_preview = wp_get_attachment_image( $thumbnail_id, $size, true );
						$meta_data = wp_get_attachment_metadata( $thumbnail_id );
						$atachment_detail = get_post( $thumbnail_id );
						$atachment_title = $atachment_detail->post_title;
						$notice_attach = "<div class='upload-errors' id='rttchr_notice'><div class='upload-error'><strong>" . __( 'Warning', 're-attacher' ) . ": </strong>" . __( 'You are not allowed to attach this image', 're-attacher' ) . "</div></div>";
						$return .= "<div class='rttchr-details'>" . $img_preview . "<div>" . $atachment_title . "</br>" . $meta_data['width'] . 'x' . $meta_data['height'] . "</div>" . $notice_attach . "</div>";
					}
				}
			}
			$success = true;
		} else {
			$atachment_detail = get_post( $thumbnail_ids );
			if ( preg_match( '/image/', $atachment_detail->post_mime_type ) ) {
				$img_preview = wp_get_attachment_image( $thumbnail_ids, array( 250, 250 ), true );
				$return .= "<div class='rttchr-details'><a class='hide-if-no-js rttchr-unattach-media-item' id='" . $thumbnail_ids . "' href='#' title='" . __( 'Unattach', 're-attacher' ) . "'>" . __( 'Unattach', 're-attacher' ) . "</a>" . $img_preview . "</div>";
				$success = true;
			}
		}
		/* return answer on page */ 
		if ( $success ) { 
			wp_send_json_success( $return );
			exit;			
		}
		wp_die( 0 );
	}
}
/**
*	Add notises in media upoader for portfolio	and gallery
*/
if ( ! function_exists( 'rttchr_attach_portfolio_ajax_action' ) ) {
	function rttchr_attach_portfolio_ajax_action() {
		check_ajax_referer( 'set_post_attach_item_' . $_POST['post_type'], 'nonce' );
		$post_ID = intval( $_POST['portfolio_post_id'] );
		if ( empty( $post_ID ) ) {
			wp_die( -1 );
		}
		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			wp_die( -1 );
		}
		global $wpdb, $post;
		$curent_detail = get_post( $post_ID );
		$user_id = get_current_user_id();
		$curent_type = $curent_detail->post_type;		
		$thumbnail_id = ( isset( $_POST['portfolio_thumbnail_id'] ) ) ? $_POST['portfolio_thumbnail_id'] : false;
		$notice_attach = "";	
		if ( $thumbnail_id ) {
			/*get information about the selected item */ 
			$atachment_detail = get_post( $thumbnail_id );
			if ( ! isset( $atachment_detail ) || empty( $atachment_detail ) ) {
				wp_die( 0 );
			}
			$atachment_parent = $atachment_detail->post_parent;
			$atachment_author =	$atachment_detail->post_author;
			/*If the user meets the conditions and the highlighted item has a parent displays a warning */ 
			if ( isset ( $atachment_parent ) && ( current_user_can( 'edit_others_posts' ) || $atachment_author == $user_id ) ) {
				if ( $atachment_parent != 0 && $atachment_parent != $post_ID ) {
					$parent_detail = get_post( $atachment_parent );
					if ( ! isset( $parent_detail ) || empty( $parent_detail ) ) {
						wp_die( 0 );
					}
					if ( ( $parent_detail->post_type == 'gallery' || $parent_detail->post_type == 'portfolio' ) && $parent_detail->post_status != 'trash' ) {
						$parent_type = $parent_detail->post_type;
						$parent_title = ( ! empty ( $parent_detail->post_title ) ) ? $parent_detail->post_title : 'no title';
						$notice_attach = "<div class='upload-error'><strong>" . __( 'Notice', 're-attacher' ) . ": </strong>" . __( 'this item attached to', 're-attacher' ) . ' ' . $parent_type . ' ' . '&quot;' . $parent_title . '&quot;' . '. ' . __( 'If you attach it to the current ', 're-attacher' ) . ' '. $curent_type . ', ' . __( 'it will disappear from the', 're-attacher' ) . ' ' . $parent_type . ' ' . '&quot;' . $parent_title . '&quot;' . "</div>";
					} else
						$notice_attach = "";
				}
			} else
				$notice_attach = "<div class='upload-error'><strong>" . __( 'Warning', 're-attacher' ) . ": </strong>" . __( 'You are not allowed to attach this image', 're-attacher' ) . "</div>";

			/* If everything went successfully returns the message */
			if ( ! empty( $atachment_parent ) || $atachment_author != $user_id ) { 
				wp_send_json_success( $notice_attach );			
			}
			wp_die( 0 );
		}
	}
}
/*
*	Save change attach or anattach in galery
*/
if ( ! function_exists( 'rttchr_save_change_gallery' ) ) {
	function rttchr_save_change_gallery( $hook ) {
		global $post, $wpdb;
		if ( ! empty( $post ) ) {
			if ( $post->post_type == 'gallery' ) {
				if ( isset( $_REQUEST['img_unattach'] ) && is_array( $_REQUEST['img_unattach'] ) ) {
					foreach ( $_REQUEST['img_unattach'] as $unattach_id ) {
						$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET `post_parent` = %d WHERE ID = %d", 0, $unattach_id ) );		
					}
				}
				if ( isset( $_REQUEST['img_attach'] ) && is_array( $_REQUEST['img_attach'] ) ) {
					foreach ( $_REQUEST['img_attach'] as $attach_id ) {
						$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET `post_parent` = %d WHERE ID = %d", $post->ID, $attach_id ) );
					}
				}
			}
		}
	}
}
/**
*	filter media library & posts list for authors
*/
if ( ! function_exists( 'rttchr_author_media_action' ) ) {
	function rttchr_author_media_action( $wp_query_obj ) {
		global $current_user, $pagenow;
		if ( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' ) {
			return;
		}
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			$wp_query_obj->set( 'author', $current_user->ID );
		}
		return;
	}
}
/**
 *	Callback function for our metabox on page edit post.
*/
if ( ! function_exists( 'rttchr_attach_box_in_post_callback' ) ) {	
	function rttchr_attach_box_in_post_callback(){
		echo rttchr_metabox_content_in_post();
	}
}
/**
 *	Return content for Callback function for our metabox on page edit post.
*/
if ( ! function_exists( 'rttchr_metabox_content_in_post' ) ) {	
	function rttchr_metabox_content_in_post( $post_ID = '', $ajax_wpdb = '' ) {
		global $wpdb, $post;
		/* check the input parameters */
		$self_wpdb = ( ! empty( $wpdb ) ) ? $wpdb : $ajax_wpdb;
		$post_id = ( empty( $post_ID ) ) ? $post->ID : $post_ID;
		/* create a button call the library */
		$content = ''; 
		$attach = $self_wpdb->get_results( "SELECT `ID` FROM $self_wpdb->posts WHERE `post_parent` = $post_id and `post_type` = 'attachment'" );/* show attachments if there */ 	
		if ( $attach ) {		
			$content_width		= 250;
			$thumbnail_html		= '';
			$size				= count( $attach ) == 1 ? array( $content_width, $content_width ) : array( 50, 50 );
			foreach ( $attach as $attachment ) {
				$img_preview = wp_get_attachment_image( $attachment->ID, $size, true );
				$content .= "<div class='rttchr-details'><a class='hide-if-no-js rttchr-unattach-media-item' id=" . $attachment->ID . " href='#' title=" . __( 'Unattach', 're-attacher' ) . ">" . __( 'Unattach', 're-attacher' ) . "</a>" . $img_preview . "</div>" ;			 
			} 	
		} else 
			$content = __( 'Nothing attached', 're-attacher' );
		return $content;
	}
}
/**
 *	Function for button on page gallery.
*/
if ( ! function_exists( 'rttchr_add_button_in_gallery' ) ) {	
	function rttchr_add_button_in_gallery() {
		echo "<div id='rttchr-gallery-media-buttons' class='hide-if-no-js'><span class='rttchr-button-title'>" . __( 'Choose a media file that will be attached', 're-attacher' ) . ':' . "</span></br><a class='button' href='#' id='rttchr-attach-media-item'>" . __( 'Attach media item to this gallery', 're-attacher' ) . "</a></div><div id='rttchr_preview_media'></div>";
	}
}
/**
*	Add buttons Unattach in gallery
*/
if ( ! function_exists( 'rttchr_add_button_unattach_gallery' ) ) {
	function rttchr_add_button_unattach_gallery( $id ) {
		if ( current_user_can( 'edit_posts' ) ) {
			echo '<div class="rttchr_unattach_link"><a href="javascript:void(0);" onclick="rttchr_img_unattach( ' . $id . ' );">' . __( "Unattach", "re-attacher" ) . '</a><div/>';
		}
	}
}
/**
*	Create our metabox on page edit image
*/
if ( ! function_exists( 'rttchr_attach_box' ) ) {	
	function rttchr_attach_box() {
		global $post;
		if ( ! empty( $post ) ) {
			if ( $post->post_parent != 0 ) {
				$title = _draft_or_post_title( $post->post_parent );
				$parent= get_post( $post->post_parent);
				$parent_type = get_post_type_object( $parent->post_type );
				$url_rttchr = wp_nonce_url( admin_url( 'upload.php?action=unattach&rttchr=true&id=' . $post->ID ), 'unattach' );?> 
				<p>
					<?php _e( 'Attached to', 're-attacher' ) ?>:
					<strong>
						<?php if ( ! empty( $parent_type ) && current_user_can( 'edit_post', $post->post_parent ) && $parent_type->show_ui ) { ?>
							<a href="<?php echo get_edit_post_link( $post->post_parent ); ?>"><?php echo $title ?></a>
						<?php } else {
							echo $title;
						} ?>
					</strong>
				</p>
				<p><?php _e( 'If you want change the attachment, please click on', 're-attacher' ); ?></p>
				<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','<?php echo $post->ID ?>' );return false;" href="#the-list" title="<?php _e( 'Reattach this media item', 're-attacher' ); ?>"><?php _e( 'Reattach', 're-attacher' ); ?></a> 
				<?php _e( 'or', 're-attacher' ) ?> 
				<a class="hide-if-no-js" href="<?php echo esc_url( $url_rttchr ); ?>" title="<?php _e( 'Unattach this media item', 're-attacher' ); ?>"><?php _e( 'Unattach', 're-attacher' ) ?></a> 
			<?php } else { ?>
				( <?php _e( 'Unattached', 're-attacher' ); ?> )<br />
				<a class="hide-if-no-js" onclick="findPosts.open( 'media[]','<?php echo $post->ID ?>' );return false;" href="#the-list" title="<?php _e( 'Attach this media item', 're-attacher' ); ?>"><?php _e( 'Attach', 're-attacher' ); ?></a>
			<?php } ?>
			<div id="ajax-response"></div>
			<?php find_posts_div();
		} 
	}
}
/*
*	Add/Remove Columns
*/
if ( ! function_exists( 'rttchr_custom_pages_columns' ) ) {
	function rttchr_custom_pages_columns( $columns ) {
		/** Add a Attached to Column **/
		$rttchr_custom_pages_columns = array( 're-attacher' => __( 'Attached to', 're-attacher' ) );
		$columns = array_merge( $columns, $rttchr_custom_pages_columns );
		/** Remove a Parent Columns **/
		unset( $columns['parent'] );
		return $columns;
	}
}
/*
*	Sortable in our Columns
*/
if ( ! function_exists( 'rttchr_sortable_column' ) ) {
	function rttchr_sortable_column( $sortable_columns ) {
		$sortable_columns['re-attacher'] = 'parent';
		return $sortable_columns;
	}
}
/**
*	Additional links on the plugin page
*/
if ( ! function_exists( 'rttchr_register_plugin_links' ) ) {
	function rttchr_register_plugin_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			if ( ! is_network_admin() )
				$links[] = '<a href="admin.php?page=re-attacher.php">' . __( 'Settings', 're-attacher' ) . '</a>';
			$links[] = '<a href="http://wordpress.org/plugins/re-attacher/faq/" target="_blank">' . __( 'FAQ', 're-attacher' ) . '</a>';
			$links[] = '<a href="http://support.bestwebsoft.com">' . __( 'Support', 're-attacher' ) . '</a>';
		}
		return $links;
	}
}

if ( ! function_exists( 'rttchr_plugin_action_links' ) ) {
	function rttchr_plugin_action_links( $links, $file ) {
		if ( ! is_network_admin() ) {
			/* Static so we don't call plugin_basename on every plugin row. */
			static $this_plugin;
			if ( ! $this_plugin ) 
				$this_plugin = plugin_basename( __FILE__ );
			if ( $file == $this_plugin ){
				$settings_link = '<a href="admin.php?page=re-attacher.php">' . __( 'Settings', 're-attacher' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}
		return $links;
	}
}
/**
*	restrict authors to only being able to view media that they've uploaded 
*/
if ( ! function_exists( 'rttchr_author_media_filter' ) ) {
	function rttchr_author_media_filter( $wp_query ) {
		/* user author */
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			/* restrict the query to current user */
			global $current_user;
			$wp_query->set( 'author', $current_user->ID );
		}
	}
}	

/* add help tab  */
if ( ! function_exists( 'rttchr_add_tabs' ) ) {
	function rttchr_add_tabs() {
		$screen = get_current_screen();
		$args = array(
			'id' 			=> 'rttchr',
			'section' 		=> '200902305'
		);
		bws_help_tab( $screen, $args );
	}
}

/* 
*	Function for delete options
*/
if ( ! function_exists( 'rttchr_delete_options' ) ) {
	function rttchr_delete_options() {
		global $wpdb;
		/* Delete options */
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$old_blog = $wpdb->blogid;
			/* Get all blog ids */
			$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				delete_option( 'rttchr_options' );
			}
			switch_to_blog( $old_blog );
		} else {
			delete_option( 'rttchr_options' );
		}
	}
}

/* add default options when you activate the plugin */
register_activation_hook( __FILE__, 'rttchr_settings' );
/*	Add our menu end 'hook_page' */
add_action( 'admin_menu', 'rttchr_admin_menu' );
/*	Check if plugin is compatible with current WP version && Add translate && chek or add options */
add_action( 'init', 'rttchr_init' );
add_action( 'admin_init', 'rttchr_admin_init' );
add_action( 'plugins_loaded', 'rttchr_plugins_loaded' );
/*	Implements a bulk action for unattaching items in bulk. */
 add_action( 'load-upload.php', 'rttchr_custom_bulk_action' ); 
/*	Save change result after click in our metabox on edit image page */
add_action( 'load-post.php', 'rttchr_load_post' );
/*	Enqueue admin scripts. */
add_action( 'admin_enqueue_scripts', 'rttchr_attach_box_scripts_action' );
/*	Add notises installed or not active */
add_action( 'admin_notices', 'rttchr_show_notices' );
/*	Adds new buld actions 'unattach' and 're-attach' to the media lib. */
add_action( 'admin_footer', 'rttchr_custom_bulk_admin_footer' ); 
/*	Show are Columns content */
add_action( 'manage_media_custom_column', 'rttchr_media_custom_columns', 0, 2 );
/*	Add our metabox on page edit image end page edit post */
add_action( 'add_meta_boxes', 'rttchr_add_custom_metabox' );
/*	Add buttons attach in post and page */
add_action( 'media_buttons_context', 'rttchr_add_button' ) ;
/*	Add place for notice in media upoader for portfolio	*/
add_action( 'print_media_templates', 'rttchr_print_media_templates', 11 );
/*	Ajax callback for attaching/detaching alternative thumbnail to post */
add_action( 'wp_ajax_add_attachment_item', 'rttchr_attach_box_ajax_action' );
/*	Ajax callback for attaching in galery */
add_action( 'wp_ajax_add_attachment_item_gallery', 'rttchr_attach_galery_ajax_action' ); 
/*	Add notises in media upoader for portfolio	*/
add_action( 'wp_ajax_portfolio_notice', 'rttchr_attach_portfolio_ajax_action' ); 
/* Save change attach or anattach in galery */
add_action( 'save_post', 'rttchr_save_change_gallery' );
/*	Add/Remove Columns */
add_filter( 'manage_upload_columns', 'rttchr_custom_pages_columns' );
/*	Sortable in our Columns */
add_filter( 'manage_upload_sortable_columns', 'rttchr_sortable_column' );
/*	Additional links on the plugin page */
add_filter( 'plugin_action_links', 'rttchr_plugin_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'rttchr_register_plugin_links', 10, 2 );
/*	dell default options when you uninstal the plugin */
register_uninstall_hook( __FILE__, 'rttchr_delete_options' );