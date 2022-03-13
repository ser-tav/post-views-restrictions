<?php
if ( ! current_user_can('administrator') ) {
	die( 'Permissions Denied' );
}

if ( isset( $_POST['post-view-log-table-nonce'] ) && wp_verify_nonce( $_POST['post-view-log-table-nonce'], 'clear-post-view-log-table-nonce' ) ) {
	try {
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "post_view_log" )
		);
		echo '<div class="updated"><p>Success. Table with visitors is empty now</p></div>';
	} catch ( \Exception $e ) {
		echo '<div class="error"><p>Error: ' . $e->getMessage() . '</p></div>';
	}
}
?>


<div class="wplpv-wrapper">
	<h2>Article & Webinar Restrictions Settings</h2>

	<form method="post" >
		<label>Please, clear visitor table only for testing process.</label>
		<?php
		wp_nonce_field( 'clear-post-view-log-table-nonce','post-view-log-table-nonce' );
		submit_button('Clear Visitor Table');
		?>
	</form>
</div>