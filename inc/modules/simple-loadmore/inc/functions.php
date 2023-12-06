<?php

/**
 * ENQUEUE
 * loadmore.js
 */
function handler_simple_loadmore_js() {
	wp_register_script( 'ajax_simple_loadmore', get_stylesheet_directory_uri() . '/inc/modules/simple-loadmore/assets/simple-loadmore.js',
		array( 'jquery' ), filemtime( get_stylesheet_directory() . '/inc/modules/simple-loadmore/assets/simple-loadmore.js' ), true );

	wp_localize_script( 'ajax_simple_loadmore', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

}

add_action( 'wp_enqueue_scripts', 'handler_simple_loadmore_js', 9999 );


/**
 * ensp_morefilterss
 */
function get_simple_loadmore(
	$atts
	= array(
		'query'         => array(
			'post_type'      => 'post',
			'posts_per_page' => 12,
			'post_status'    => 'publish',
			'order_by'       => 'date',
		),
		'class_content' => 'columns-3'
	)
) {
	ob_start();
	require( simple_loadmore_path . '/templates/content-initial.php' );
	return ob_get_clean();
}
