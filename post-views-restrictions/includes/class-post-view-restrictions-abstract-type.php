<?php

/**
 * Class Post_View_Restrictions_Abstract_Type
 */
abstract class Post_View_Restrictions_Abstract_Type {
	/**
	 * @var Post_View_Restrictions_Logger
	 */
	protected $logger;

	public function run() {
		$this->load_dependencies();
		$this->handle_restriction();
	}

	public function run_ajax() {
		$this->load_dependencies();
		$this->add_ajax_handler();
	}

	/**
	 * Load the required dependencies for this plugin
	 */
	protected function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-view-restrictions-logger.php';

		$this->logger = new Post_View_Restrictions_Logger();
	}

	abstract protected function handle_restriction();

	abstract protected function add_ajax_handler();

	abstract protected function ajax_view_restriction_handler_content( $post );

	public function ajax_view_restriction_handler() {
		if ( isset( $_POST['post_id'] ) && ! empty( $_POST['post_id'] ) ) {
			global $post;
			$post_id = sanitize_text_field( wp_unslash( $_POST['post_id'] ) );
			$post = get_post( $post_id );
			setup_postdata( $post );

			$response = array(
				'user_role' => $this->get_user_access_level(),
				'error' => false,
				'content' => '',
			);

			if ( $this->is_user_has_restriction() ) {
				$allowed = $this->get_count_of_free_articles();
				$viewed = $this->get_count_of_viewed_articles();

				if ( $allowed > $viewed ) {
					$this->logger->log_post_visit();
					$viewed++;
				}

				$response['viewed'] = $viewed;
				$response['allowed'] = $allowed;

				if ( $this->logger->is_current_post_visit_counted() ) {
					$response['content'] = $this->ajax_view_restriction_handler_content( $post );
				}
			} else {
				$response['content'] = $this->ajax_view_restriction_handler_content( $post );
			}

			wp_reset_postdata();
		} else {
			$response = array( 'error' => true );
		}

		return wp_send_json( $response );
	}

	/**
	 * @return int
	 */
	public function get_count_of_viewed_articles() {
		return $this->logger->get_count_of_visited_posts_last_month();
	}

	/**
	 * @return int
	 */
	public function get_count_of_free_articles() {
		$level = $this->get_user_access_level();
		switch ( $level ) {
			case 'guest':
				return 3;
			case 'subscribed-guest':
				return 5;
			default:
				return 3;
		}
	}

	/**
	 * @return false
	 */
	protected function get_user_access_level() {
		$user = wp_get_current_user();
		$is_allowed_admin_role = 0 < count( array_intersect( $user->roles, array( 'editor', 'administrator', 'author' ) ) );

		//TODO: add logic with subscriptions
		$is_subscribed_user = false;

		if ( $is_allowed_admin_role || $is_subscribed_user ) {
			return 'subscribed';
		}

		if ( is_user_logged_in() ) {
			return 'subscribed-guest';
		}

		return 'guest';
	}

	/**
	 * @return bool
	 */
	protected function is_user_has_restriction() {
		return 'subscribed' != $this->get_user_access_level();
	}

	/**
	 * @return bool
	 */
	protected function is_current_page_has_restriction() {
		global $post;

		if ( is_single() && isset( $post ) && isset( $post->post_type ) ) {
			$is_press_release = has_category( get_category_by_slug( 'press-releases' ), $post );

			// Uses can see webinar page, but can not see webinar video.
			return 'post' === $post->post_type
				&& ! $is_press_release
				&& ! $this->is_webinar_view_post();
		}

		return false;
	}

	/**
	 * @return bool
	 */
	protected function is_webinar_view_post() {
		global $post;
		return has_category( get_category_by_slug( 'webinars' ), $post );
	}
}
