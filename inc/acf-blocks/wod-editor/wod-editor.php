<?php

add_action( 'acf/init', function () {

	acf_register_block_type( array(
		'name'            => 'before_wod_content',
		'title'           => __( 'WOD Before Content' ),
		'description'     => '',
		'render_callback' => function(){
			echo get_field('before_wod_content');
		},
		'category'        => 'restrict',
		'icon'            => 'lock',
		'keywords'        => array( 'content', 'restrict' ),
		'post_types' => array('post'),
		'mode'  => 'edit',
		'supports'        => array(
			'align' => false,
			'multiple' => false,
			'mode' => 'edit',
		),
	) );

	acf_register_block_type( array(
		'name'            => 'after_wod_content',
		'title'           => __( 'WOD After Content' ),
		'description'     => '',
		'render_callback' => function(){
			echo get_field('after_wod_content');
		},
		'category'        => 'restrict',
		'icon'            => 'lock',
		'keywords'        => array( 'content', 'restrict' ),
		'post_types' => array('post'),
		'mode'  => 'edit',
		'supports'        => array(
			'align' => false,
			'multiple' => false,
			'mode' => 'edit',
		),
	) );

	acf_register_block_type( array(
		'name'            => 'plus_content',
		'title'           => __( 'WOD Content MFT' ),
		'description'     => '',
		'render_callback' => function(){
			echo '<InnerBlocks />';
		},
		'category'        => 'restrict',
		'icon'            => 'lock',
		'keywords'        => array( 'content', 'restrict' ),
		'post_types' => array('post'),
		'supports'        => array(
			'align' => false,
			'mode'  => false,
			'jsx'   => true,
			'multiple' => false,
		),
	) );

	acf_register_block_type( array(
		'name'            => 'garage_content',
		'title'           => __( 'WOD Content Hatchet' ),
		'description'     => '',
		'render_callback' => function(){
			echo '<InnerBlocks />';
		},
		'category'        => 'restrict',
		'icon'            => 'lock',
		'keywords'        => array(  'content', 'restrict' ),
		'post_types' => array('post'),
		'supports'        => array(
			'align' => false,
			'mode'  => false,
			'jsx'   => true,
			'multiple' => false,
		),
	) );

	acf_register_block_type( array(
		'name'            => 'masters_content',
		'title'           => __( 'WOD Content Masters' ),
		'description'     => '',
		'render_callback' => function(){
			echo '<InnerBlocks />';
		},
		'category'        => 'restrict',
		'icon'            => 'lock',
		'keywords'        => array(  'content', 'restrict' ),
		'post_types' => array('post'),
		'supports'        => array(
			'align' => false,
			'mode'  => false,
			'jsx'   => true,
			'multiple' => false,
		),
	) );

	acf_register_block_type( array(
		'name'            => 'team_content',
		'title'           => __( 'WOD Content Teens' ),
		'description'     => '',
		'render_callback' => function(){
			echo '<InnerBlocks />';
		},
		'category'        => 'restrict',
		'icon'            => 'lock',
		'keywords'        => array(  'content', 'restrict' ),
		'post_types' => array('post'),
		'supports'        => array(
			'align' => false,
			'mode'  => false,
			'jsx'   => true,
			'multiple' => false,
		),
	) );

	acf_register_block_type( array(
		'name'            => 'regionals_content',
		'title'           => __( 'WOD Content Anywhere' ),
		'description'     => '',
		'render_callback' => function(){
			echo '<InnerBlocks />';
		},
		'category'        => 'restrict',
		'icon'            => 'lock',
		'keywords'        => array(  'content', 'restrict' ),
		'post_types' => array('post'),
		'supports'        => array(
			'align' => false,
			'mode'  => false,
			'jsx'   => true,
			'multiple' => false,
		),
	) );


} );
