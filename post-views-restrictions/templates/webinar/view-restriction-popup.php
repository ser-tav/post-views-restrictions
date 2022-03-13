<?php
/** @var Post_View_Restrictions_Webinar $this */
?>

<div id="webinar-video-popup">
	<div class="popup-header">
		<img class="logo" src="<?php echo bridge_qode_options()->getOptionValue('logo_image') ?>" />
		<a href="javascript:closeViewWebinarPopup();" class="close-popup" aria-label="Ok"><img src="/wp-content/themes/bridge-child/images/orthoworld/close-black.svg"></a>
	</div>

	<h2><?php echo $this->get_post_title() ?></h2>

	<div id="video-block">loading...<?php /* we will insert full video content via ajax here if user has access */ ?></div>

	<div id="view-restriction-content" class="vc_row">
        <div class="vc_col-sm-1">
            <img src="/wp-content/themes/bridge-child/images/orthoworld/document-black.svg" alt="document icon">
        </div>
		<div class="vc_col-sm-11">
			<p class="user-role-guest sans-font">You are <b>out of free articles</b> for this month</p>
			<p class="user-role-guest">Subscribe as a Guest for $0 and unlock a total of 5 articles per month.</p>

			<p class="user-role-subscribed-guest sans-font">You are <b>out of five articles</b> for this month</p>
			<p class="user-role-subscribed-guest">Subscribe as an Executive Member for access to unlimited articles, THE ORTHOPAEDIC INDUSTRY ANNUAL REPORT and more.</p>
			<div>
				<a class="btn-subscribe" href="<?= wc_get_account_endpoint_url('manage-subscriptions'); ?>">Subscribe Now<img src="/wp-content/themes/bridge-child/images/orthoworld/cart-black.svg" alt="document icon"></a>
			</div>
		</div>
	</div>
</div>


<div id="view-restriction-popup" class="vc_row">
    <div class="view-restriction-popup__wrapper">
        <div class="vc_col-sm-1">
            <img src="/wp-content/themes/bridge-child/images/orthoworld/document-icon.svg" alt="document icon">
        </div>
        <div class="vc_col-sm-9">
            <p class="popup-message">You have watched <span class="count-viewed-articles"></span> out of <span class="count-allowed-articles"></span> free articles for this month</p>
            <p class="user-role-guest">Subscribe as a Guest for $0 and unlock a total of 5 articles per month.</p>
            <p class="user-role-subscribed-guest">Subscribe as an Executive Member for access to unlimited articles, THE ORTHOPAEDIC INDUSTRY ANNUAL REPORT and more.</p>
        </div>
        <div class="vc_col-sm-2">
            <a class="btn-subscribe" href="<?= wc_get_account_endpoint_url('manage-subscriptions'); ?>">Subscribe Now
                <img src="/wp-content/themes/bridge-child/images/orthoworld/cart-black.svg" alt="document icon"></a>
            <a href="javascript:closeViewPostRestrictionPopup();" class="close-popup" aria-label="Ok"> <img src="/wp-content/themes/bridge-child/images/orthoworld/close-gray.svg" alt="document icon"></a>
        </div>
    </div>
</div>

<script type="text/javascript">
	function closeViewPostRestrictionPopup() {
		jQuery('#view-restriction-popup').hide();
	}

	function closeViewWebinarPopup() {
		jQuery('#webinar-video-popup').hide();
	}
	jQuery(document).on('click', '.webinar-watch-now', function($) {
		jQuery('#webinar-video-popup').show();

		if ( !jQuery('#webinar-video-popup').hasClass('register') && !jQuery('#webinar-video-popup').hasClass('watch') ) {
			jQuery.post(
				'<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>',
				{
					action: 'webinar_video_view_restriction',
					post_id: '<?php echo get_the_ID() ?>'
				},
				function(response) {
					if (response.content) {
						jQuery('#video-block').html(response.content);
					}
					if ( response.allowed && response.allowed && response.allowed >= response.viewed ) {
						jQuery('#view-restriction-popup .count-viewed-articles').html(response.viewed);
						jQuery('#view-restriction-popup .count-allowed-articles').html(response.allowed);
						jQuery('#view-restriction-popup').show();
					}
					if (response.user_role) {
						jQuery('.user-role-' + response.user_role).show();
					}
					jQuery('#webinar-video-popup').addClass('watch');
				}
			);
		}
	});

	jQuery(document).ready(function($) {
		jQuery.post(
			'<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>',
			{
				action: 'webinar_view_restriction',
				post_id: '<?php echo get_the_ID() ?>'
			},
			function(response) {
				if ( response.allowed && response.allowed && response.allowed >= response.viewed ) {
					jQuery('#view-restriction-popup .count-viewed-articles').html(response.viewed);
					jQuery('#view-restriction-popup .count-allowed-articles').html(response.allowed);
					jQuery('#view-restriction-popup').show();
				}

				if (response.user_role) {
					jQuery('.user-role-' + response.user_role).show();
				}

				if ( response.status == 'register' ) {
					jQuery('#view-restriction-content').show();
					jQuery('#webinar-video-popup').addClass('register');
				}
			}
		);
	});
