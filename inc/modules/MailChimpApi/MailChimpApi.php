<?php
define( 'MAILCHIMP_KEY', '4cfe72d94c3f1b9e52e827359db9d334-us9' );

function MailChimpAddUser( $data ) {
	$memberId = md5( strtolower( $data['email'] ) );
	$server   = substr( MAILCHIMP_KEY, strpos( MAILCHIMP_KEY, '-' ) + 1 );
	$url      = 'https://' . $server . '.api.mailchimp.com/3.0/lists/' . $data['listId'] . '/members/' . $memberId;

	$args = [
		'email_address' => $data['email'],
		'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
	];

	if ( isset( $data['firstname'] ) && isset( $data['lastname'] ) ) {
		$args['merge_fields'] = [
			'FNAME' => $data['firstname'],
			'LNAME' => $data['lastname']
		];
	}

	if ( isset( $data['tags'][0] ) && ! empty( $data['tags'][0] ) ) {
		$args['tags'] = $data['tags'];
	}

	$json = json_encode( $args );

	$ch = curl_init( $url );

	curl_setopt( $ch, CURLOPT_USERPWD, 'user:' . MAILCHIMP_KEY );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, [ 'Content-Type: application/json' ] );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );

	$result   = curl_exec( $ch );
	$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	curl_close( $ch );

	return $httpCode;
}