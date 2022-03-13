<?php

class Post_View_Restrictions_Activator {
	/**
	 * Create a table in database to save visit logs
	 */
	public static function activate() {
		global $wpdb;

		$create_tracking_table = ("CREATE TABLE " . $wpdb->prefix . "post_view_log (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			viewer_hash varchar(40),
			user_id bigint(20),
			post_id bigint(20),
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (ID)
		) CHARSET=utf8");

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $create_tracking_table );
	}
}
