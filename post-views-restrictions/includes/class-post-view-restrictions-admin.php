<?php

if ( ! function_exists( 'wp_get_current_user' ) ) {
	include( ABSPATH . 'wp-includes/pluggable.php' );
}

class Post_View_Restrictions_Admin {
	public function run( $plugin_basename ) {
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_settings_link' ) );
		add_options_page(
			'Article & Webinar Restrictions',
			'Article & Webinar Restrictions',
			'administrator',
			'wp-post-view-restrictions',
			array( $this, 'settings_page' )
		);
	}

	function settings_page() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/settings.php';
	}

	public function add_settings_link($links) {
		$settings_link = '<a href="admin.php?page=wp-post-view-restrictions">Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Create a table in database to save visit logs
	 */
	public static function reset() {
		global $wpdb;

		/*$create_tracking_table = ("CREATE TABLE " . $wpdb->prefix . "post_view_log (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			viewer_hash varchar(40),
			user_id bigint(20),
			post_id bigint(20),
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (ID)
		) CHARSET=utf8");*/

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		//dbDelta( $create_tracking_table );
	}
}
