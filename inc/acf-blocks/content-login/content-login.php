<?php

add_action( 'acf/init', function () {
	acf_register_block_type( array(
		'name'            => 'content_login',
		'title'           => __( 'Content Login' ),
		'description'     => '',
		'render_callback' => 'content_login_callback',
		'category'        => 'restrict',
		'icon'            => 'lock',
		'keywords'        => array( 'login', 'content', 'restrict' ),
		'supports'        => array(
			'align' => true,
			'mode'  => false,
			'jsx'   => true
		),
	) );
} );

function content_login_callback() {
	if ( is_user_logged_in() || is_admin() ) {
		echo '<div class="content-login">';
		echo '<InnerBlocks />';
		echo '</div>';
	}
}