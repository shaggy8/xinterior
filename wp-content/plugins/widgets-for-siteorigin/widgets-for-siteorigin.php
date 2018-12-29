<?php

/**
 * @wordpress-plugin
 * Plugin Name: Widgets for SiteOrigin
 * Plugin URI: http://widgets.wpinked.com/
 * Description: A collection of highly customizable and thoughtfully crafted widgets. Built on top of the SiteOrigin Widgets Bundle.
 * Version: 1.4.2
 * Author: wpinked
 * Author URI: widgets.wpinked.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wpinked-widgets
 * Domain Path: /languages
 * @fs_premium_only /inc-pro/, /js-pro/, /css-pro/, /widgets-pro/
 *
 * @link wpinked.com
 * @since 1.0.0
 * @package Widgets_For_SiteOrigin
 *
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !function_exists( 'wfs_fs_wpinked_widgets_so' ) ) {
    // Create a helper function for easy SDK access.
    function wfs_fs_wpinked_widgets_so()
    {
        global  $wfs_fs_wpinked_widgets_so ;
        
        if ( !isset( $wfs_fs_wpinked_widgets_so ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wfs_fs_wpinked_widgets_so = fs_dynamic_init( array(
                'id'             => '2753',
                'slug'           => 'widgets-for-siteorigin',
                'type'           => 'plugin',
                'public_key'     => 'pk_12d52e412c9845eb83c6c5c8bc3d3',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'first-path' => 'admin.php?page=wpinked-widgets',
            ),
                'is_live'        => true,
            ) );
        }
        
        return $wfs_fs_wpinked_widgets_so;
    }
    
    // Init Freemius.
    wfs_fs_wpinked_widgets_so();
    // Signal that SDK was initiated.
    do_action( 'wfs_fs_wpinked_widgets_so_loaded' );
    define( 'INKED_SO_VER', '1.4.2' );
    // Allow JS suffix to be pre-set
    if ( !defined( 'INKED_JS_SUFFIX' ) ) {
        define( 'INKED_JS_SUFFIX', '.min' );
    }
    // Path to plugin
    define( 'INKED_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );
    // Visibility
    require_once 'inc/visibility.php';
    // Enqueue JS and CSS files
    require_once 'inc/enqueue.php';
    // Template Functions
    require_once 'inc/functions.php';
    require_once 'inc/template-functions.php';
    // Admin Notices
    // require_once ( 'inc/admin-notice.php' );
    if ( !function_exists( 'wpinked_so_widgets' ) ) {
        // Loading widget folders
        function wpinked_so_widgets( $folders )
        {
            $folders[] = plugin_dir_path( __FILE__ ) . '/widgets/';
            return $folders;
        }
    
    }
    add_filter( 'siteorigin_widgets_widget_folders', 'wpinked_so_widgets' );
    if ( !function_exists( 'wpinked_so_add_widget_tabs' ) ) {
        // Placing all widgets under the 'Widgets for SiteOrigin' Tab
        function wpinked_so_add_widget_tabs( $tabs )
        {
            $tabs[] = array(
                'title'  => __( 'Widgets for SiteOrigin', 'wpinked-widgets' ),
                'filter' => array(
                'groups' => array( 'widgets-for-so' ),
            ),
            );
            return $tabs;
        }
    
    }
    add_filter( 'siteorigin_panels_widget_dialog_tabs', 'wpinked_so_add_widget_tabs', 5 );
    if ( !function_exists( 'wpinked_so_widget_add_bundle_groups' ) ) {
        // Adding Icon for all Widgets
        function wpinked_so_widget_add_bundle_groups( $widgets )
        {
            foreach ( $widgets as $class => &$widget ) {
                
                if ( preg_match( '/Inked_(.*)_SO_Widget/', $class, $matches ) ) {
                    $widget['icon'] = 'wpinked-widget dashicons dashicons-editor-code';
                    $widget['groups'] = array( 'widgets-for-so' );
                }
            
            }
            return $widgets;
        }
    
    }
    add_filter( 'siteorigin_panels_widgets', 'wpinked_so_widget_add_bundle_groups', 11 );
    if ( !function_exists( 'wpinked_so_translation' ) ) {
        // Making the plugin translation ready
        function wpinked_so_translation()
        {
            load_plugin_textdomain( 'wpinked-widgets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }
    
    }
    add_action( 'plugins_loaded', 'wpinked_so_translation' );
    /**
     * GLOBAL VARIABLES
     */
    global  $wpinked_widget_count ;
    $wpinked_widget_count = 0;
    require_once 'inc/activate.php';
    require_once 'admin/admin.php';
    if ( !function_exists( 'wpinked_so_class_prefixes' ) ) {
        function wpinked_so_class_prefixes( $class_prefixes )
        {
            $class_prefixes[] = 'Inked_Widget_Field_';
            return $class_prefixes;
        }
    
    }
    add_filter( 'siteorigin_widgets_field_class_prefixes', 'wpinked_so_class_prefixes' );
    if ( !function_exists( 'wpinked_so_fields_class_paths' ) ) {
        function wpinked_so_fields_class_paths( $class_paths )
        {
            $class_paths[] = plugin_dir_path( __FILE__ ) . 'fields/';
            return $class_paths;
        }
    
    }
    add_filter( 'siteorigin_widgets_field_class_paths', 'wpinked_so_fields_class_paths' );
    if ( !function_exists( 'wpinked_so_plugin_activate' ) ) {
        function wpinked_so_plugin_activate()
        {
            add_option( 'simian_redirect', true );
        }
    
    }
    if ( !function_exists( 'wpinked_so_plugin_redirect' ) ) {
        function wpinked_so_plugin_redirect()
        {
            
            if ( get_option( 'simian_redirect', false ) ) {
                delete_option( 'simian_redirect' );
                wp_redirect( admin_url( 'admin.php?page=wpinked-widgets' ) );
            }
        
        }
    
    }
    
    if ( !function_exists( 'wpinked_pro_so_widgets' ) ) {
        register_activation_hook( __FILE__, 'wpinked_so_plugin_activate' );
        add_action( 'admin_init', 'wpinked_so_plugin_redirect' );
    }

}
