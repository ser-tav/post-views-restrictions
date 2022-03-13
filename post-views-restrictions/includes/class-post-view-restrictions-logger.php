<?php
/**
 * Class Post_View_Restrictions_Logger
 */
class Post_View_Restrictions_Logger {
	/**
	 * Save info about post_visit to database
	 */
	public function log_post_visit() {
		global $wpdb;

		$post_id = get_the_ID();
		$viewer_hash = $this->get_viewer_hash();
		$user_id = $this->get_user_id();

		if ( ! $this->is_post_visit_logged( $post_id, $viewer_hash, $user_id ) ) {
			$wp_prefix = $wpdb->prefix;
			$wpdb->insert(
				$wp_prefix . 'post_view_log',
				array(
					'viewer_hash' => $viewer_hash,
					'user_id' => $user_id,
					'post_id' => sanitize_text_field( $post_id ),
				)
			);
		}
	}

	/**
	 * @return bool
	 */
	public function is_current_post_visit_counted() {
		$post_id = get_the_ID();
		$viewer_hash = $this->get_viewer_hash();
		$user_id = $this->get_user_id();

		return $this->is_post_visit_logged( $post_id, $viewer_hash, $user_id );
	}

	/**
	 * @return int
	 */
	public function get_count_of_visited_posts_last_month() {
		global $wpdb;

		$viewer_hash = $this->get_viewer_hash();
		$user_id = $this->get_user_id();

		$query = 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'post_view_log WHERE ';

		$where = '(viewer_hash = "' . $viewer_hash . '")';
		if ( 0 != $user_id ) {
			$where .= ' OR (user_id = ' . $user_id . ')';
		}

		$where = '(' . $where . ') AND (created_at >= NOW() - INTERVAL 1 MONTH)';

		return (int) $wpdb->get_var( $wpdb->prepare( $query . $where ) );
	}

	/**
	 * @param $post_id
	 * @param $viewer_hash
	 * @param $user_id
	 * @return bool
	 */
	public function is_post_visit_logged( $post_id, $viewer_hash, $user_id ) {
		global $wpdb;

		$post_id = sanitize_text_field( $post_id );
		$viewer_hash = sanitize_text_field( $viewer_hash );
		$user_id = sanitize_text_field( $user_id );

		$query = 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'post_view_log ';

		$where = 'WHERE (post_id = ' . $post_id . ' AND viewer_hash = "' . $viewer_hash . '")';
		if ( 0 != $user_id ) {
			$where .= ' OR (post_id = ' . $post_id . ' AND user_id = ' . $user_id . ')';
		}

		return 0 != (int) $wpdb->get_var( $wpdb->prepare( $query . $where ) );
	}


	/**
	 * @return int|string
	 */
	private function get_user_id() {
		$user_id = (int) get_current_user_id();
		if ( ! $user_id || ! is_numeric( $user_id ) ) {
			$user_id = 0;
		}

		return sanitize_text_field ( $user_id );
	}

	/**
	 * @return string
	 */
	private function get_viewer_hash() {
		$ip = $this->get_ip_address();
		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';

		return sanitize_text_field( hash_hmac( 'sha1', $ip . $user_agent, false ) );
	}

	/**
	 * @return string
	 */
	public function get_ip_address() {
		if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}
		return '';
	}
}
