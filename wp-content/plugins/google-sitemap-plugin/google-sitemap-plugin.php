<?php
/*
Plugin Name: Google Sitemap by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/google-sitemap/
Description: Generate and add XML sitemap to WordPress website. Help search engines index your blog.
Author: BestWebSoft
Text Domain: google-sitemap-plugin
Domain Path: /languages
Version: 3.1.6
Author URI: https://bestwebsoft.com/
License: GPLv2 or later
*/

/*
	© Copyright 2017  BestWebSoft  ( https://support.bestwebsoft.com )

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

if ( ! function_exists( 'gglstmp_admin_menu' ) ) {
	function gglstmp_admin_menu() {
		global $gglstmp_options, $wp_version, $submenu, $gglstmp_plugin_info;

		$settings = add_menu_page(
			__( 'Google Sitemap Settings', 'google-sitemap-plugin' ), /* $page_title */
			'Google Sitemap', /* $menu_title */
			'manage_options', /* $capability */
			'google-sitemap-plugin.php', /* $menu_slug */
			'gglstmp_settings_page', /* $callable_function */
			'none' /* $icon_url */
		);

		add_submenu_page(
			'google-sitemap-plugin.php', /* $parent_slug */
			__( 'Google Sitemap Settings', 'google-sitemap-plugin' ), /* $page_title */
			__( 'Settings', 'google-sitemap-plugin' ), /* $menu_title */
			'manage_options', /* $capability */
			'google-sitemap-plugin.php', /* $menu_slug */
			'gglstmp_settings_page' /* $callable_function */
		);

		if ( ! bws_hide_premium_options_check( $gglstmp_options ) ) {
			add_submenu_page( 'google-sitemap-plugin.php', /* $parent_slug */
				__( 'Custom Links', 'google-sitemap-plugin' ), /* $page_title */
				__( 'Custom Links', 'google-sitemap-plugin' ), /* $menu_title */
				'manage_options', /* $capability */
				'google-sitemap-custom-links.php', /* $menu_slug */
				'gglstmp_settings_page' /* $callable_function */
			);
		}

		add_submenu_page(
			'google-sitemap-plugin.php', /* $parent_slug */
			'BWS Panel', /* $page_title */
			'BWS Panel', /* $menu_title */
			'manage_options', /* $capability */
			'gglstmp-bws-panel', /* $menu_slug */
			'bws_add_menu_render' /* $callable_function */
		);

		if ( isset( $submenu['google-sitemap-plugin.php'] ) ) {
			$submenu['google-sitemap-plugin.php'][] = array(
				'<span style="color:#d86463"> ' . __( 'Upgrade to Pro', 'google-sitemap-plugin' ) . '</span>',
				'manage_options',
				'https://bestwebsoft.com/products/wordpress/plugins/google-sitemap/?k=28d4cf0b4ab6f56e703f46f60d34d039&pn=83&v=' . $gglstmp_plugin_info["Version"] . '&wp_v=' . $wp_version );
		}

		add_action( "load-{$settings}", 'gglstmp_add_tabs' );
	}
}

