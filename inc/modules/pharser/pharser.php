<?php
define( 'PARSER_LOG_DIR',  get_stylesheet_directory() . "/log/parser/" );

define( 'PARSER', array(
	'name'      => 'Centro',
	'slug'      => 'centro',
	'feed'      => 'https://www.centrocommunity.org/blog/?format=json-pretty', // https://blog.alta.org/atom.xml
	'post_type' => 'news',
	'taxonomy'  => 'news_type', //category
	'main_term' => '' //main category //Community Blog
) );



include 'functions.php';
include 'page-admin.php';

if ( ! function_exists( 'parser_feed_updated' ) ):
	function parser_feed_updated( $feedUpdated ) {
		$optionUpdated   = update_option( PARSER['slug'] . '_feed_updated', $feedUpdated );
		$labelUpdated    = sprintf( __( 'New %s feed updated time stored.' ), PARSER['name'] );
		$labelNotUpdated = sprintf( __( '%s feed updated time not changed.' ), PARSER['name'] );

		es_write_parser_log( $optionUpdated ? $labelUpdated : $labelNotUpdated, 'info' );
	}
endif;
add_action( 'parser_before_parsing_entries', 'parser_feed_updated' );



/***********************************************************************
 *                              CRON                                   *
 **********************************************************************/
function es_run_parser_squarespace_callback() {
    $parserStatus = get_option( PARSER['slug'] . '_parser_status', 'idle' );

    if ( 'running' === $parserStatus ) {
        es_write_parser_log( __( 'Parser status is "running" => aborting parsing job' ), 'warning' );
    } else if ( 'idle' === $parserStatus ) {
        es_write_parser_log( __( '===>>> Parser status is "idle" => safe to start parsing' ), 'debug' );
        es_insert_parsed_posts();
    } else {
        es_write_parser_log( sprintf( __( 'Parser status unknown. This is an unlikely scenario. Status: "%s"' ), $parserStatus ), 'error' );
    }

    es_write_parser_log( __( '===>>> Parsing and entry creation process finished' ), 'debug' );
    update_option( PARSER['slug'] . '_parser_status', 'idle' );
}

add_action( 'run_parser_squarespace', 'es_run_parser_squarespace_callback' );

// Schedule Cron Job Event
function es_setup_parser_squarespace_cron() {
    if ( ! wp_next_scheduled( 'run_parser_squarespace' ) ) {
        wp_schedule_event( time(), 'daily', 'run_parser_squarespace' );
    }
}
add_action( 'wp', 'es_setup_parser_squarespace_cron' );


// function parsing_now_posts() {
// 	if ( ! is_admin() ) {
// 		update_option( PARSER['slug'] . '_feed_hash', '');
// 		es_insert_parsed_posts();
// 	}
// }
//
// add_action( 'init', 'parsing_now_posts' );