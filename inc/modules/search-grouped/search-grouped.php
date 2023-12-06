<?php
define( 'search_grouped_path', dirname( __FILE__ ) );

include 'inc/functions.php';
include 'inc/processing-ajax.php';

function ensp_search_grouped(
	$settings
	= array(
		'query'                 => array(
			'post_type'      => array( 'post', 'page' ),
			'posts_per_page' => 12
		),
		'has_grouped_section'   => true,
		'has_separeted_section' => false,
		'items_class'           => 'columns-3'
	)
) {


	if ( ! is_array( $settings['query']['post_type'] ) ) {
		return;
	}


	$post_types     = $settings['query']['post_type'];
	$posts_per_page = isset( $settings['query']['posts_per_page'] ) ? $settings['query']['posts_per_page'] : 12;

	if ( is_search() ) {
		$s = get_search_query();
	} else {
		$s = (isset( $_GET['search'] ) ? $_GET['search'] : '');
	}

	$items_class = isset( $settings['items_class'] ) ? $settings['items_class'] : '';

	wp_enqueue_script( 'ensp_ajax_search_grouped' );


	include( search_grouped_path . '/templates/filters.php' );


	if ( $settings['has_grouped_section'] ) {
		$args = array(
			'post_type'      => $post_types,
			'post_status'    => 'publish',
			'order_by'       => 'date',
			'posts_per_page' => $posts_per_page,
			's'              => $s
		);

		query_posts( $args );

		global $wp_query;
		$max_pages = $wp_query->max_num_pages;


		$json_query = json_encode( array(
			'post_type'      => $post_types,
			'posts_per_page' => $posts_per_page,
			's'              => $s,
			'page'           => 2,
			'max_page'       => $max_pages
		) );


		include( search_grouped_path . '/templates/section-all-post-types.php' );

		wp_reset_query();
	}

	if ( $settings['has_separeted_section'] ) {
		foreach ( $post_types as $post_type ) {

			$args = array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'order_by'       => 'date',
				'posts_per_page' => $posts_per_page,
				's'              => $s
			);

			query_posts( $args );

			global $wp_query;
			$max_pages = $wp_query->max_num_pages;


			$json_query = json_encode( array(
				'post_type'      => $post_type,
				'posts_per_page' => $posts_per_page,
				's'              => $s,
				'page'           => 2,
				'max_page'       => $max_pages
			) );


			include( search_grouped_path . '/templates/section-one-post-type.php' );

			wp_reset_query();
		}
	}
}

