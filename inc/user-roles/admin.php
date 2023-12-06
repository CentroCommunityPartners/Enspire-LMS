<?php
/**
 * Admin CAPS
 */

$super_admin     = get_role( 'administrator' );
$cap_admin        = $super_admin->capabilities;

//remove super admin caps
$cap_admin_remove = array(
	'edit_theme_options',
	'update_themes',
	'edit_themes',
	'install_themes',
	'delete_themes',
	'switch_themes',
	'activate_plugins',
	'edit_plugins',
	'update_plugins',
	'install_plugins',
	'delete_plugins',
	'update_core',
);

foreach ( $cap_admin as $key => $value ) {
	if ( in_array( $key, $cap_admin_remove ) ) {
		unset( $cap_admin[ $key ] );
	}
}

$cap_admin_add = array(
	'gravityforms_create_form'      => true,
	'gravityforms_delete_forms'     => true,
	'gravityforms_edit_forms'       => true,
	'gravityforms_preview_forms'    => true,
	'gravityforms_view_entry_notes' => true,
	'gravityforms_edit_entry_notes' => true,
	'gravityforms_export_entries'   => true,
	'gravityforms_view_settings'    => false,
	'gravityforms_edit_settings'    => false,
	'gravityforms_view_updates'     => true,
	'custom_reporting'              => true,
);

$cap_admin = array_merge( $cap_admin, $cap_admin_add );

remove_role( 'admin' );
add_role( 'admin', __( 'Administrator' ), $cap_admin );