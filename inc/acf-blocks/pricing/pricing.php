<?php

add_action( 'acf/init', function () {

	acf_register_block_type( array(
		'name'            => 'pricing',
		'title'           => __( 'Membership Pricing' ),
		'description'     => '',
		'render_template' => ACF_BLOCKS_PATH . 'pricing/inc/template.php',
		'category'        => 'formatting',
		'icon'            => 'lock',
		'keywords'        => array( 'pricing', 'tab', 'membership' ),
		'mode'            => false,
		'supports'        => array(
			'align'         => false,
			'align_content' => false,
		),
		'enqueue_assets'  => function () {
			$name        = 'pricing';
			$assets      = ACF_BLOCKS_URI . "$name/assets/";
			$assets_time = ACF_BLOCKS_PATH . "$name/assets/";

			wp_enqueue_style( "block-" . $name, $assets . "style.css", array(), filemtime( $assets_time . "style.css" ), false );
			wp_enqueue_script( "block-" . $name, $assets . "script.js", array('owl'), filemtime( $assets_time . "script.js" ), true );
		},
	) );

} );
