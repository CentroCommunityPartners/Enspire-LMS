<?php
/**
 * Custom Post Type News
 */

function ensp_init_news() {
	$labels = array(
		'name'               => _x( 'News', 'Post Type General Name', 'enspire_lms' ),
		'singular_name'      => _x( 'News', 'Post Type Singular Name', 'enspire_lms' ),
		'menu_name'          => __( 'News', 'enspire_lms' ),
		'parent_item_colon'  => __( 'Parent News', 'enspire_lms' ),
		'all_items'          => __( 'All News', 'enspire_lms' ),
		'view_item'          => __( 'View News', 'enspire_lms' ),
		'add_new_item'       => __( 'Add News', 'enspire_lms' ),
		'add_new'            => __( 'Add News', 'enspire_lms' ),
		'edit_item'          => __( 'Edit News', 'enspire_lms' ),
		'update_item'        => __( 'Update News', 'enspire_lms' ),
		'search_items'       => __( 'Search', 'enspire_lms' ),
		'not_found'          => __( 'Not Found', 'enspire_lms' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'enspire_lms' ),
	);

	$args = array(
		'label'               => __( 'news', 'enspire_lms' ),
		'description'         => __( 'news', 'enspire_lms' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', ),
		'taxonomies'          => array( 'news_category' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 11,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'menu_icon'           => 'dashicons-welcome-widgets-menus',
		'capability_type'     => 'post',
		'show_in_rest' => true,
	);

	register_post_type( 'news', $args );
}

add_action( 'init', 'ensp_init_news' );