if ( ! function_exists( 'gglstmp_plugins_loaded' ) ) {
	function gglstmp_plugins_loaded() {
		/* Internationalization */
		load_plugin_textdomain( 'google-sitemap-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/* Function adds language files */
if ( ! function_exists( 'gglstmp_init' ) ) {
	function gglstmp_init() {
		global $gglstmp_plugin_info;

		if ( empty( $gglstmp_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$gglstmp_plugin_info = get_plugin_data( __FILE__ );
		}

		/* add general functions */
		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );

		/* check compatible with current WP version */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $gglstmp_plugin_info, '3.9' );

		/* Get options from the database */
		gglstmp_register_settings();

		if ( 1 == get_option( 'gglstmp_robots' ) ) {
			add_filter( 'robots_txt', 'gglstmp_robots_add_sitemap', 10, 2 );
		}

		if ( isset( $_GET['gglstmp_robots'] ) ) {
			$robots_txt_url = ABSPATH . 'robots.txt';
			/* Get content from real robots.txt file and output its content + custom content */
			if ( file_exists( $robots_txt_url ) ) {
				$robots_content = file_get_contents( $robots_txt_url );
				$robots_content .= "\n";
				$public = get_option( 'blog_public' );
				header( 'Content-Type: text/plain; charset=utf-8' );
				echo apply_filters( 'robots_txt', $robots_content, $public );
				exit;
			}
		}

		if ( is_multisite() ) {
			$blog_id = get_current_blog_id();
			$filename = "sitemap_{$blog_id}.xml";
		} else {
			$filename = "sitemap.xml";
		}

		if ( ! file_exists( ABSPATH . $filename ) ) {
			$sitemaprecreate = true;
		}

		if ( isset( $sitemaprecreate ) ) {
			gglstmp_schedule_sitemap( false, false, true );
		}
	}
}

if ( ! function_exists( 'gglstmp_admin_init' ) ) {
	function gglstmp_admin_init() {
		/* Add variable for bws_menu */
		global $bws_plugin_info, $gglstmp_plugin_info;

		if ( empty( $bws_plugin_info ) ) {
			$bws_plugin_info = array( 'id' => '83', 'version' => $gglstmp_plugin_info["Version"] );
		}

		if ( isset( $_GET['page'] ) && "google-sitemap-plugin.php" == $_GET['page'] ) {
			if ( ! session_id() ) {
				session_start();
			}
		}
	}
}

if ( ! function_exists( 'gglstmp_activate' ) ) {
	function gglstmp_activate() {
		if ( is_multisite() ) {
			switch_to_blog( 1 );
			/* register uninstall function only for the main blog */
			register_uninstall_hook( __FILE__, 'gglstmp_delete_settings' );
			restore_current_blog();
		} else {
			/* register uninstall function */
			register_uninstall_hook( __FILE__, 'gglstmp_delete_settings' );
		}
	}
}

/*============================================ Function for register of the plugin settings on init core ====================*/
if ( ! function_exists( 'gglstmp_register_settings' ) ) {
	function gglstmp_register_settings() {
		global $gglstmp_options, $gglstmp_plugin_info;

		if ( ! get_option( 'gglstmp_options' ) ) {
			$sitemaprecreate = true;
			$options_default = gglstmp_get_options_default();
			add_option( 'gglstmp_options', $options_default );
		}

		$gglstmp_options = get_option( 'gglstmp_options' );

		if ( ! isset( $gglstmp_options['plugin_option_version'] ) || $gglstmp_options['plugin_option_version'] != $gglstmp_plugin_info['Version'] ) {
			$options_default = gglstmp_get_options_default();
			$gglstmp_options = array_merge( $options_default, $gglstmp_options );
			/**
			* Register uninstall hook
			*/
			if ( ! isset( $gglstmp_options['plugin_option_version'] ) || version_compare( str_replace( 'pro-', '', $gglstmp_options['plugin_option_version'] ), '3.1.0', '<' ) ) {
				unset( $gglstmp_options['sitemap'] );
				gglstmp_activate();
			}

			$gglstmp_options['plugin_option_version'] = $gglstmp_plugin_info["Version"];
			/* show pro features */
			$gglstmp_options['hide_premium_options'] = array();
			update_option( 'gglstmp_options', $gglstmp_options );
		}

		if ( isset( $sitemaprecreate ) ) {
			gglstmp_schedule_sitemap();
		}
	}
}

if ( ! function_exists( 'gglstmp_get_options_default' ) ) {
	function gglstmp_get_options_default() {
		global $gglstmp_plugin_info;

		$options_default = array(
			'plugin_option_version' 	=> $gglstmp_plugin_info['Version'],
			'first_install'				=> strtotime( "now" ),
			'display_settings_notice'	=> 1,
			'suggest_feature_banner'	=> 1,
			'post_type'					=> array( 'page', 'post' ),
			'taxonomy'					=> array(),
			'limit'						=> 50000,
			'sitemap_cron_delay'		=> 600, /* delay in seconds to next cron */
			'sitemaps'					=> array(),
			'alternate_language'		=> 0,
		);
		return $options_default;
	}
}

/**
 * @since 3.1.1
 * Update sitemap on permalink structure update.
 * @param	array	$rules	array of existing rules. No modification is needed.
 * @return	array	$rules
 */
if ( ! function_exists( 'gglstmp_rewrite_rules' ) ) {
	function gglstmp_rewrite_rules( $rules ) {
		gglstmp_schedule_sitemap();
		return $rules;
	}
}

/**
 * @since 3.1.0
 * Schedules sitemap preparing task for specified blog.
 * @param	mixed	$blog_id	(int)The blog id the sitemap is created for. Default is false - for current blog.
 * @param	bool	$no_cron	Set if sitemap creation would be executed using cron. Default is false.
 * @return	void
 */
if ( ! function_exists( 'gglstmp_schedule_sitemap' ) ) {
	function gglstmp_schedule_sitemap( $blog_id = false, $no_cron = false, $now = false ) {
		global $gglstmp_options;

		if ( empty( $blog_id ) ) {
			$blog_id = get_current_blog_id();
		}

		if ( $no_cron || ! isset( $gglstmp_options['link_count'] ) || $gglstmp_options['link_count'] < 10000 ) {
			gglstmp_prepare_sitemap( $blog_id );
		} else {
			if ( $now ) {
				wp_schedule_single_event( time(), 'gglstmp_sitemap_cron', array( $blog_id ) );
			} else {
				wp_schedule_single_event( time() + absint( $gglstmp_options['sitemap_cron_delay'] ), 'gglstmp_sitemap_cron', array( $blog_id ) );
			}
		}
	}
}

if ( ! function_exists( 'gglstmp_edited_term' ) ) {
	function gglstmp_edited_term( $term_id, $tt_id, $taxonomy ) {
		if ( isset( $taxonomy ) && 'nav_menu' != $taxonomy ) {
			gglstmp_schedule_sitemap();
		}
	}
}

/**
 * @since 3.1.0
 * Function prepares all the items that should be included into blog's sitemap.
 * After array of items is prepared, it is divided into multiple parts according to the limit value.
 * A single sitemap file will be created if the limit isn't reached,
 * otherwise sitemap file for each part of array of items will be created. Blog index file would be created also.
 * If multisite network is used, network index file will be created also.
 * @param	mixed		$blog_id	(int)The blog id the sitemap is created for. Default is false - for current blog.
 * @return	void
 */
if ( ! function_exists( 'gglstmp_prepare_sitemap' ) ) {
	function gglstmp_prepare_sitemap( $blog_id = false ) {
		global $wpdb, $gglstmp_options;

		$old_blog = $wpdb->blogid;

		$create_index = true;
		$counter = 0;
		$part_num = 1;
		$elements = array();
		$is_multisite = is_multisite();

		if ( $is_multisite && ! empty( $blog_id ) ) {
			switch_to_blog( absint( $blog_id ) );
		} else {
			$blog_id = get_current_blog_id();
		}

		$gglstmp_options = get_option( 'gglstmp_options' );

		$post_types = get_post_types( array( 'public' => true ) );
		/* get all posts */

		foreach ( $post_types as $post_type => $post_type_object ) {
			if ( ! in_array( $post_type, $gglstmp_options['post_type'] ) ) {
				unset( $post_types[ $post_type ] );
			}
		}

		$post_status = apply_filters( 'gglstmp_post_status', array( 'publish' ) );

		$excluded_posts = $wpdb->get_col( "
			SELECT
				`ID`
			FROM $wpdb->posts
			WHERE
				`post_status` IN ('hidden', 'private')
		" );

		if ( ! empty( $excluded_posts ) ) {
			while ( true ) {
				/* exclude bbPress forums and topics */
				$hidden_child_array = $wpdb->get_col(
					"SELECT
						`ID`
					FROM $wpdb->posts
					WHERE
						`post_status` IN ('" . implode( "','", $post_status ) . "')
						AND `ID` NOT IN (" . implode( ',', $excluded_posts ) . ")
						AND `post_type` IN ('forum', 'topic', 'reply')
						AND `post_parent` IN (" . implode( ',', $excluded_posts ) . ");"
				);

				if ( ! empty( $hidden_child_array ) ) {
					$excluded_posts = array_unique( array_merge( $excluded_posts, $hidden_child_array ) );
				} else {
					break 1;
				}
			}
		}

		/* get all taxonomies */
		$taxonomies = array(
			'category' => __( 'Post categories','google-sitemap-plugin' ),
			'post_tag' => __( 'Post tags','google-sitemap-plugin' )
		);

		foreach ( $taxonomies as $key => $taxonomy_name ) {
			if ( ! in_array( $key, $gglstmp_options['taxonomy'] ) ) {
				unset( $taxonomies[ $key ] );
			}
		}

		/* add home page */
		$show_on_front = !! ( 'page' == get_option( 'show_on_front' ) );
		$frontpage_id = get_option( 'page_on_front' );
		$frontpage_is_added = false;

		if ( ! empty( $post_types ) ) {
			$post_status_string = "p.`post_status` IN ('" . implode( "','", (array)$post_status ) . "')";

			$excluded_posts_string = $post_types_string = '';

			$post_types_string = "AND p.`post_type` IN ('" . implode( "','", (array)$post_types ) . "')";

			if ( ! empty( $excluded_posts ) ) {
				$excluded_posts_string = "AND p.`ID` NOT IN (" . implode( ",", $excluded_posts ) . ")";
			}

			$posts = $wpdb->get_results(
				"SELECT
					`ID`,
					`post_author`,
					`post_status`,
					`post_name`,
					`post_parent`,
					`post_type`,
					`post_date`,
					`post_date_gmt`,
					`post_modified`,
					`post_modified_gmt`,
					GROUP_CONCAT(t.`term_id`) as term_id
				FROM `{$wpdb->posts}` p
				LEFT JOIN {$wpdb->term_relationships} tr
					ON p.`ID` = tr.`object_id`
				LEFT JOIN {$wpdb->term_taxonomy} tt
					ON tt.`term_taxonomy_id` = tr.`term_taxonomy_id`
				LEFT JOIN {$wpdb->terms} t
					ON t.`term_id` = tt.`term_id`
				WHERE
					{$post_status_string}
					{$post_types_string}
					{$excluded_posts_string}
				GROUP BY `ID`
				ORDER BY `post_date_gmt` DESC;"
			);

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$priority = 0.8;
					if ( $show_on_front && $frontpage_id == $post->ID ) {
						$priority = 1.0;
						$frontpage_is_added = true;
					}
					$elements[] = array(
						'url'		=> get_permalink( $post ),
						'date'		=> date( 'Y-m-d\TH:i:sP', strtotime( $post->post_modified ) ),
						'frequency'	=> 'monthly',
						'priority'	=> $priority
					);

				}
			}
		}

		if ( ! $frontpage_is_added ) {
			$elements[] = array(
				'url'		=> home_url( '/' ),
				'date'		=> date( 'Y-m-d\TH:i:sP', time() ),
				'frequency'	=> 'monthly',
				'priority'	=> 1.0
			);
		}

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy => $taxonomy_data ) {
				$terms = get_terms( $taxonomy, 'hide_empty=1' );

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term_value ) {
						$modified = $wpdb->get_var( "SELECT `post_modified` FROM $wpdb->posts, $wpdb->term_relationships WHERE `post_status` = 'publish' AND `term_taxonomy_id` = " . $term_value->term_taxonomy_id . " AND $wpdb->posts.ID= $wpdb->term_relationships.object_id ORDER BY `post_modified` DESC" );
						$elements[] = array(
							'url'		=> get_term_link( (int)$term_value->term_id, $taxonomy ),
							'date'		=> date( 'Y-m-d\TH:i:sP', strtotime( $modified ) ),
							'frequency'	=> 'monthly',
							'priority'	=> 0.8
						);
					}
				}
			}
		}

		/* removing existing sitemap and sitemap_index files for current blog */
		$existing_files = gglstmp_get_sitemap_files();
		array_map( "unlink", $existing_files );

		$gglstmp_options['sitemaps'] = array();
		$gglstmp_options['link_count'] = count( $elements );

		if ( count( $elements ) <= $gglstmp_options['limit'] ) {
			$part_num = 0;
			gglstmp_create_sitemap( $elements, $part_num );
			if ( ! $is_multisite ) {
				$create_index = false;
			}
		} else {
			$parts = array_chunk( $elements, $gglstmp_options['limit'] );
			foreach ( $parts as $part_num => $part_elements ) {
				gglstmp_create_sitemap( $part_elements, $part_num + 1 );
			}
		}

		if ( $is_multisite || $create_index ) {

			if ( $is_multisite ) {
				/* removing main index file */
				$existing_files = gglstmp_get_sitemap_files( 0 );
				array_map( "unlink", $existing_files );

				if ( count( $elements ) > $gglstmp_options['limit'] ) {
					/* creating subsite sitemap only when limit is exhausted or external sitemap is added */
					gglstmp_create_sitemap_index();
				}

				/* create main network sitemap index file. Only for subfolder structure, as index sitemap cannot contain sitemaps located on different domain/subdomain */
				gglstmp_create_sitemap_index( 0 );
			} elseif( count( $elements ) > $gglstmp_options['limit'] ) {
				/* external sitemap is added as custom link on a sinle site */
				gglstmp_create_sitemap_index();
			}
		}

		/**
		 * Options update is necessary because 'gglstmp_create_sitemap' and 'gglstmp_create_sitemap_index' functions
		 * have modified $gglstmp_options global variable by calling 'gglstmp_save_sitemap_info' function
		 */
		update_option( 'gglstmp_options', $gglstmp_options );
		if ( $is_multisite ) {
			switch_to_blog( $old_blog );
		}
	}
}

