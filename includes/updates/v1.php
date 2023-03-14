<?php


function wp_sub_accounts_minor_update_v1_1() {

	global $wpdb;

	$sql = '
		SELECT  *
		FROM	'.$wpdb->usermeta .'
		WHERE   meta_key = "parent"
	';

	$results = $wpdb->get_results( $sql );
	if ( ! empty( $results ) ) {

		foreach( $results as $result ) {

			$child_id  = $result->user_id;
			$parent_id = $result->meta_value;
			wp_sub_accounts_update()->log( 'Updating: ' . $child_id . ' is_child_of_' . $parent_id );
			update_user_meta( $child_id, 'is_child_of_' . $parent_id, true );
		}

		return true;

	} else {

		$msg = __( 'No child accounts were found to update. ', 'wp-sub-accounts' );
		wp_sub_accounts_update()->log( $msg );
		$msg = __( 'There was a problem performing the minor update to version 1.1 of the Wp Sub Accounts plugin.' );
		return new WP_Error( __FUNCTION__, $msg );

	}



}

