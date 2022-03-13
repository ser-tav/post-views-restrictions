<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-view-restrictions-abstract-type.php';

/**
 * Class Post_View_Restrictions_Article
 */
class Post_View_Restrictions_Article extends Post_View_Restrictions_Abstract_Type {
	/**
	 * Show popup, log view, trim content
	 */
	protected function handle_restriction() {
		add_filter( 'the_content', array( $this, 'remove_post_content' ) );
		add_action( 'wp_footer', array( $this, 'restriction_popup_html' ) );
		add_action( 'action_after_article_content', array( $this, 'restriction_content_html' ) );
	}

	protected function add_ajax_handler() {
		add_action( 'wp_ajax_post_view_restriction', array( $this, 'ajax_view_restriction_handler' ) );
		add_action( 'wp_ajax_nopriv_post_view_restriction', array( $this, 'ajax_view_restriction_handler' ) );
	}

	protected function ajax_view_restriction_handler_content( $post ) {
		return apply_filters( 'the_content', get_post_field( 'post_content', $post ) );
	}

	public function restriction_popup_html() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/article/view-restriction-popup.php';
	}

	public function remove_post_content( $content ) {
		return '';
	}

	public function restriction_content_html() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/article/view-restriction-content.php';
	}

	/**
	 * @return string
	 */
	public function get_short_content() {
		global $post;

		$content = $post->post_content;

		$maxchar = strlen( $content ) / 2;
		$maxchar = $maxchar > 350 ? 350 : $maxchar;

		return $this->trim_html( $content, array( 'maxchar' => $maxchar ) );
	}

	/**
	 * @param $text
	 * @param string $args
	 * @return false|mixed|string|string[]|null
	 */
	private function trim_html( $text, $args = '' ) {
		if ( is_string( $args ) ) {
			parse_str( $args, $args );
		}

		$rg = (object) array_merge( [
			'maxchar'           => 500,
			'autop'             => true,
			'more_text'         => '...',
			'ignore_more'       => false,
			'save_tags'         => '<strong><b><a><em><i><var><code><span>',
			'sanitize_callback' => static function( string $text, object $rg ){
				return strip_tags( $text, $rg->save_tags );
			},
		], $args );

		// strip content shortcodes: [foo]some data[/foo]. Consider markdown
		$text = preg_replace( '~\[([a-z0-9_-]+)[^\]]*\](?!\().*?\[/\1\]~is', '', $text );
		// strip others shortcodes: [singlepic id=3]. Consider markdown
		$text = preg_replace( '~\[/?[^\]]*\](?!\()~', '', $text );
		// strip direct URLs
		$text = preg_replace( '~(?<=\s)https?://.+\s~', '', $text );
		$text = trim( $text );

		// <!--more-->
		if ( ! $rg->ignore_more && strpos( $text, '<!--more-->' ) ) {
			preg_match( '/(.*)<!--more-->/s', $text, $mm );
			$text = trim( $mm[1] );
			$text_append = sprintf( ' <a href="%s#more-%d">%s</a>', get_permalink( $post ), $post->ID, $rg->more_text );
		}
		// text, excerpt, content
		else {

			$text = call_user_func( $rg->sanitize_callback, $text, $rg );
			$has_tags = false !== strpos( $text, '<' );

			// collect html tags
			if ( $has_tags ) {
				$tags_collection = array();
				$nn = 0;

				$text = preg_replace_callback( '/<[^>]+>/', static function( $match ) use ( & $tags_collection, & $nn ) {
					$nn++;
					$holder = "~$nn";
					$tags_collection[ $holder ] = $match[0];

					return $holder;
				}, $text );
			}

			// cut text
			$cuted_text = mb_substr( $text, 0, $rg->maxchar );
			if ( $text !== $cuted_text ) {
				// del last word, it not complate in 99%
				$text = preg_replace( '/(.*)\s\S*$/s', '\\1...', trim( $cuted_text ) );
			}

			// bring html tags back
			if ( $has_tags ) {
				$text = strtr( $text, $tags_collection );
				$text = force_balance_tags( $text );
			}
		}

		// add <p> tags. Simple analog of wpautop()
		if ( $rg->autop ) {
			$text = preg_replace(
				[ "/\r/", "/\n{2,}/", "/\n/" ],
				[ '', '</p><p>', '<br />' ],
				"<p>$text</p>"
			);
		}

		if ( isset( $text_append ) ) {
			$text .= $text_append;
		}

		return $text;
	}
}
