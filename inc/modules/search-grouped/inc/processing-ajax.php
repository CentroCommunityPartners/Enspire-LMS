<?php
/**
 * AJAX PROCESSING
 */
function ensp_ajax_search_grouped_action() {
	$query     = $_POST['query_args'];
	$paged     = $query['page'];
	$s         = $query['s'];
	$post_type = $query['post_type'];

	$args = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'order_by'       => 'date',
		'posts_per_page' => $query['posts_per_page'],
		'paged'          => $paged,
		's'              => $s
	);

	query_posts( $args );
	global $wp_query;
	$max_pages = $wp_query->max_num_pages;

	ob_start();
	require( search_grouped_path . '/templates/loop.php' );
	$posts = ob_get_clean();
	wp_reset_query();

	$response = array(
		'posts'     => $posts,
		'max_pages' => $max_pages,
	);

	wp_send_json( $response );

	die();

}

add_action( 'wp_ajax_search_grouped', 'ensp_ajax_search_grouped_action' );
add_action( 'wp_ajax_nopriv_search_grouped', 'ensp_ajax_search_grouped_action' );