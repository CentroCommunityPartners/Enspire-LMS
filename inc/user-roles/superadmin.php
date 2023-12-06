<?php

function wps_change_role_name() {
	global $wp_roles;
	if ( ! isset( $wp_roles ) ) {
		$wp_roles = new WP_Roles();
	}
	$wp_roles->roles['administrator']['name'] = 'Superadmin';
	$wp_roles->role_names['administrator']    = 'Superadmin';
}

add_action( 'init', 'wps_change_role_name' );