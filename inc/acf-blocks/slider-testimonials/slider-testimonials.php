<?php

add_action( 'acf/init', function () {

	acf_register_block_type( array(
		'name'            => 'slider-testimonials',
		'title'           => __( 'Slider Testimonial' ),
		'description'     => '',
		'render_template' => ACF_BLOCKS_PATH . 'slider-testimonials/inc/template.php',
		'category'        => 'formatting',
		'icon'            => 'lock',
		'keywords'        => array( 'slider', 'testimonials' ),
		'mode'            => false,
		'supports'        => array(
			'align'         => false,
			'align_content' => false,
		),
		'enqueue_assets'  => function () {
			$name        = 'slider-testimonials';
			$assets      = ACF_BLOCKS_URI . "$name/assets/";
			$assets_time = ACF_BLOCKS_PATH . "$name/assets/";

			wp_enqueue_style( 'owl' );
			wp_enqueue_script( 'owl' );

			wp_enqueue_style( "block-" . $name, $assets . "style.css", array('owl'), filemtime( $assets_time . "style.css" ), false );
			//wp_enqueue_script( "block-" . $name, $assets . "script.js", array('owl'), filemtime( $assets_time . "script.js" ), true );
		},
	) );

} );