/**
 * @since 3.1.0
 * Function creates xml sitemap file with the provided list of elements.
 * Global variables are used and function mltlngg_get_lang_link() is called from the plugin Multilanguage.
 * Filename is generated in the following way:
 * On a single site:
 * a) $part_num isn't set: "sitemap.xml"
 * b) $part_num is set and equals 2: "sitemap_2.xml".
 * On single subsite of multisite network, $blog_id == 1:
 * a) $part_num isn't set: "sitemap_1.xml"
 * b) $part_num is set and equals 2: "sitemap_1_2.xml".
 * @param	array		$elements		An array of elements to include to the sitemap.
 * @param	int			$part_num		(optional) Indicates the number of the part of elements. It is included to the sitemap filename.
 * @return	void
 */
if ( ! function_exists( 'gglstmp_create_sitemap' ) ) {
	function gglstmp_create_sitemap( $elements, $part_num = 0 ) {
		global $blog_id, $mltlngg_languages, $mltlngg_enabled_languages, $gglstmp_options;

		$xml					= new DomDocument( '1.0', 'utf-8' );
		$home_url				= site_url( '/' );
		$xml_stylesheet_path	= ( defined( 'WP_CONTENT_DIR' ) ) ? $home_url . basename( WP_CONTENT_DIR ) : $home_url . 'wp-content';
		$xml_stylesheet_path	.= ( defined( 'WP_PLUGIN_DIR' ) ) ? '/' . basename( WP_PLUGIN_DIR ) . '/google-sitemap-plugin/sitemap.xsl' : '/plugins/google-sitemap-plugin/sitemap.xsl';
		$xslt = $xml->createProcessingInstruction( 'xml-stylesheet', "type=\"text/xsl\" href=\"$xml_stylesheet_path\"" );
		$xml->appendChild( $xslt );
		$urlset = $xml->appendChild( $xml->createElementNS( 'http://www.sitemaps.org/schemas/sitemap/0.9','urlset' ) );

		/* Used to check compatibility and work with the plugin Multilanguage*/
		$compatibility = false;
		if( ! empty( $gglstmp_options['alternate_language'] ) && count( $mltlngg_enabled_languages ) > 1 ) {
			$compatibility = true;
		}

		/* Create an array with active languages and add a value for hreflang */
		$enabled_languages = array();
		if ( $compatibility ) {
			$urlset->setAttributeNS( 'http://www.w3.org/2000/xmlns/', 'xmlns:xhtml', 'http://www.w3.org/1999/xhtml' );

			foreach ( $mltlngg_enabled_languages as $language ) {
				foreach ( $mltlngg_languages as $item ) {
					if ( $language['name'] == $item[2] ) {
						$language['lang'] = $item[0];
						$enabled_languages[$item[2]] = $language;
					}
				}
			}

			if ( function_exists( 'mltlngg_get_lang_link' ) ) {
				$lang_link = 'mltlngg_get_lang_link';
			}
			$args_links = array();
		}

		foreach ( $elements as $element ) {
			if ( $compatibility ) {
				foreach ( $enabled_languages as $language ) {
					$args_links["lang"] = $language["locale"];
					$args_links["url"] = $element["url"];

					$url = $urlset->appendChild( $xml->createElement( 'url' ) );
					$loc = $url->appendChild( $xml->createElement( 'loc' ) );
					$loc->appendChild( $xml->createTextNode( $lang_link( $args_links ) ) );

					foreach ( $enabled_languages as $language ) {
						$args_links["lang"] = $language["locale"];
						$link = $url->appendChild( $xml->createElement( 'xhtml:link' ) );
						$link->setAttribute( "rel", "alternate" );
						$link->setAttribute( "hreflang", $language['lang'] );
						$link->setAttribute( "href", $lang_link( $args_links ) );
					}

					$lastmod = $url->appendChild( $xml->createElement( 'lastmod' ) );
					$lastmod->appendChild( $xml->createTextNode( $element['date'] ) );
					$changefreq = $url->appendChild( $xml->createElement( 'changefreq' ) );
					$changefreq->appendChild( $xml->createTextNode( $element['frequency'] ) );
					$priority = $url->appendChild( $xml->createElement( 'priority' ) );
					$priority->appendChild( $xml->createTextNode( $element['priority'] ) );
				}
			} else {
				$url = $urlset->appendChild( $xml->createElement( 'url' ) );
				$loc = $url->appendChild( $xml->createElement( 'loc' ) );
				$loc->appendChild( $xml->createTextNode( $element['url'] ) );
				$lastmod = $url->appendChild( $xml->createElement( 'lastmod' ) );
				$lastmod->appendChild( $xml->createTextNode( $element['date'] ) );
				$changefreq = $url->appendChild( $xml->createElement( 'changefreq' ) );
				$changefreq->appendChild( $xml->createTextNode( $element['frequency'] ) );
				$priority = $url->appendChild( $xml->createElement( 'priority' ) );
				$priority->appendChild( $xml->createTextNode( $element['priority'] ) );
			}
		}

		$xml->formatOutput = true;

		if ( ! is_writable( ABSPATH ) ) {
			@chmod( ABSPATH, 0755 );
		}

		$part_num = ( absint( $part_num ) > 0 ) ? '_' . absint( $part_num ) : '';

		if ( is_multisite() ) {
			$filename = 'sitemap_' . absint( $blog_id ) . $part_num . '.xml';
		} else {
			$filename = 'sitemap' . $part_num . '.xml';
		}
		$xml->save( ABSPATH . $filename );
		gglstmp_save_sitemap_info( $filename );
	}
}

