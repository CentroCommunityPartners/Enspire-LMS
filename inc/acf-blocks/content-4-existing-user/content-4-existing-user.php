<?php

add_action( 'acf/init', function () {
	acf_register_block_type( array(
		'name'            => 'content_for_existing_user',
		'title'           => __( 'Content for Existing User' ),
		'description'     => 'Display content for users who have registered for more than 30 minutes',
		'render_callback' => 'content_for_existing_user_callback',
		'category'        => 'restrict',
		'icon'            => 'lock',
		'keywords'        => array( 'existing user', 'content', 'restrict' ),
		'transform'   => [ 'acf/content_for_new_user' ],
		'supports'        => array(
			'align' => true,
			'mode'  => false,
			'jsx'   => true
		),
	) );
} );

function content_for_existing_user_callback() {
	$user = wp_get_current_user();

	if ( ! $user ) {
		return;
	}

	$user_registered = $user->user_registered;
	$time_diff       = ( strtotime( 'now' ) - strtotime( $user_registered ) );
	$min_30          = 1800;
	$user_first_time = ( $time_diff < $min_30 );

	if ( ! $user_first_time || is_admin() ) {
		echo '<div class="content-existing-user">';
		echo '<InnerBlocks />';
		echo '</div>';
	}
}
