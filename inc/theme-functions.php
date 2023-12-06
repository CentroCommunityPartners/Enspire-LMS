<?php


/**
 * @return bool
 */
function userHasPaidMembership()
{
    return true; //todo
}


/**
 * Fires after a user is logged-out.
 */
function wp_unsubscribe_and_logout()
{
    wp_destroy_current_session();
    wp_clear_auth_cookie();
    wp_set_current_user(0);
    do_action('wp_logout');
}


function rcp_get_customer_by_user_id()
{
    return false;
}

function rest_get_user_field($user, $field_name, $request)
{
    $userId = $user['id'];
//	if ( $userId ) {
//		$customer   = rcp_get_customer_by_user_id( $userId );
//		$customerId = $customer ? $customer->get_id() : 0;
//		if ( $customerId ) {
//			$gatewayID = rcp_get_customer_gateway_id( $customerId, array( 'stripe', 'stripe_checkout' ) );
//
//			return rcp_user_has_paid_membership( $userId ) && $gatewayID;
//		}
//	}

    return false;
}


function rest_get_user_subscription_status_field($user, $field_name, $request)
{
    $userId = $user['id'];

    if ($userId) {
        $customer = rcp_get_customer_by_user_id($userId);
        $customerId = $customer ? $customer->get_id() : 0;

        if ($customerId) {
            $membership = rcp_get_customer_single_membership($customer->get_id());

            if (!empty($membership)) {
                return $membership->get_status();
            }
        }
    }

    return 'none';
}

/**
 *
 */
if (!function_exists('is_user_subscribed_to_bywb')) {
    function is_user_subscribed_to_bywb()
    {
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $sync_bywb = get_user_meta($user_id, 'sync_bywb', true);

            return $sync_bywb == 'on';
        }

        return false;
    }
}

function ensp_get_avatar_url($user = 0)
{
    $user = $user ? $user : wp_get_current_user();
    $avatar_url = get_stylesheet_directory_uri() . '/inc/modules/eavatar/assets/placeholder.svg';

    if (function_exists('get_eavatar_image_url')) {
        $eavatar = get_eavatar_image_url($user->ID);
        $avatar_url = $eavatar ? $eavatar : $avatar_url;
        unset($eavatar);
    }

    return $avatar_url;
}

function ensp_get_avatar()
{
    $av_url = ensp_get_avatar_url();

    return '<img src="' . $av_url . '" class="eavatar">';
}


function get_query_week_in_advanced()
{
    $now = new DateTime();
    $this_week = array(date("Y-m-d", strtotime('monday this week')), date("Y-m-d", strtotime('sunday this week')));

    // It is past 9 PM on Sunday, then show next week
    if ($now->format('l') === 'Sunday' && $now->format('H') >= '21') {
        $this_week = array(date("Y-m-d", strtotime('monday next week')), date("Y-m-d", strtotime('sunday next week')));
    }

    return array(
        'post_type' => 'post',
        'posts_per_page' => 7,
        'meta_query' => array(
            array(
                'key' => 'wod_date',
                'value' => $this_week,
                'compare' => 'between',
                'type' => 'date'
            ),
        ),
        'meta_key' => 'wod_date',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    );
}


function get_today_wod_id()
{
    global $wpdb;

    $now = new DateTime();
    $today = $now->format('Y-m-d');

    // If is past 9 PM, show next day WOD
    if ( $now->format('H') >= '21' ) {
        $today = date('Y-m-d', strtotime('tomorrow'));
    }

    $SQL_ACF_TODAY = "SELECT $wpdb->posts.id FROM $wpdb->posts
    LEFT JOIN $wpdb->postmeta
    ON $wpdb->postmeta.post_id = $wpdb->posts.ID
    WHERE 1 = 1
    AND $wpdb->posts.post_type = 'post'
    AND $wpdb->posts.post_status = 'publish'
    AND $wpdb->postmeta.meta_key = 'wod_date'
    AND CAST(  $wpdb->postmeta.meta_value as DATE) = '$today'
    LIMIT 1";

    //get post where wod_date = today
    $result = $wpdb->get_var($SQL_ACF_TODAY);

    //get post that are posted today
    if (!$result) {

        $SQL_POST_TODAY = "SELECT SQL_CALC_FOUND_ROWS  $wpdb->posts.ID FROM $wpdb->posts WHERE 1=1
        AND YEAR( $wpdb->posts.post_date ) = YEAR(CURDATE())
        AND MONTH( $wpdb->posts.post_date ) = MONTH(CURDATE())
        AND DAYOFMONTH( $wpdb->posts.post_date ) = DAYOFMONTH( CURDATE()  )
        AND $wpdb->posts.post_type = 'post'
        AND $wpdb->posts.post_status = 'publish'
        LIMIT 1";

        $result = $wpdb->get_var($SQL_POST_TODAY);
    }

    //get last post id
    if (!$result) {
        $SQL = "SELECT $wpdb->posts.id FROM $wpdb->posts WHERE `post_type` = 'post' AND  `post_status` = 'publish'  ORDER BY post_date DESC LIMIT 1";
        $result = $wpdb->get_var($SQL);
    }

    return $result;
}


function get_wod_date($format = 'F j, Y')
{

    $acf_date = get_field('wod_date');

    if ($acf_date) {
        $date = date($format, strtotime($acf_date));
    } else {
        $date = get_the_date($acf_date);
    }

    return $date;

}


/**
 * Display current user role
 */
function get_current_user_role() {
	global $wp_roles;
	$current_user = wp_get_current_user();
	$roles        = $current_user->roles;
	$role         = array_shift( $roles );

	return isset( $wp_roles->role_names[ $role ] ) ? strtolower( translate_user_role( $wp_roles->role_names[ $role ] ) ) : false;
}   