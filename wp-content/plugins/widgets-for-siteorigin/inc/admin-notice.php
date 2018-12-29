<?php
require  plugin_dir_path(__FILE__) . '/admin-notices-dismissal/persist-admin-notices-dismissal.php';
add_action( 'admin_init', array( 'PAnD', 'init' ) );

function wpinked_so_admin_notice__info() {
    if ( ! PAnD::is_admin_notice_active( 'dismiss-so-notice-forever' ) ) {
		return;
	}
    ?>
    <div data-dismissible="dismiss-so-notice-forever" class="notice notice-info is-dismissible">
        <p><?php _e( 'Hey guys. Sorry I have not been dedicating enough time to the plugin. I\'ll catch up with all outstanding issues as well as add new widgets in the next couple of weeks. Thanks for your support and understanding.', 'wpinked-widgets' ); ?></p>
    </div>
    <?php
}
add_action( 'admin_notices', 'wpinked_so_admin_notice__info' );
