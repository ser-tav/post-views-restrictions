<?php
/** @var Post_View_Restrictions_Article $this */
?>

<div id="view-restriction-popup" class="vc_row">
    <div class="view-restriction-popup__wrapper">
        <div class="vc_col-sm-1">
            <img src="/wp-content/themes/bridge-child/images/orthoworld/document-icon.svg" alt="document icon">
        </div>
        <div class="vc_col-sm-9">
            <p class="popup-message">You have read <span class="count-viewed-articles"></span> <span>out of</span> <span
                        class="count-allowed-articles"></span> free articles for this month</p>
            <p class="user-role-guest">Subscribe as a Guest for $0 and unlock a total of 5 articles per month.</p>
            <p class="user-role-subscribed-guest">Subscribe as an Executive Member for access to unlimited articles, THE
                ORTHOPAEDIC INDUSTRY ANNUAL REPORT and more.</p>
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

	jQuery(document).ready(function($) {
		jQuery.post(
			'<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>',
			{
				action: 'post_view_restriction',
				post_id: '<?php echo get_the_ID() ?>'
			},
			function(response) {
				if (response.content) {
					jQuery('#expert-content-block').remove();
					jQuery('#full-content-block').html(response.content);

					if ( response.allowed && response.allowed && response.allowed >= response.viewed ) {
						jQuery('#view-restriction-popup .count-viewed-articles').html(response.viewed);
						jQuery('#view-restriction-popup .count-allowed-articles').html(response.allowed);
						jQuery('#view-restriction-popup').show();
					}

					jQuery('.post_text .author_description').show();
					jQuery('.post_text .related-posts').show();
				} else {
					jQuery('#view-restriction-content').show();
					jQuery('.user-role-guest').show();
				}

				if (response.user_role) {
					jQuery('.user-role-' + response.user_role).show();
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
</style>
