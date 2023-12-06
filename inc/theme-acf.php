<?php
// Check function exists.
if ( ! function_exists( 'acf_register_block_type' ) ) {
	return;
}

define( 'ACF_BLOCKS_PATH', get_stylesheet_directory() . '/inc/acf-blocks/' );
define( 'ACF_BLOCKS_URI', get_stylesheet_directory_uri() . '/inc/acf-blocks/' );

if ( function_exists( 'require_all_subdir_file' ) ) {
	require_all_subdir_file( ACF_BLOCKS_PATH, 1 );
}


$allBlocks = glob( ACF_BLOCKS_PATH . "/*/*.php" );

if ( $allBlocks ) {
	foreach ( $allBlocks as $block ) {
		require_once $block;
	}
}


/**
 * Block Category
 *
 * @param $categories
 * @param $post
 *
 * @return array
 */
function misfit_block_category( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'restrict',
				'title' => __( 'Restrict', 'misfit' ),
			),
		)
	);
}

add_filter( 'block_categories', 'misfit_block_category', 10, 2 );

/**
 * ACF USER ROLES Dropdown
 */
add_filter('acf/load_field/name=user_roles', 'populateUserRoles');

function populateUserRoles( $field )
{
	//reset choices
	$field['choices'] = array();

	global $wp_roles;
	$roles = $wp_roles->get_names();

	foreach ($roles as $key => $role) {
		$field['choices'][$key] = $role;
	}

	$field['choices'] = array(''=>'All') + $field['choices'];

	return $field;
}