/**
 * @since 3.1.0
 * Function creates xml sitemap index file.
 * @param	mixed	$blog_id	(optional) Sets if the index file is created for network (0) or for single subsite (false: current blog id).
 * @return	void
 */
if ( ! function_exists( 'gglstmp_create_sitemap_index' ) ) {
	function gglstmp_create_sitemap_index( $blog_id = false ) {
		global $wpdb;

		/* index sitemap for network supports only subfolder multisite installation */
		if ( 0 === $blog_id && is_multisite() && is_subdomain_install() ) {
			return;
		}

		$blog_id = ( false === $blog_id ) ? get_current_blog_id() : absint( $blog_id );

		if ( ! is_multisite() || 0 === $blog_id ) {
			$index_filename = "sitemap.xml";
		} else {
			$index_filename = "sitemap_{$blog_id}.xml";
		}

		$elements = gglstmp_get_index_elements( $blog_id );

		$index_file      = ABSPATH . $index_filename;

		if ( file_exists( $index_file ) ) {
			unlink( $index_file );
		}

		$xmlindex        = new DomDocument( '1.0', 'utf-8' );
		$site_url = ( 0 === $blog_id ) ? network_site_url( '/' ) : site_url( '/' );

		$xml_stylesheet_path = ( defined( 'WP_CONTENT_DIR' ) ) ? $site_url . basename( WP_CONTENT_DIR ) : $site_url . 'wp-content';
		$xml_stylesheet_path .= ( defined( 'WP_PLUGIN_DIR' ) ) ? '/' . basename( WP_PLUGIN_DIR ) . '/google-sitemap-plugin/sitemap-index.xsl' : '/plugins/google-sitemap-plugin/sitemap-index.xsl';

		$xmlindex->appendChild( $xmlindex->createProcessingInstruction( 'xml-stylesheet', "type=\"text/xsl\" href=\"$xml_stylesheet_path\"" ) );
		$sitemapindex = $xmlindex->appendChild( $xmlindex->createElementNS( 'http://www.sitemaps.org/schemas/sitemap/0.9','sitemapindex' ) );
		foreach ( $elements as $element ) {
			$sitemap = $sitemapindex->appendChild( $xmlindex->createElement( 'sitemap' ) );
			$loc     = $sitemap->appendChild( $xmlindex->createElement( 'loc' ) );
			$loc->appendChild( $xmlindex->createTextNode( $element['loc'] ) );
			$lastmod = $sitemap->appendChild( $xmlindex->createElement( 'lastmod' ) );
			$lastmod->appendChild( $xmlindex->createTextNode( $element['lastmod'] ) );
		}

		if ( count( $elements ) > 0 ) {
			if ( ! is_writable( ABSPATH ) ) {
				@chmod( ABSPATH, 0755 );
			}
			$xmlindex->formatOutput = true;
			$xmlindex->save( $index_file );
			if ( 0 !== $blog_id ) {
				gglstmp_save_sitemap_info( $index_filename, true );
			}
		} elseif ( file_exists( $index_file ) ) {
			unlink( $index_file );
		}
	}
}

/**
 * @since 3.1.0
 * Function gets the elements from the blogs options and returns an array of elements to include to the index sitemap file.
 * @param	mixed		$blog_id			(optional) Sets the range of elements to return. false - current subsite, 0 - network index, (int) - id of the subsite
 * @return	array		$include_index		(optional) Sets if index element should be also included.
 */
if ( ! function_exists( 'gglstmp_get_index_elements' ) ) {
	function gglstmp_get_index_elements( $blog_id = false, $include_index = false ) {
		global $wpdb;
		$index_elements = $external_index_elements = array();
		$is_multisite = is_multisite();
		if ( $is_multisite && 0 === $blog_id ) {
			/* building main network index */
			$old_blog = $wpdb->blogid;
			$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			foreach ( $blogids as $id ) {
				switch_to_blog( $id );
				$blog_options = get_option( 'gglstmp_options' );
				if ( ! empty( $blog_options['sitemaps'] ) && is_array( $blog_options['sitemaps'] ) ) {
					foreach ( $blog_options['sitemaps'] as $sitemap ) {
						if (
							( empty( $sitemap['is_index'] ) || $include_index ) &&
							isset( $sitemap['path'] ) && file_exists( $sitemap['path'] ) &&
							isset( $sitemap['loc'] )
						) {
							$index_elements[ $sitemap['loc'] ] = $sitemap;
						}
					}
				}
			}
			switch_to_blog( $old_blog );
		} else {
			$blog_options = ( ! $is_multisite || empty( $blog_id ) ) ? get_option( 'gglstmp_options' ) : get_blog_option( absint( $blog_id ), 'gglstmp_options' );
			if ( ! empty( $blog_options['sitemaps'] ) && is_array( $blog_options['sitemaps'] ) ) {
				foreach ( $blog_options['sitemaps'] as $sitemap ) {
					if (
						( empty( $sitemap['is_index'] ) || $include_index ) &&
						isset( $sitemap['path'] ) && file_exists( $sitemap['path'] ) &&
						isset( $sitemap['loc'] )
					) {
						$index_elements[ $sitemap['loc'] ] = $sitemap;
					}
				}
			}
		}
		return $index_elements;
	}
}

/**
 * @since 3.1.0
 * Function returns all the corresponding existing sitemap files.
 * @param	mixed		$blog_id		(optional, default: false) "all" || false || (int)blog_id. Specifies the range of xml files to return.
 * 										"all" - all availabe sitemap .xml files.
 * 										false - sitemaps of current blog.
 * 										blog_id - sitemaps of specified blog, 0 - network index file.
 * @return	array		$files			An array of filenames of existing files of the specified type.
 */
if ( ! function_exists( 'gglstmp_get_sitemap_files' ) ) {
	function gglstmp_get_sitemap_files( $blog_id = false ) {
		$files = array();

		if ( is_multisite() ) {
			if ( 'all' != $blog_id ) {
				$blog_id = ( false === $blog_id ) ? get_current_blog_id() : absint( $blog_id );
			}
			if ( 'all' === $blog_id ) {
				/* all existing sitemap files */
				$mask = "sitemap*.xml";
			} elseif ( 0 === $blog_id ) {
				/* main network index */
				$mask = "sitemap.xml";
			} else {
				/* all subsite sitemap files */
				$mask = "sitemap_{$blog_id}*.xml";
			}
		} else {
			$mask = "sitemap*.xml";
		}

		if ( isset( $mask ) )
			$files = glob( ABSPATH . $mask );

		return $files;
	}
}

/**
 * Function checks the availability of the sitemap file by the provided URL.
 * @param	string		$url			The url of the xml sitemap file to check.
 * @return	array		$result			An array with the code and message of the external url check. 200 == $result['code'] if success.
 */
if ( ! function_exists( 'gglstmp_check_sitemap' ) ) {
	function gglstmp_check_sitemap( $url ) {
		$result = wp_remote_get( esc_url_raw( $url ) );
		if ( is_array( $result ) && ! is_wp_error( $result ) ) {
			return $result['response'];
		} else {
			return $result;
		}
	}
}

/**
 * @since 3.1.0
 * Function checks the availability of the sitemap file by the provided URL.
 * @param	string		$filename					The filename to save to the options.
 * @param	string		$is_index					Indicates if the file is an index sitemap.
 * 													false if is regular sitemap
 * 													'index' if is sitemap index
 * @return	void
 */
