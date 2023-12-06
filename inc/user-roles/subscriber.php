<?php
/**
 * Subscriber has acces only on frontend
 */

$cap_subscriber = array( 'read' => true );
add_role( 'entrepreneur', __( 'Entrepreneur' ), $cap_subscriber );
add_role( 'trainer', __( 'Trainer' ), $cap_subscriber );

function wpse31748_exclude_menu_items( $items, $menu, $args ) {

	if ( in_array( get_current_user_role(), array( 'trainer', 'entrepreneur' ) ) ) {
		foreach ( $items as $key => $item ) {
			if ( $item->post_title === 'Dashboard' ) {
				unset( $items[ $key ] );
			}
		}
	}

	return $items;
}

add_filter( 'wp_get_nav_menu_items', 'wpse31748_exclude_menu_items', null, 3 );