<?php

add_action( 'acf/init', function () {
	acf_register_block_type( array(
		'name'            => 'content_logout',
		'title'           => __( 'Content Logout' ),
		'description'     => '',
		'render_callback' => 'content_logout_callback',
		'category'        => 'restrict',
		'icon'            => 'unlock',
		'keywords'        => array( 'logout', 'content', 'restrict' ),
		'supports'        => array(
			'align' => true,
			'mode'  => false,
			'jsx'   => true
		),
	) );
} );

function content_logout_callback() {
	if ( ! is_user_logged_in() || is_admin() ) {
		echo '<div class="content-logout">';
		echo '<InnerBlocks />';
		echo '</div>';
	}
}