</script>

<style>
	#view-restriction-popup {
		display: none;
		align-items: center;
		padding: 0;
		position: fixed;
		width: 100%;
		bottom: 0;
		left: 0;
		background: #3E5C81;
		border-radius: 4px;
		z-index: 9999;
        max-width: 1288px;
        margin: 0 auto;
        right: 0;
	}
	#view-restriction-popup p {
		color: white;
	}
	#view-restriction-popup .close-popup {
		position: absolute;
		top: -30px;
		right: 10px;
		color: white;
		font-weight: 600;
	}
	.user-role-guest, .user-role-subscribed-guest {
		display: none;
	}

	#webinar-video-popup {
		display: none;
		width: 100%;
		height: 100%;
		position: fixed;
		z-index: 999999;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: white;
		text-align: center;
	}

	#webinar-video-popup .popup-header {
		height: 100px;
		padding: 50px;
	}
	#webinar-video-popup .popup-header .logo {
		float: left;
	}
	#webinar-video-popup .popup-header .close-popup {
		float: right;
	}

	.full_width.webinar .post-title {
		height: 300px;
	}
	.full_width.webinar .post_text {
		max-width: 1000px;
		margin: 0 auto;
	}

    #view-restriction-content {
        display: none;
        align-items: center;
        padding: 22px;
        position: relative;
        width: 100%;
        max-width: 740px;
        margin: 0 auto;
    }

    #view-restriction-content .vc_col-sm-11 p.sans-font {
        font-family: 'Encode Sans';
        font-weight: normal;
        font-size: 24px;
        line-height: 36px;
        letter-spacing: .5px;
        color: #3D4242;
        margin: 0 0 13px;
    }

    #view-restriction-content .vc_col-sm-11 p:not(.sans-font) {
        font-family: 'Vollkorn';
        font-weight: normal;
        font-size: 20px;
        line-height: 30px;
        color: #3D4242;
        margin: 0 0 32px;
    }

    #view-restriction-content .btn-subscribe {
        display: flex;
        align-items: center;
        padding: 21px 24px;
        justify-content: space-between;
        font-family: 'Encode Sans';
        font-style: normal;
        font-weight: bold;
        font-size: 16px;
        line-height: 22px;
        letter-spacing: 1px;
        color: #3D4242;
        max-width: 208px;
        width: 100%;
        margin: 0 auto;
    }

    #view-restriction-content .btn-subscribe img {
        width: 24px;
        height: 24px;
    }

    #view-restriction-content .vc_col-sm-1 {
        padding-top: 16px;
        min-width: 48px;
        width: auto;
        padding-right: 32px;
        padding-left: 0;
        display: block;
    }

    #view-restriction-content .vc_col-sm-1 img {
        width: 48px;
        height: 48px;
        max-width: 100%;
        min-width: 48px;
    }

    #view-restriction-content .vc_col-sm-11 {
        max-width: 616px;
        padding-left: 0;
        text-align: left;
    }

    #expert-content-block {
        position: relative;
    }

    #expert-content-block:after {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        content: '';
        background: linear-gradient(360deg, #FFFFFF 0%, rgba(255, 255, 255, 0) 100%);
    }

    @media only screen and (max-width: 768px) {
        #view-restriction-content .vc_col-sm-1 {
            display: none;
        }

        body #view-restriction-content {
            width: auto;
        }
    }
</style>
