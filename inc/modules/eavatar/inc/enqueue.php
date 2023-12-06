<?php
/**
 * ENQUEUE
 */
function handler_eavatar_enqueue() {
	wp_register_style( 'eavatar', EAVATAR_URL . 'assets/eavatar.css' );
	wp_register_script( 'eavatar', EAVATAR_URL . 'assets/eavatar.js', array( 'jquery','fancybox','cropper' ), null,true );

	$evatarOptions = array(
		'ajaxurl'    => admin_url( 'admin-ajax.php' ),
		'maxSize'    => EAVATAR_CROP['max_size'],
		'cropRatio'  => EAVATAR_CROP['ratio'],
		'cropWidth'  => EAVATAR_CROP['width'],
		'cropHeight' => EAVATAR_CROP['height'],
		'user_id'    => get_current_user_id()
	);

	wp_localize_script( 'eavatar', 'evatarOptions', $evatarOptions );


	wp_register_style( 'cropper', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.css' );
	//wp_register_script( 'cropper', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.js', '1.5.11', true );

	wp_register_style( 'fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css' );
	wp_register_script( 'fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
}

add_action( 'wp_enqueue_scripts', 'handler_eavatar_enqueue', 9999 );