if ( ! function_exists( 'gglstmp_save_sitemap_info' ) ) {
	function gglstmp_save_sitemap_info( $filename = 'sitemap.xml', $is_index = false ) {
		global $gglstmp_options;
		$xml_url  = home_url( '/' ) . $filename;
		$xml_path = ABSPATH . $filename;
		$is_index = !! $is_index ? 1 : 0 ;

		$sitemap_data = array(
			'is_index'	=> $is_index,
			'file'		=> $filename,
			'path'		=> $xml_path,
			'loc'		=> $xml_url,
			'lastmod'	=> date( 'Y-m-d\TH:i:sP', filemtime( $xml_path ) )
		);

		if ( file_exists( $xml_path ) ) {
			/* save data to blog options */
			$gglstmp_options['sitemaps'][ $filename ] = $sitemap_data;
			update_option( 'gglstmp_options', $gglstmp_options );
		}
	}
}

if ( ! function_exists( 'gglstmp_get_sitemap_info' ) ) {
	function gglstmp_get_sitemap_info( $blog_id = false ) {
		if ( is_multisite() && ! empty( $blog_id ) ) {
			$options = get_blog_option( absint( $blog_id ), 'gglstmp_options' );
		} else {
			$options = get_option( 'gglstmp_options' );
		}

		return ( ! empty( $options['sitemaps'] ) ) ? $options['sitemaps'] : array();
	}
}

if ( ! function_exists ( 'gglstmp_client' ) ) {
	function gglstmp_client() {
		global $gglstmp_plugin_info;

		if ( ! function_exists( 'google_api_php_client_autoload' ) || class_exists( 'Google_Client' ) ) {
			require_once( dirname( __FILE__ ) . '/google_api/autoload.php' );
		}

		$client = new Google_Client();
		$client->setClientId( '37374817621-7ujpfn4ai4q98q4nb0gaaq5ga7j7u0ka.apps.googleusercontent.com' );
		$client->setClientSecret( 'GMefWPZdRIWk3J7USu6_Kf6_' );
		$client->setScopes( array( 'https://www.googleapis.com/auth/webmasters', 'https://www.googleapis.com/auth/siteverification' ) );
		$client->setRedirectUri( 'urn:ietf:wg:oauth:2.0:oob' );
		$client->setAccessType( 'offline' );
		$client->setDeveloperKey( 'AIzaSyBRFiI5TGKKeteDoDa8T8GkJGxRFa1IMxE' );
		$client->setApplicationName( $gglstmp_plugin_info['Name'] );
		return $client;
	}
}

if ( ! function_exists( 'gglstmp_plugin_status' ) ) {
	function gglstmp_plugin_status( $plugins, $all_plugins, $is_network ) {
		$result = array(
			'status'      => '',
			'plugin'      => '',
			'plugin_info' => array(),
		);
		foreach ( (array)$plugins as $plugin ) {
			if ( array_key_exists( $plugin, $all_plugins ) ) {
				if (
					( $is_network && is_plugin_active_for_network( $plugin ) ) ||
					( ! $is_network && is_plugin_active( $plugin ) )
				) {
					$result['status']      = 'actived';
					$result['plugin']      = $plugin;
					$result['plugin_info'] = $all_plugins[$plugin];
					break;
				} else {
					$result['status']      = 'deactivated';
					$result['plugin']      = $plugin;
					$result['plugin_info'] = $all_plugins[$plugin];
				}

			}
		}
		if ( empty( $result['status'] ) ) {
			$result['status'] = 'not_installed';
		}
		return $result;
	}
}

/* Display setting page */
if ( ! function_exists ( 'gglstmp_settings_page' ) ) {
	function gglstmp_settings_page() {
		global $gglstmp_plugin_info, $gglstmp_list_table;
		require_once( dirname( __FILE__ ) . '/includes/pro_banners.php' ); ?>
		<div class="wrap">
			<?php if ( 'google-sitemap-plugin.php' == $_GET['page'] ) { /* Showing settings tab */
				require_once( dirname( __FILE__ ) . '/includes/class-gglstmp-settings.php' );
				$page = new Gglstmp_Settings_Tabs( plugin_basename( __FILE__ ) ); ?>
				<h1>Google Sitemap <?php _e( 'Settings', 'google-sitemap-plugin' ); ?></h1>
				<noscript><div class="error below-h2"><p><strong><?php _e( "Please enable JavaScript in your browser.", 'google-sitemap-plugin' ); ?></strong></p></div></noscript>
				<?php $page->display_content();
			} else { ?>
				<h1>
					<?php _e( 'Custom Links', 'google-sitemap-plugin' ); ?>
					<button disabled="disabled" class="page-title-action add-new-h2" ><?php _e( 'Add New', 'google-sitemap-plugin' ); ?></button>
				</h1>
				<?php gglstmp_pro_block( "gglstmp_custom_links_block", false );
				bws_plugin_reviews_block( $gglstmp_plugin_info['Name'], 'google-sitemap-plugin' );
			} ?>
		</div>
	<?php }
}

if ( ! function_exists( 'gglstmp_robots_add_sitemap' ) ) {
	function gglstmp_robots_add_sitemap( $output, $public ) {
		if ( '0' != $public ) {
			$home_url = get_option( 'home' );
			$filename = ( is_multisite() ) ? "sitemap_" . get_current_blog_id() . ".xml" : "sitemap.xml";
			$line = "Sitemap: " . $home_url . "/" . $filename;
			if ( file_exists( ABSPATH . $filename ) && false === strpos( $output, $line ) ) {
				$output .= "\n" . $line . "\n";
			}
		}
		return $output;
	}
}

