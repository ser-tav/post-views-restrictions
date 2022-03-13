<?php $post_id = $post->ID; ?>
<div class="post_image">
	<?php $bridge_qode_video_type = get_post_meta($post_id, "video_format_choose", true);?>
	<?php if($bridge_qode_video_type == "youtube") { ?>
		<iframe name="fitvid-<?php the_ID(); ?>"  src="//www.youtube.com/embed/<?php echo get_post_meta($post_id, "video_format_link", true);  ?>?wmode=transparent" wmode="Opaque" width="805" height="403" allowfullscreen></iframe>
	<?php } elseif ($bridge_qode_video_type == "vimeo"){ ?>
		<iframe name="fitvid-<?php the_ID(); ?>" src="//player.vimeo.com/video/<?php echo get_post_meta($post_id, "video_format_link", true);  ?>?title=0&amp;byline=0&amp;portrait=0" width="800" height="450" allowfullscreen></iframe>
	<?php } elseif ($bridge_qode_video_type == "self"){ ?>
		<div class="video">
			<div class="mobile-video-image" style="background-image: url(<?php echo get_post_meta($post_id, "video_format_image", true);  ?>);"></div>
			<div class="video-wrap"  >
				<video class="video" poster="<?php echo get_post_meta($post_id, "video_format_image", true);  ?>" preload="auto">
					<?php if(get_post_meta($post_id, "video_format_webm", true) != "") { ?> <source type="video/webm" src="<?php echo get_post_meta($post_id, "video_format_webm", true);  ?>"> <?php } ?>
					<?php if(get_post_meta($post_id, "video_format_mp4", true) != "") { ?> <source type="video/mp4" src="<?php echo get_post_meta($post_id, "video_format_mp4", true);  ?>"> <?php } ?>
					<?php if(get_post_meta($post_id, "video_format_ogv", true) != "") { ?> <source type="video/ogg" src="<?php echo get_post_meta($post_id, "video_format_ogv", true);  ?>"> <?php } ?>
					<object width="320" height="240" type="application/x-shockwave-flash" data="<?php echo get_template_directory_uri(); ?>/js/flashmediaelement.swf">
						<param name="movie" value="<?php echo get_template_directory_uri(); ?>/js/flashmediaelement.swf" />
						<param name="flashvars" value="controls=true&file=<?php echo get_post_meta($post_id, "video_format_mp4", true);  ?>" />
						<img itemprop="image" src="<?php echo get_post_meta($post_id, "video_format_image", true);  ?>" width="1920" height="800" title="<?php echo esc_html__('No video playback capabilities', 'bridge'); ?>" alt="<?php echo esc_html__('Video thumb', 'bridge'); ?>" />
					</object>
				</video>
			</div></div>
	<?php } ?>
</div>