<?php
/** @var Post_View_Restrictions_Article $this */
?>

<div id="expert-content-block">
	<p><?php echo $this->get_short_content() ?></p>
</div>

<div id="full-content-block"><?php /* we will insert full post content via ajax here if user has access */ ?></div>

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

<style>
	#view-restriction-content {
		display: none;
		align-items: center;
		padding: 28px 0 12px;
        max-width: 696px;
        margin: 0 auto;
		position: relative;
		width: 100%;
	}
	.post_text .author_description {
		display: none;
	}
	.post_text .related-posts {
		display: none;
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
        max-width: 615px;
        padding-left: 0;
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

    @media only screen and (min-width: 768px) {
        #view-restriction-content {
            margin-left: -27px;
        }
    }

    @media only screen and (max-width: 768px) {
        #view-restriction-content .vc_col-sm-1 {
            display: none;
        }
    }
</style>
