<?php

/**
 * ENQUEUE
 */
function handler_search_grouped_js() {
	wp_register_script( 'ensp_ajax_search_grouped', get_stylesheet_directory_uri() . '/inc/modules/search-grouped/assets/search-grouped.js',
		array( 'jquery' ),
		filemtime( get_stylesheet_directory() . '/inc/modules/search-grouped/assets/search-grouped.js' ), true );
	wp_localize_script( 'ensp_ajax_search_grouped', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
}

add_action( 'wp_enqueue_scripts', 'handler_search_grouped_js', 9999 );

