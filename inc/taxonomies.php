<?php

function ensp_custom_taxonomies() {
	$labels_type = array(
		'name'              => __( 'Type', 'enspire_lms' ),
		'singular_name'     => __( 'Type', 'enspire_lms' ),
		'search_items'      => __( 'Search Type', 'enspire_lms' ),
		'all_items'         => __( 'All Types', 'enspire_lms' ),
		'parent_item'       => __( 'Parent Type', 'enspire_lms' ),
		'parent_item_colon' => __( 'Parent Type:', 'enspire_lms' ),
		'edit_item'         => __( 'Edit Type', 'enspire_lms' ),
		'update_item'       => __( 'Update Type', 'enspire_lms' ),
		'add_new_item'      => __( 'Add New Type', 'enspire_lms' ),
		'new_item_name'     => __( 'New Type Name', 'enspire_lms' ),
		'menu_name'         => __( 'Type', 'enspire_lms' ),
	);

	$labels_program = array(
		'name'              => __( 'Program', 'enspire_lms' ),
		'singular_name'     => __( 'Program', 'enspire_lms' ),
		'search_items'      => __( 'Search Program', 'enspire_lms' ),
		'all_items'         => __( 'All Programs', 'enspire_lms' ),
		'parent_item'       => __( 'Parent Program', 'enspire_lms' ),
		'parent_item_colon' => __( 'Parent Program:', 'enspire_lms' ),
		'edit_item'         => __( 'Edit Program', 'enspire_lms' ),
		'update_item'       => __( 'Update Program', 'enspire_lms' ),
		'add_new_item'      => __( 'Add New Program', 'enspire_lms' ),
		'new_item_name'     => __( 'New Program Name', 'enspire_lms' ),
		'menu_name'         => __( 'Program', 'enspire_lms' ),
	);


//Taxonomy Audience
	$labels_audience = array(
		'name'              => __( 'Audience', 'enspire_lms' ),
		'singular_name'     => __( 'Audience', 'enspire_lms' ),
		'search_items'      => __( 'Search Audience', 'enspire_lms' ),
		'all_items'         => __( 'All Audience', 'enspire_lms' ),
		'parent_item'       => __( 'Parent Audience', 'enspire_lms' ),
		'parent_item_colon' => __( 'Parent Audience:', 'enspire_lms' ),
		'edit_item'         => __( 'Edit Audience', 'enspire_lms' ),
		'update_item'       => __( 'Update Audience', 'enspire_lms' ),
		'add_new_item'      => __( 'Add New Audience', 'enspire_lms' ),
		'new_item_name'     => __( 'New Audience Name', 'enspire_lms' ),
		'menu_name'         => __( 'Audience', 'enspire_lms' ),
	);

	$labels_module = array(
		'name'              => __( 'Module', 'enspire_lms' ),
		'singular_name'     => __( 'Module', 'enspire_lms' ),
		'search_items'      => __( 'Search Module', 'enspire_lms' ),
		'all_items'         => __( 'All Modules', 'enspire_lms' ),
		'parent_item'       => __( 'Parent Module', 'enspire_lms' ),
		'parent_item_colon' => __( 'Parent Module:', 'enspire_lms' ),
		'edit_item'         => __( 'Edit Module', 'enspire_lms' ),
		'update_item'       => __( 'Update Module', 'enspire_lms' ),
		'add_new_item'      => __( 'Add New Module', 'enspire_lms' ),
		'new_item_name'     => __( 'New Module Name', 'enspire_lms' ),
		'menu_name'         => __( 'Module', 'enspire_lms' ),
	);


	$labels_language = array(
		'name'              => __( 'Language', 'enspire_lms' ),
		'singular_name'     => __( 'Language', 'enspire_lms' ),
		'search_items'      => __( 'Search Language', 'enspire_lms' ),
		'all_items'         => __( 'All Languages', 'enspire_lms' ),
		'parent_item'       => __( 'Parent Language', 'enspire_lms' ),
		'parent_item_colon' => __( 'Parent Language:', 'enspire_lms' ),
		'edit_item'         => __( 'Edit Language', 'enspire_lms' ),
		'update_item'       => __( 'Update Language', 'enspire_lms' ),
		'add_new_item'      => __( 'Add New Language', 'enspire_lms' ),
		'new_item_name'     => __( 'New Language Name', 'enspire_lms' ),
		'menu_name'         => __( 'Language', 'enspire_lms' ),
	);


	$labels_location = array(
		'name'              => __( 'Location', 'enspire_lms' ),
		'singular_name'     => __( 'Location', 'enspire_lms' ),
		'search_items'      => __( 'Search Location', 'enspire_lms' ),
		'all_items'         => __( 'All Location', 'enspire_lms' ),
		'parent_item'       => __( 'Parent Location', 'enspire_lms' ),
		'parent_item_colon' => __( 'Parent Location:', 'enspire_lms' ),
		'edit_item'         => __( 'Edit Location', 'enspire_lms' ),
		'update_item'       => __( 'Update Location', 'enspire_lms' ),
		'add_new_item'      => __( 'Add New Location', 'enspire_lms' ),
		'new_item_name'     => __( 'New Location Name', 'enspire_lms' ),
		'menu_name'         => __( 'Location', 'enspire_lms' ),
	);

	$args_type = array(
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'labels'            => $labels_type,
		'rewrite'           => array( 'slug' => 'type' ),
		'show_in_rest'      => true,
	);

	$args_program = array(
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'labels'            => $labels_program,
		'rewrite'           => array( 'slug' => 'program' ),
		'show_in_rest'      => true,
	);

	$args_audience = array(
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'labels'            => $labels_audience,
		'rewrite'           => array( 'slug' => 'audience' ),
		'show_in_rest'      => true,
	);

	$args_module = array(
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'labels'            => $labels_module,
		'rewrite'           => array( 'slug' => 'module' ),
		'show_in_rest'      => true,
	);

	$args_language = array(
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'labels'            => $labels_language,
		'rewrite'           => array( 'slug' => 'language' ),
		'show_in_rest'      => true,
	);

	$args_location = array(
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'labels'            => $labels_location,
		'rewrite'           => array( 'slug' => 'language' ),
		'show_in_rest'      => true,
	);

	$args_category = array(
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
	);


	//Taxonomies for multiple post type
	register_taxonomy( 'audience', array( 'news','resources','sfwd-courses' ), $args_audience );
	register_taxonomy( 'program', array( 'news','resources','sfwd-courses' ), $args_program );
	register_taxonomy( 'language', array( 'news','resources','sfwd-courses' ), $args_language );
	register_taxonomy( 'location', array( 'news','sfwd-courses' ), $args_location );

	//Taxonomies for news
	register_taxonomy( 'news_type', array( 'news' ), $args_type );
	//register_taxonomy( 'news_category',  array( 'news' ), $args_category);

	//Taxonomies for resources
	register_taxonomy( 'resources_type', array( 'resources' ), $args_type );

	//Taxonomies for courses
	register_taxonomy( 'sfwd-courses_module', array( 'sfwd-courses' ), $args_module );

}

add_action( 'init', 'ensp_custom_taxonomies' );