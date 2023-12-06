<?php

/**
 * Edit/Remove Menu items
 */
function edit_not_superadmin_menu() {
	global $menu;
	global $submenu;

	$user_role = wp_get_current_user()->roles[0];

	if ( $user_role != 'administrator' ) {

		remove_menu_page( 'wpcf7' );
		remove_menu_page( 'mapsvg-config' );
		remove_menu_page( 'options-general.php' );
		remove_menu_page( 'edit.php?post_type=acf-field-group' );
		remove_menu_page( 'pb_backupbuddy_backup' );
		remove_menu_page( 'wp_file_manager' );
		remove_menu_page( 'wp-user-avatar' );
		remove_menu_page( 'fakerpress' );
		remove_menu_page( '?page=wp-user-avatar' );


		unset( $submenu['index.php'][11] ); //relevansi
		unset( $submenu['index.php'][12] ); //relevansi
	}

	if ( $user_role != 'administrator' ) {
		$submenu = remove_menu_subitems( $submenu, 'index.php', array( 'Admin search' ) );
	}

}

add_action( 'admin_menu', 'edit_not_superadmin_menu', 9002, 1 );


/**
 * Remove Notification Dashboard for low users
 */
add_action( 'admin_enqueue_scripts', 'ds_admin_theme_style' );
add_action( 'login_enqueue_scripts', 'ds_admin_theme_style' );
function ds_admin_theme_style() {
	$user_role = wp_get_current_user()->roles[0];
	if ( $user_role != 'administrator' ) {
		echo '<style>.update-nag, .updated, .error, .is-dismissible {display:none ! important; }</style>';
	}
}
