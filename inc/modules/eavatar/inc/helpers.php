<?php

/**
 * Save the image base64 on the wp media.
 * @source https://gist.github.com/cyberwani/ad5452b040001878d692c3165836ebff
 */
function eavatar_save_avatar_base64( $base64_img, $user_id ) {

	if ( ! $user_id || !$base64_img) {
		return 0;
	}

	$user      = get_userdata( $user_id );
	$file_name = 'avatar-' . $user->user_login;

	//delete current
	$current_avatar_id = get_user_meta( $user_id, 'avatar', true );
	if ( $current_avatar_id ) {
		wp_delete_attachment( $current_avatar_id, true );
	}

	// Upload dir.
	$upload_dir  = wp_upload_dir();
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

	$img       = str_replace( 'data:image/png;base64,', '', $base64_img );
	$img       = str_replace( ' ', '+', $img );
	$decoded   = base64_decode( $img );
	$filename  = sanitize_title( $file_name . '-' . md5( microtime() ) ) . '.jpg';
	$file_type = 'image/jpg';
	//$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

	// Save the image in the uploads directory.
	$upload_file = file_put_contents( $upload_path . $filename, $decoded );

	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
		'guid'           => $upload_dir['url'] . '/' . basename( $filename )
	);

	$imagePath = $upload_dir['path'] . '/' . $filename;


	$attachment_id = wp_insert_attachment( $attachment, $imagePath );
	$json          = [];

	if ( is_wp_error( $attachment_id ) ) {
		$json['error'] = "Error.";
	} else {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attachment_id, $imagePath );
		$res1        = wp_update_attachment_metadata( $attachment_id, $attach_data );


		//Get the attachment entry in media library
		$image_full_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );

		//Save the image in the user metadata
		update_user_meta( $user_id, 'avatar', $attachment_id );

		//json response
		$json['aid']    = $attachment_id;
		$json['src']    = $image_full_attributes[0];
		$json['status'] = 'ok';
	}

	return $json;
}

function get_eavatar_id( $user_id = 0 ) {

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( ! $user_id ) {
		return;
	}

	return get_user_meta( $user_id, 'avatar', true );
}

function get_eavatar_image_url( $user_id = 0, $size = 'full' ) {
	$eavatar_id = get_eavatar_id( $user_id );
	if ( ! $eavatar_id ) {
		return;
	}

	return wp_get_attachment_image_url( $eavatar_id, $size );
}

function get_eavatar_image( $user_id = 0, $size = 'full' ) {
	$eavatar_src = get_eavatar_image_url( $user_id, $size );

	if ( ! $eavatar_src ) {
		return;
	}


	return sprintf( '<img src="%s" class="eavatar">', $eavatar_src );
}
