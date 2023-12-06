<?php
/**
 * MAP
 *
 * @comment Requirements google map api key
 * @source https://www.advancedcustomfields.com/resources/google-map/
 */

define( 'GMAP_API_KEY', 'AIzaSyC3KpkcGAfSwk1GCSMoNDHDG81ZcUzWMKc' );

add_action( 'acf/init', function () {

	acf_update_setting( 'google_api_key', GMAP_API_KEY );

	acf_register_block_type( array(
		'name'            => 'map',
		'title'           => __( 'Map' ),
		'description'     => '',
		'render_template' => ACF_BLOCKS_PATH . 'map/inc/template.php',
		'category'        => 'formatting',
		'icon'            => 'location',
		'keywords'        => array( 'location', 'map' ),
		'mode'            => false,
		'supports'        => array(
			'align'         => false,
			'align_content' => false,
		),
		'enqueue_assets'  => function () {
			$api_key     = GMAP_API_KEY; // 'AIzaSyC3KpkcGAfSwk1GCSMoNDHDG81ZcUzWMKc
			$name        = 'map';
			$assets      = ACF_BLOCKS_URI . "$name/assets/";
			$assets_time = ACF_BLOCKS_PATH . "$name/assets/";

			wp_enqueue_style( "block-" . $name, $assets . "style.css", array(), filemtime( $assets_time . "style.css" ), false );
			wp_enqueue_script( "block-" . $name, $assets . "script.js", array( 'jquery' ), filemtime( $assets_time . "script.js" ), true );
			wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key, array( 'jquery' ), '', true );
		},
	) );
} );
