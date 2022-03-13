<?php

/**
 * Class Post_View_Restrictions
 */
class Post_View_Restrictions {
	public function run() {
		if ( $this->is_webinar_view_post() ) {
			$this->get_webinar_handler()->run();
		} elseif ( $this->is_current_page_has_restriction() ) {
			$this->get_article_handler()->run();
		}
	}

	public function run_ajax() {
		$this->get_article_handler()->run_ajax();
		$this->get_webinar_handler()->run_ajax();
	}

	private function get_article_handler() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-view-restrictions-article.php';
		return new Post_View_Restrictions_Article();
	}

	private function get_webinar_handler() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-view-restrictions-webinar.php';
		return new Post_View_Restrictions_Webinar();
	}

	/**
	 * @return bool
	 */
	private function is_current_page_has_restriction() {
		global $post;

		if ( is_single() && isset( $post ) && isset( $post->post_type ) ) {
			$is_press_release = has_category( get_category_by_slug( 'press-releases' ), $post );

			return 'post' === $post->post_type && ! $is_press_release;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	private function is_webinar_view_post() {
		global $post;
		return has_category( get_category_by_slug( 'webinars' ), $post );
	}
}
