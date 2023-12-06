<?php
/**
 * Custom Post Type News
 */

function ensp_init_resources() {
	$labels = array(
		'name'               => _x( 'Resources', 'Post Type General Name', 'enspire_lms' ),
		'singular_name'      => _x( 'Resource', 'Post Type Singular Name', 'enspire_lms' ),
		'menu_name'          => __( 'Resources', 'enspire_lms' ),
		'parent_item_colon'  => __( 'Parent Resource', 'enspire_lms' ),
		'all_items'          => __( 'All Resources', 'enspire_lms' ),
		'view_item'          => __( 'View Resources', 'enspire_lms' ),
		'add_new_item'       => __( 'Add New Resource', 'enspire_lms' ),
		'add_new'            => __( 'Add New', 'enspire_lms' ),
		'edit_item'          => __( 'Edit Resource', 'enspire_lms' ),
		'update_item'        => __( 'Update Resource', 'enspire_lms' ),
		'search_items'       => __( 'Search', 'enspire_lms' ),
		'not_found'          => __( 'Not Found', 'enspire_lms' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'enspire_lms' ),
	);


	$args = array(
		'label'               => __( 'Resources', 'enspire_lms' ),
		'description'         => __( 'Resources', 'enspire_lms' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_rest'        => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 10,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'menu_icon'           => 'dashicons-media-document'
	);

	register_post_type( 'resources', $args );

}

add_action( 'init', 'ensp_init_resources' );


