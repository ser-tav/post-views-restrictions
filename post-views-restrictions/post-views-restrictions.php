<?php
/**
	Plugin Name: Article & Webinar Restrictions
	Description: Post View Restrictions
	Version:     1.0.0
*/

/**
 * The code that runs during plugin activation.
 */
function activate_post_view_restrictions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-view-restrictions-activator.php';
	Post_View_Restrictions_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_post_view_restrictions' );


/**
 * Run main plugin logic.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-post-view-restrictions.php';
$post_view_restrictions = new Post_View_Restrictions();

add_action( 'wp', array( $post_view_restrictions, 'run' ) );

if ( wp_doing_ajax() ) {
	$post_view_restrictions->run_ajax();
}

if ( is_admin() ) {
	require plugin_dir_path( __FILE__ ) . 'includes/class-post-view-restrictions-admin.php';

	$post_view_restrictions_admin = new Post_View_Restrictions_Admin();
	$post_view_restrictions_admin->run( plugin_basename( __FILE__ ) );
}
