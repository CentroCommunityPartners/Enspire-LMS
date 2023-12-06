<?php
/**
 * AJAX PROCESSING
 */
function ajax_simple_loadmore_action() {

	$query          = $_POST['query'];
	$paged          = $query['paged'] + 1;
	$query['paged'] = $paged;

	query_posts( $query );

	global $wp_query;
	$max_pages = $wp_query->max_num_pages;

	if ( $query['paged'] > $max_pages ) {
		return;
	}

	ob_start();
	require( simple_loadmore_path . '/templates/loop.php' );
	$posts = ob_get_clean();

	$response = array(
		'posts' => $posts,
		'query' => $query,
	);

	wp_send_json( $response );

	die();
}

add_action( 'wp_ajax_simple_loadmore', 'ajax_simple_loadmore_action' );
add_action( 'wp_ajax_nopriv_simple_loadmore', 'ajax_simple_loadmore_action' );