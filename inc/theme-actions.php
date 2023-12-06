<?php
//add_action( 'admin_init', 'misfit_admin_init', 1 );
//add_action( 'template_include', 'misfit_template_include' );

// Remove the 'super socializer' avatar options.
//remove_action( 'show_user_profile', 'the_champ_show_avatar_option' );

//filter users by course section
//add_action( 'restrict_manage_users', 'add_course_section_filter' );
add_filter( 'pre_get_users', 'filter_users_by_course_section' );

//Rest Api
add_action( 'rest_api_init', 'customer_subscription_add_user_data' );







function add_course_section_filter( $which ) {

	// create sprintf templates for <select> and <option>s
	$st = '<select name="meta_filter_%s" style="float:none;"><option value="">%s</option>%s</select>';
	$ot = '<option value="%s" %s>%s</option>';

	// determine which filter button was clicked, if any and set section
	$button  = key( array_filter( $_GET, function ( $v ) {
		return __( 'Filter' ) === $v;
	} ) );
	$section = $_GET[ 'meta_filter_' . $button ] ?? - 1;

	// generate <option> and <select> code
	$options = implode( '', array_map( function ( $i ) use ( $ot, $section ) {
		return sprintf( $ot, $i, selected( $i, $section, false ), $i );
	}, array( 'paused', 'unsubscribed' ) ) );
	$select  = sprintf( $st, $which, __( 'Meta Filters' ), $options );

	// output <select> and submit button
	echo $select;
	submit_button( __( 'Filter' ), null, $which, false );
}

function filter_users_by_course_section( $query ) {
	global $pagenow;
	if ( is_admin() && 'users.php' == $pagenow ) {
		$button = key( array_filter( $_GET, function ( $v ) {
			return __( 'Filter' ) === $v;
		} ) );
		if ( $section = $_GET[ 'meta_filter_' . $button ] ) {
			$meta_query = [ [ 'key' => $section, 'value' => 1, 'compare' => 'LIKE' ] ];
			$query->set( 'meta_key', $section );
			$query->set( 'meta_query', $meta_query );
		}
	}
}



function customer_subscription_add_user_data() {
	register_rest_field( 'user',
		'wordpress_subscription',
		array(
			'get_callback'    => 'rest_get_user_field',
			'update_callback' => null,
			'schema'          => null,
		)
	);

	register_rest_field( 'user',
		'subscription_status',
		array(
			'get_callback'    => 'rest_get_user_subscription_status_field',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}