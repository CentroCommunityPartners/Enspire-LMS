<?php

add_action( 'acf/init', function () {

	acf_register_block_type( array(
		'name'            => 'iconbox-columns',
		'title'           => __( 'Icon Box Columns' ),
		'description'     => '',
		'render_template' => ACF_BLOCKS_PATH . 'iconbox-columns/inc/template.php',
		'category'        => 'formatting',
		'icon'            => 'lock',
		'keywords'        => array( 'icon', 'iconbox', 'columns icon' ),
		'mode'            => false,
		'supports'        => array(
			'align'         => false,
			'align_content' => false,
		),
		'enqueue_assets'  => function () {
			$name        = 'iconbox-columns';
			$assets      = ACF_BLOCKS_URI . "$name/assets/";
			$assets_time = ACF_BLOCKS_PATH . "$name/assets/";

			wp_enqueue_style( "block-" . $name, $assets . "style.css", array(), filemtime( $assets_time . "style.css" ), false );
		},
	) );

} );