/* Function for adding style */
if ( ! function_exists( 'gglstmp_add_plugin_stylesheet' ) ) {
	function gglstmp_add_plugin_stylesheet() {
		wp_enqueue_style( 'gglstmp_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
		if ( isset( $_GET['page'] ) && "google-sitemap-plugin.php" == $_GET['page'] ) {
			bws_enqueue_settings_scripts();
		}
	}
}

/* Function to get info about site from Google Webmaster Tools */
if ( ! function_exists( 'gglstmp_get_site_info' ) ) {
	function gglstmp_get_site_info( $webmasters, $site_verification ) {
		global $gglstmp_options;

		$instruction_url = 'https://docs.google.com/document/d/1VOJx_OaasVskCqi9fsAbUmxfsckoagPU5Py97yjha9w/';
		$home_url = home_url( '/' );
		$wmt_sites_array = $wmt_sitemaps_arr = array();

		$return = '<table id="gglstmp_manage_table"><tr><th>' . __( 'Website', 'google-sitemap-plugin' ) . '</th>
					<td><a href="' . $home_url . '" target="_blank">' . $home_url . '</a></td></tr>';

		try {
			$wmt_sites = $webmasters->sites->listSites()->getSiteEntry();

			foreach ( $wmt_sites as $site ) {
				$wmt_sites_array[ $site->siteUrl ] = $site->permissionLevel;
			}

			if ( ! array_key_exists( $home_url, $wmt_sites_array ) ) {
				$return .= '<tr><th>' . __( 'Status', 'google-sitemap-plugin' ) . '</th>
					<td>' . __( 'Not added', 'google-sitemap-plugin' ) . '</td></tr>';
			} else {

				$return .= '<tr><th>' . __( 'Status', 'google-sitemap-plugin' ) . '</th>
					<td class="gglstmp_success">' . __( 'Added', 'google-sitemap-plugin' ) . '</td></tr>';

				$return .= '<tr><th>' . __( 'Verification Status', 'google-sitemap-plugin' ) . '</th>';
				if ( 'siteOwner' == $wmt_sites_array[ $home_url ] ) {
					$return .= '<td>' . __( 'Verified', 'google-sitemap-plugin' ) . '</td></tr>';
				} else {
					$return .= '<td>' . __( 'Not verified', 'google-sitemap-plugin' ) . '</td></tr>';
				}

				$webmasters_sitemaps = $webmasters->sitemaps->listSitemaps( $home_url )->getSitemap();

				foreach ( $webmasters_sitemaps as $sitemap ) {
					$wmt_sitemaps_arr[ $sitemap->path ] = ( $sitemap->errors > 0 || $sitemap->warnings > 0 ) ? true : false;
				}

				$return .= '<tr><th>' . __( 'Sitemap Status', 'google-sitemap-plugin' ) . '</th>';

				if ( is_multisite() ) {
					$blog_id = get_current_blog_id();
					$xml_file =  'sitemap_' . $blog_id . '.xml';
					$url_sitemap = home_url( '/' ) . $xml_file;
				} else {
					$xml_file = 'sitemap.xml';
					$url_sitemap  = home_url( '/' ) . $xml_file;
				}

				if ( ! empty( $url_sitemap ) ) {
					if ( ! array_key_exists( $url_sitemap, $wmt_sitemaps_arr ) ) {
						$return .= '<td>' . __( 'Not added', 'google-sitemap-plugin' ) . '</td></tr>';
					} else {
						if ( ! $wmt_sitemaps_arr[ $url_sitemap ] ) {
							$return .= '<td class="gglstmp_success">' . __( 'Added', 'google-sitemap-plugin' ) . '</td></tr>';
						} else {
							$return .= '<td>' . __( 'Added with errors.', 'google-sitemap-plugin' ) . '<a href="https://www.google.com/webmasters/tools/sitemap-details?hl=en&siteUrl=' . urlencode( $home_url ) . '&sitemapUrl=' . urlencode( $url_sitemap ) . '#ISSUE_FILTER=-1">' . __( 'View errors in Google Webmaster Tools', 'google-sitemap-plugin' ) . '</a></td></tr>';
						}
					}
					$return .= '<tr><th>' . __( 'Sitemap URL', 'google-sitemap-plugin' ) . '</th>
						<td><a href="' . $url_sitemap . '" target="_blank">' . $url_sitemap . '</a></td></tr>';
				} else {
					$return .= '<td><strong>' . __( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . __( 'Please check the sitemap file manually.', 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . __( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
				}
			}
		} catch ( Google_Service_Exception $e ) {
			$error = $e->getErrors();
			$sv_error = isset( $error[0]['message'] ) ? $error[0]['message'] : __( 'Unexpected error', 'google-sitemap-plugin' );
		} catch ( Google_IO_Exception $e ) {
			$sv_error = $e->getMessage();
		} catch ( Google_Auth_Exception $e ) {
			$sv_error = true;
		} catch ( Exception $e ) {
			$sv_error = $e->getMessage();
		}

		if ( ! empty( $sv_error ) ) {
			if ( true !== $sv_error ) {
				$return .= '<tr><th></th><td><strong>' . __( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $sv_error . '</td></tr>';
			}
			$return .= '<tr><th></th><td>' . __( "Manual verification required.", 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . __( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
		}
		$return .= '</table>';
		return $return;
	}
}

/* Deleting site from Google Webmaster Tools */
if ( ! function_exists( 'gglstmp_delete_site' ) ) {
	function gglstmp_delete_site( $webmasters, $site_verification ) {
		global $gglstmp_options;

		$home_url = home_url( '/' );
		$return = '<table id="gglstmp_manage_table"><tr><th>' . __( 'Website', 'google-sitemap-plugin' ) . '</th>
					<td><a href="' . $home_url . '" target="_blank">' . $home_url . '</a></td></tr>';

		try {
			$webmasters_sitemaps = $webmasters->sitemaps->listSitemaps( $home_url )->getSitemap();
			foreach ( $webmasters_sitemaps as $sitemap ) {
				try {
					$webmasters->sitemaps->delete( $home_url, $sitemap->path );
				} catch ( Google_Service_Exception $e ) {
				} catch ( Google_IO_Exception $e ) {
				} catch ( Google_Auth_Exception $e ) {
				} catch ( Exception $e ) {}
			}

			$webmasters->sites->delete( $home_url );

			$return .= '<tr><th>' . __( 'Status', 'google-sitemap-plugin' ) . '</th>
					<td>' . __( 'Deleted', 'google-sitemap-plugin' ) . '</td></tr>';
			unset( $gglstmp_options['site_vererification_code'] );
			update_option( 'gglstmp_options', $gglstmp_options );

		} catch ( Google_Service_Exception $e ) {
			$error = $e->getErrors();
			$sv_error = isset( $error[0]['message'] ) ? $error[0]['message'] : __( 'Unexpected error', 'google-sitemap-plugin' );
		} catch ( Google_IO_Exception $e ) {
			$sv_error = $e->getMessage();
		} catch ( Google_Auth_Exception $e ) {
			$sv_error = true;
		} catch ( Exception $e ) {
			$sv_error = $e->getMessage();
		}
		if ( ! empty( $sv_error ) ) {
			$return .= '<tr><th>' . __( 'Status', 'google-sitemap-plugin' ) . '</th>
				<td>' . __( 'Not added', 'google-sitemap-plugin' ) . '</td></tr>';
			if ( true !== $sv_error ) {
				$return .= '<tr><th></th><td><strong>' . __( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $sv_error . '</td></tr>';
			}
		}
		$return .= '</table>';
		return $return;
	}
}

/* Adding and verifing site, adding sitemap file to Google Webmaster Tools */
if ( ! function_exists( 'gglstmp_add_site' ) ) {
	function gglstmp_add_site( $webmasters, $site_verification ) {
		global $gglstmp_options;

		$instruction_url = 'https://docs.google.com/document/d/1VOJx_OaasVskCqi9fsAbUmxfsckoagPU5Py97yjha9w/';
		$home_url = home_url( '/' );

		$return = '<table id="gglstmp_manage_table"><tr><th>' . __( 'Website', 'google-sitemap-plugin' ) . '</th>
					<td><a href="' . $home_url . '" target="_blank">' . $home_url . '</a></td></tr>';
		try {
			$webmasters->sites->add( $home_url );
			$return .= '<tr><th>' . __( 'Status', 'google-sitemap-plugin' ) . '</th>
					<td class="gglstmp_success">' . __( 'Added', 'google-sitemap-plugin' ) . '</td></tr>';
		} catch ( Google_Service_Exception $e ) {
			$error = $e->getErrors();
			$wmt_error = isset( $error[0]['message'] ) ? $error[0]['message'] : __( 'Unexpected error', 'google-sitemap-plugin' );
		} catch ( Google_IO_Exception $e ) {
			$wmt_error = $e->getMessage();
		} catch ( Google_Auth_Exception $e ) {
			$wmt_error = true;
		} catch ( Exception $e ) {
			$wmt_error = $e->getMessage();
		}

		if ( ! empty( $wmt_error ) ) {
			$return .= '<tr><th>' . __( 'Status', 'google-sitemap-plugin' ) . '</th>';
			if ( true !== $wmt_error ) {
				$return .= '<td><strong>' . __( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $wmt_error . '</td></tr>
				<tr><th></th>';
			}
			$return .= '<td>' . __( "Manual verification required.", 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . __( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
		} else {

			try {
				$gglstmp_sv_get_token_request_site = new Google_Service_SiteVerification_SiteVerificationWebResourceGettokenRequestSite;
				$gglstmp_sv_get_token_request_site->setIdentifier( $home_url );
				$gglstmp_sv_get_token_request_site->setType( 'SITE' );
				$gglstmp_sv_get_token_request = new Google_Service_SiteVerification_SiteVerificationWebResourceGettokenRequest;
				$gglstmp_sv_get_token_request->setSite( $gglstmp_sv_get_token_request_site );
				$gglstmp_sv_get_token_request->setVerificationMethod( 'META' );
				$getToken = $site_verification->webResource->getToken( $gglstmp_sv_get_token_request );
				$gglstmp_options['site_vererification_code'] = htmlspecialchars( $getToken['token'] );
				if ( preg_match( '|^&lt;meta name=&quot;google-site-verification&quot; content=&quot;(.*)&quot; /&gt;$|', $gglstmp_options['site_vererification_code'] ) ) {
					update_option( 'gglstmp_options', $gglstmp_options );

					$return .= '<tr><th>' . __( 'Verification Code', 'google-sitemap-plugin' ) . '</th>
						<td>' . __( 'Received and added to the site', 'google-sitemap-plugin' ) . '</td></tr>';
				} else {
					$return .= '<tr><th>' . __( 'Verification Code', 'google-sitemap-plugin' ) . '</th>
						<td>' . __( 'Received, but has not been added to the site', 'google-sitemap-plugin' ) . '</td></tr>';
				}
			} catch ( Google_Service_Exception $e ) {
				$error = $e->getErrors();
				$sv_error = isset( $error[0]['message'] ) ? $error[0]['message'] : __( 'Unexpected error', 'google-sitemap-plugin' );
			} catch ( Google_IO_Exception $e ) {
				$sv_error = $e->getMessage();
			} catch ( Google_Auth_Exception $e ) {
				$sv_error = true;
			} catch ( Exception $e ) {
				$sv_error = $e->getMessage();
			}

			if ( ! empty( $sv_error ) ) {
				if ( true !== $sv_error ) {
					$return .= '<tr><th>' . __( 'Verification Code', 'google-sitemap-plugin' ) . '</th>
						<td><strong>' . __( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $sv_error . '</td></tr>';
				}

				$return .= '<tr><th>' . __( 'Verification Status', 'google-sitemap-plugin' ) . '</th>
					<td>' . ___( "The site couldn't be verified. Manual verification required.", 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . __( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
			} else {

				try {
					$gglstmp_wmt_resource_site = new Google_Service_SiteVerification_SiteVerificationWebResourceResourceSite;
					$gglstmp_wmt_resource_site->setIdentifier( $home_url );
					$gglstmp_wmt_resource_site->setType( 'SITE' );
					$gglstmp_wmt_resource = new Google_Service_SiteVerification_SiteVerificationWebResourceResource;
					$gglstmp_wmt_resource->setSite( $gglstmp_wmt_resource_site );
					$site_verification->webResource->insert( 'META', $gglstmp_wmt_resource );

					$return .= '<tr><th>' . __( 'Verification Status', 'google-sitemap-plugin' ) . '</th>
						<td class="gglstmp_success">' . __( 'Verified', 'google-sitemap-plugin' ) . '</td></tr>';
				} catch ( Google_Service_Exception $e ) {
					$error = $e->getErrors();
					$sv_error = isset( $error[0]['message'] ) ? $error[0]['message'] : __( 'Unexpected error', 'google-sitemap-plugin' );
				} catch ( Google_IO_Exception $e ) {
					$sv_error = $e->getMessage();
				} catch ( Google_Auth_Exception $e ) {
					$sv_error = true;
				} catch ( Exception $e ) {
					$sv_error = $e->getMessage();
				}

				if ( ! empty( $sv_error ) ) {
					$return .= '<tr><th>' . __( 'Verification Status', 'google-sitemap-plugin' ) . '</th>';
					if ( true !== $sv_error ) {
						$return .= '<td><strong>' . __( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $sv_error . '</td></tr>
							<tr><th></th>';
					}
					$return .= '<td>' . __( "Manual verification required.", 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . __( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
				} else {

					$return .= '<tr><th>' . __( 'Sitemap Status', 'google-sitemap-plugin' ) . '</th>';

					$is_multisite = is_multisite();

					if ( $is_multisite ) {
						$blog_id = get_current_blog_id();
						$sitemap_filename = "sitemap_{$blog_id}.xml";
					} else {
						$sitemap_filename = 'sitemap.xml';
					}

					if (
						! empty( $gglstmp_options['sitemaps'][ $sitemap_filename ]['loc'] ) &&
						! empty( $gglstmp_options['sitemaps'][ $sitemap_filename ]['path'] ) &&
						file_exists( $gglstmp_options['sitemaps'][ $sitemap_filename ]['path'] )
					) {
						$sitemap_url = $gglstmp_options['sitemaps'][ $sitemap_filename ]['loc'];
						$check_result = gglstmp_check_sitemap( $sitemap_url );
						if ( ! is_wp_error( $check_result ) && 200 == $check_result['code'] ) {
							try {
								$webmasters->sitemaps->submit( $home_url, $sitemap_url );
								$return .= '<td class="gglstmp_success">' . __( 'Added', 'google-sitemap-plugin' ) . '</td></tr>';
							} catch ( Google_Service_Exception $e ) {
								$error = $e->getErrors();
								$wmt_error = isset( $error[0]['message'] ) ? $error[0]['message'] : __( 'Unexpected error', 'google-sitemap-plugin' );
							} catch ( Google_IO_Exception $e ) {
								$wmt_error = $e->getMessage();
							} catch ( Google_Auth_Exception $e ) {
								$wmt_error = true;
							} catch ( Exception $e ) {
								$wmt_error = $e->getMessage();
							}
							if ( ! empty( $wmt_error ) ) {
								if ( true !== $wmt_error ) {
									$return .= '<td><strong>' . __( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $wmt_error . '</td></tr>
										<tr><th></th>';
								}
								$return .= '<td>' . __( "Please add the sitemap file manually.", 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . __( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
							}
						} else {
							$return .= sprintf(
								'<td><strong>%s:</strong> %s</td></tr>',
								__( 'Error 404', 'google-sitemap-plugin' ),
								sprintf(
									__( 'The sitemap file %s not found.', 'google-sitemap-plugin' ),
									sprintf(
										'(<a href="%s">%s</a>)',
										$gglstmp_options['sitemaps'][ $sitemap_filename ]['loc'],
										$sitemap_filename
									)
								)
							);
						}
					} else {
						$return .= '<td><strong>' . __( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . __( 'The sitemap file not found.', 'google-sitemap-plugin' ) . '</td></tr>';
					}
				}
			}
		}

		$return .= '</table>';
		return $return;
	}
}

/* Add verification code to the site head */
if ( ! function_exists( 'gglstmp_add_verification_code' ) ) {
	function gglstmp_add_verification_code() {
		global $gglstmp_options;

		if ( isset( $gglstmp_options['site_vererification_code'] ) ) {
			echo htmlspecialchars_decode( $gglstmp_options['site_vererification_code'] );
		}
	}
}

/* Check post status before Updating */
if ( ! function_exists( 'gglstmp_check_post_status' ) ) {
	function gglstmp_check_post_status( $new_status, $old_status, $post ) {
		if ( ! wp_is_post_revision( $post->ID ) ) {
			global $gglstmp_update_sitemap;
			if ( in_array( $new_status, array( 'publish', 'trash', 'future' ) ) ) {
				$gglstmp_update_sitemap = true;
			} elseif (
				in_array( $old_status, array( 'publish', 'future' ) ) &&
				in_array( $new_status, array( 'auto-draft', 'draft', 'private', 'pending' ) )
			) {
				$gglstmp_update_sitemap = true;
			}
		}
	}
}

/* Updating the sitemap after a post or page is trashed or published */
if ( ! function_exists( 'gglstmp_update_sitemap' ) ) {
	function gglstmp_update_sitemap( $post_id, $post = null ) {
		if ( ! wp_is_post_revision( $post_id ) && ( ! isset( $post ) || ( 'nav_menu' != $post->post_type && 'nav_menu_item' != $post->post_type ) ) ) {
			global $gglstmp_update_sitemap;
			if ( true === $gglstmp_update_sitemap ) {
				gglstmp_register_settings();
				gglstmp_schedule_sitemap();
			}
		}
	}
}

/* Adding setting link in activate plugin page */
if ( ! function_exists( 'gglstmp_action_links' ) ) {
	function gglstmp_action_links( $links, $file ) {
		/* Static so we don't call plugin_basename on every plugin row. */
		if ( ! is_network_admin() && ! is_plugin_active( 'google-sitemap-pro/google-sitemap-pro.php' ) ) {
			static $this_plugin;
			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}
			if ( $file == $this_plugin ) {
				$settings_link = '<a href="admin.php?page=google-sitemap-plugin.php">' . __( 'Settings', 'google-sitemap-plugin' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}
		return $links;
	}
}

if ( ! function_exists( 'gglstmp_links' ) ) {
	function gglstmp_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			if ( ! is_network_admin() && ! is_plugin_active( 'google-sitemap-pro/google-sitemap-pro.php' ) ) {
				$links[] = '<a href="admin.php?page=google-sitemap-plugin.php">' . __( 'Settings', 'google-sitemap-plugin' ) . '</a>';
			}
			$links[] = '<a href="https://support.bestwebsoft.com/hc/en-us/sections/200538869" target="_blank">' . __( 'FAQ', 'google-sitemap-plugin' ) . '</a>';
			$links[] = '<a href="https://support.bestwebsoft.com">' . __( 'Support', 'google-sitemap-plugin' ) . '</a>';
		}
		return $links;
	}
}

if ( ! function_exists ( 'gglstmp_plugin_banner' ) ) {
	function gglstmp_plugin_banner() {
		global $hook_suffix, $gglstmp_plugin_info, $gglstmp_options;

		if ( 'plugins.php' == $hook_suffix ) {
			if ( ! $gglstmp_options ) {
				gglstmp_register_settings();
			}

			if ( isset( $gglstmp_options['first_install'] ) && strtotime( '-1 week' ) > $gglstmp_options['first_install'] ) {
				bws_plugin_banner( $gglstmp_plugin_info, 'gglstmp', 'google-sitemap', '8fbb5d23fd00bdcb213d6c0985d16ec5', '83', '//ps.w.org/google-sitemap-plugin/assets/icon-128x128.png' );
			}

			bws_plugin_banner_to_settings( $gglstmp_plugin_info, 'gglstmp_options', 'google-sitemap-plugin', 'admin.php?page=google-sitemap-plugin.php' );
		}

		if ( isset( $_REQUEST['page'] ) && 'google-sitemap-plugin.php' == $_REQUEST['page'] ) {
			bws_plugin_suggest_feature_banner( $gglstmp_plugin_info, 'gglstmp_options', 'google-sitemap-plugin' );
		}
	}
}

/* add help tab  */
if ( ! function_exists( 'gglstmp_add_tabs' ) ) {
	function gglstmp_add_tabs() {
		$screen = get_current_screen();
		$args = array(
			'id' 			=> 'gglstmp',
			'section' 		=> '200538869'
		);
		bws_help_tab( $screen, $args );
	}
}

/**
 * Fires when the new blog has been added or during the blog activation, marking as not spam or as not archived.
 * @since   1.2.9
 * @param   int   $blog_id     Blog ID
 * @return  void
 */
if ( ! function_exists( 'gglstmp_add_sitemap' ) ) {
	function gglstmp_add_sitemap( $blog_id ) {
		global $wpdb;

		/* don`t have to check blog status for new blog */
		if ( 'wpmu_new_blog' != current_filter() ) {
			$blog_details = get_blog_details( $blog_id );
			if (
				! is_object( $blog_details ) ||
				1 == $blog_details->archived ||
				1 == $blog_details->deleted ||
				1 == $blog_details->spam
			) {
				return;
			}
		}

		gglstmp_schedule_sitemap( $blog_id );
	}
}

/**
 * Fires when the blog has been deleted or blog status has been changed to 'spam', 'deactivated(deleted)' or 'archived'.
 * @since   1.2.9
 * @param   int   $blog_id     Blog ID
 * @return  void
 */
if ( ! function_exists( 'gglstmp_delete_sitemap' ) ) {
	function gglstmp_delete_sitemap( $blog_id ) {

		/* remove blog sitemap files */
		$mask = "sitemap_{$blog_id}*.xml";
		array_map( "unlink", glob( ABSPATH . $mask ) );

		/* update network index file */
		gglstmp_create_sitemap_index( 0 );
	}
}

/* Function for delete of the plugin settings on register_activation_hook */
if ( ! function_exists( 'gglstmp_delete_settings' ) ) {
	function gglstmp_delete_settings() {
		global $wpdb;
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$all_plugins = get_plugins();

		if ( ! array_key_exists( 'google-sitemap-pro/google-sitemap-pro.php', $all_plugins ) ) {
			if ( is_multisite() ) {
				/* Get all blog ids */
				$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
				foreach ( $blogids as $blog_id ) {
					delete_blog_option( $blog_id, 'gglstmp_options' );
					delete_blog_option( $blog_id, 'gglstmp_robots' );
				}
			} else {
				delete_option( 'gglstmp_options' );
				delete_option( 'gglstmp_robots' );
			}
			/* remove all sitemaps */
			$sitemaps = gglstmp_get_sitemap_files( 'all' );
			array_map( "unlink", $sitemaps );
		}

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );
	}
}

register_activation_hook( __FILE__, 'gglstmp_activate' );

add_action( 'admin_menu', 'gglstmp_admin_menu' );

add_action( 'init', 'gglstmp_init' );
add_action( 'admin_init', 'gglstmp_admin_init' );

/* initialization */
add_action( 'plugins_loaded', 'gglstmp_plugins_loaded' );

add_action( 'admin_enqueue_scripts', 'gglstmp_add_plugin_stylesheet' );

add_action( 'transition_post_status', 'gglstmp_check_post_status', 10, 3 );
add_action( 'save_post', 'gglstmp_update_sitemap', 10, 2 );
add_action( 'trashed_post', 'gglstmp_update_sitemap' );

add_action( 'gglstmp_sitemap_cron','gglstmp_prepare_sitemap' );

/* rebuild sitemap on permalink structure change, on taxonomy term add/edit/delete */
add_action( 'permalink_structure_changed','gglstmp_schedule_sitemap', 10, 0 );
add_action( 'created_term','gglstmp_edited_term', 10, 3 );
add_action( 'edited_term','gglstmp_edited_term', 10, 3 );
add_action( 'delete_term','gglstmp_edited_term', 10, 3 );

add_filter( 'rewrite_rules_array','gglstmp_rewrite_rules', PHP_INT_MAX, 1 );

add_action( 'wp_head', 'gglstmp_add_verification_code' );

add_filter( 'plugin_action_links', 'gglstmp_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'gglstmp_links', 10, 2 );

add_action( 'admin_notices', 'gglstmp_plugin_banner' );

add_action( 'wpmu_new_blog', 'gglstmp_add_sitemap' );
add_action( 'activate_blog', 'gglstmp_add_sitemap' );
add_action( 'make_undelete_blog', 'gglstmp_add_sitemap' );
add_action( 'unarchive_blog', 'gglstmp_add_sitemap' );
add_action( 'make_ham_blog', 'gglstmp_add_sitemap' );

add_action( 'delete_blog', 'gglstmp_delete_sitemap' );
add_action( 'deactivate_blog', 'gglstmp_delete_sitemap' );
add_action( 'make_delete_blog', 'gglstmp_delete_sitemap' );
add_action( 'archive_blog', 'gglstmp_delete_sitemap' );
add_action( 'make_spam_blog', 'gglstmp_delete_sitemap' );