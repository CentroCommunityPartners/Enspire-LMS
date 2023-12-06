<?php
/**
 * @Module eAvatar
 */

define( 'EAVATAR_PATH', dirname( __FILE__ ) );
define( 'EAVATAR_URL', get_stylesheet_directory_uri() . '/inc/modules/eavatar/' );

define( 'EAVATAR_CROP', array(
	'max_size' => 9999, // in MB
	'ratio'    => '3/4',
	'width'    => '380',
	'height'   => '505'
) );

include 'inc/enqueue.php';
include 'inc/helpers.php';
include 'inc/proccesing-ajax.php';


add_shortcode( 'eavatar', function () {

	if ( is_admin() ) {
		return;
	}

	ob_start();

	include EAVATAR_PATH . '/templates/form.php';

	wp_enqueue_style( 'cropper' );
	wp_enqueue_script( 'cropper' );

	wp_enqueue_style( 'fancybox' );
	wp_enqueue_script( 'fancybox' );

	wp_enqueue_style( 'eavatar' );
	wp_enqueue_script( 'eavatar' );

	return ob_get_clean();

} );
