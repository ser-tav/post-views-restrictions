<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-view-restrictions-abstract-type.php';

/**
 * Class Post_View_Restrictions_Webinar
 */
class Post_View_Restrictions_Webinar extends Post_View_Restrictions_Abstract_Type {

	protected function handle_restriction() {
		add_action( 'wp_footer', array( $this, 'restriction_popup_html' ) );
	}

	protected function add_ajax_handler() {
		add_action( 'wp_ajax_webinar_view_restriction', array( $this, 'ajax_view_webinar_restriction_handler' ) );
		add_action( 'wp_ajax_nopriv_webinar_view_restriction', array( $this, 'ajax_view_webinar_restriction_handler' ) );

		add_action( 'wp_ajax_webinar_video_view_restriction', array( $this, 'ajax_view_restriction_handler' ) );
		add_action( 'wp_ajax_nopriv_webinar_video_view_restriction', array( $this, 'ajax_view_restriction_handler' ) );
	}

	public function ajax_view_webinar_restriction_handler() {
		if ( isset( $_POST['post_id'] ) && ! empty( $_POST['post_id'] ) ) {
			global $post;
			$post_id = sanitize_text_field( wp_unslash( $_POST['post_id'] ) );
			$post = get_post( $post_id );
			setup_postdata( $post );

			$response = array(
				'user_role' => $this->get_user_access_level(),
				'error' => false,
				'status' => 'register',
			);

			if ( $this->is_user_has_restriction() ) {
				$allowed = $this->get_count_of_free_articles();
				$viewed = $this->get_count_of_viewed_articles();

				$response['viewed'] = $viewed;
				$response['allowed'] = $allowed;

				if ( $allowed > $viewed || $this->logger->is_current_post_visit_counted() ) {
					$response['status'] = 'watch';
				}
			} else {
				$response['status'] = 'watch';
			}

			wp_reset_postdata();
		} else {
			$response = array( 'error' => true );
		}

		return wp_send_json( $response );
	}

	protected function ajax_view_restriction_handler_content( $post ) {
		ob_start();
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/webinar/video.php';
		return ob_get_clean();
	}

	public function restriction_popup_html() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/webinar/view-restriction-popup.php';
	}

	/**
	 * @return string
	 */
	public function get_post_title() {
		global $post;
		return $post->post_title;
	}
}
