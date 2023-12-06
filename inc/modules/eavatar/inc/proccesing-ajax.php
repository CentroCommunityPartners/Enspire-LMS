<?php

/**
 * ADD Avatar Ajax Action
 */
add_action( 'wp_ajax_eavatar_upload_base64', 'eavatar_upload_base64_handler' );
function eavatar_upload_base64_handler() {

	$base64_img = $_POST['image'];
	$user_id    = $_POST['user_id'];

	if ( ! $user_id || ! $base64_img ) {
		die();
	}

	$json  = eavatar_save_avatar_base64($base64_img, $user_id);

	//send json response
	die( json_encode( $json ) );
}

/**
 * Remove Avatar Ajax Action
 */
add_action( 'wp_ajax_eavatar_remove', 'delete_evatar_attachment' );
function delete_evatar_attachment() {

	if ( ! $_POST['image_id'] || ! $_POST['user_id'] ) {
		return;
	}

	wp_delete_attachment( $_POST['image_id'], true );
	delete_user_meta( $_POST['user_id'], 'avatar' );
}
