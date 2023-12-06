<?php
if (!defined('_S_VERSION')) {
    define('_S_VERSION', '1.0.0');
}


define('THEME_DIRECTORY', get_stylesheet_directory());
define('THEME_URI', get_stylesheet_directory_uri());


include 'inc/helpers.php';
include 'inc/theme-setup.php';
include 'inc/theme-enqueue.php';
include 'inc/theme-functions.php';
include 'inc/theme-tags.php';
include 'inc/theme-filters.php';
include 'inc/theme-actions.php';
include 'inc/theme-acf.php';
include 'inc/taxonomies.php';
include 'inc/user-roles.php';

include 'inc/modules/search-grouped/search-grouped.php';
include 'inc/modules/related/related.php';
include 'inc/modules/resources/resources.php';
include 'inc/modules/news/news.php';
include 'inc/modules/loadmore/loadmore.php';
include 'inc/modules/pharser/pharser.php';

include 'inc/buddyboss/buddyboss-helpers.php';
include 'inc/buddyboss/buddyboss-actions.php';

include 'inc/memberpress/memberpress-helpers.php';
include 'inc/memberpress/memberpress-actions.php';

include 'inc/widgets/widget-download-app.php';
include 'inc/widgets/widget-socials.php';

include 'inc/learndash/ld-actions.php';



/**
 * Register new tabs for profile page.
 */

// Subscription History Tab Start
function profile_subscription_nav_item()
{

    global $bp;

    bp_core_new_nav_item(
        array(
            'name' => 'Subscription',
            'slug' => 'subscription',
            'screen_function' => 'subscription_tab_main'
        )
    );
}

add_action('bp_setup_nav', 'profile_subscription_nav_item', 10);

function subscription_tab_main()
{
    add_action('bp_template_content', 'bp_template_subscription_main_function');
    bp_core_load_template('template_content');
}

function bp_template_subscription_main_function()
{
    if (is_user_logged_in()) {
        $user_ID = get_current_user_id();

        global $wpdb;
        $tablename = $wpdb->prefix . "mepr_subscriptions";

        // get all user subscription data from database for specific user
        $query = $wpdb->get_results("SELECT * FROM $tablename WHERE user_id = " . $user_ID);

        // echo '<pre>'; print_r($query); echo '<pre>';
        ?>
        <h3>Subscription</h3>
        <table class="subscription_datatable">
            <thead>
            <tr>
                <th>Membership</th>
                <th>Subscription</th>
                <th>Active</th>
                <th>Created</th>
                <th>Expires</th>
                <th>Card Exp.</th>
            </tr>
            </thead>
            <tbody>
            <?php

            // loop to display all data in table formate
            foreach ($query as $membership) {
                ?>
                <tr>
                    <?php
                    $pid = $membership->product_id;

                    echo '<td data-label="Membership"><strong>' . $membership_title = get_the_title($pid) . '</strong></td>';
                    echo '<td data-label="Subscription"><strong>$' . $membership->price . '/' . $membership->period_type . '</strong></td>';

                    $statusval = $membership->status;
                    if ($statusval == 'active') {
                        $status = "Yes";
                    } else {
                        $status = "No";
                    }
                    echo '<td data-label="Active"><span>' . $status . '</span></td>';
                    $newDate = date("F d, Y", strtotime($membership->created_at));
                    echo '<td data-label="Created">' . $newDate . '</td>';
                    echo '<td data-label="Expires">' . do_shortcode('[mepr-list-subscriptions]') . '</td>';
                    echo '<td data-label="Card Exp."></td>';
                    ?>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    } else {
        wp_login_form(array('echo' => true));
    }
}

// Subscription History Tab End


// Billing History Tab Start
function profile_billing_nav_item()
{

    global $bp;

    bp_core_new_nav_item(
        array(
            'name' => 'Billing History',
            'slug' => 'billing-history',
            'screen_function' => 'billing_tab_main'
        )
    );
}

add_action('bp_setup_nav', 'profile_billing_nav_item', 10);

function billing_tab_main()
{
    add_action('bp_template_content', 'bp_template_billing_main_function');
    bp_core_load_template('template_content');
}


// My Downloads History Tab Start
function profile_new_nav_item()
{

    global $bp;

    bp_core_new_nav_item(
        array(
            'name' => 'Downloads History',
            'slug' => 'downloads-history',
            'screen_function' => 'view_manage_tab_main'
        )
    );
}

add_action('bp_setup_nav', 'profile_new_nav_item', 10);

function view_manage_tab_main()
{
    add_action('bp_template_content', 'bp_template_content_main_function');
    bp_core_load_template('template_content');
}

function bp_template_content_main_function()
{
    if (is_user_logged_in()) {
        //Add shortcode to display content in sub tab
        echo '<div class="download_his"><h3>Downloads</h3>' . do_shortcode('[mpdl-file-links]' . '</div>');
        //echo do_shortcode('[[downloads members_only="true"]]');

    } else {
        wp_login_form(array('echo' => true));
    }
}

// My Downloads History Tab End

function salesforce_auth_url( $url ) {

	return 'https://test.salesforce.com/services/oauth2/token';

}

add_filter( 'wpf_salesforce_auth_url', 'salesforce_auth_url' );
// Edit Account Tab Start
function profile_edit_account_nav_item()
{

    global $bp;
    $user_id = $_REQUEST['my_id'];
    $camp_link = get_site_url() . "/campaign-detail/?id=" . $user_id;
    // echo "<p class='campaign_store_name'><a href='".$camp_link."'>".$camp_title."</a></p>";
    // $arform_id = Get_ARFORM_ID_using_slug($camp_link);

    bp_core_new_nav_item(
        array(
            'name' => 'Edit Account',
            'slug' => 'profile/edit/',
            'screen_function' => 'edit_account_tab_main'
        )
    );
}

add_action('bp_setup_nav', 'profile_edit_account_nav_item', 10);

function edit_account_tab_main()
{
    add_action('bp_template_content', 'bp_template_edit_account_main_function');
    bp_core_load_template('template_content');
}

function bp_template_edit_account_main_function()
{
    if (is_user_logged_in()) {
        //Add shortcode to display content in sub tab
        echo do_shortcode('[wpdm_all_packages]');
    } else {
        wp_login_form(array('echo' => true));
    }
}



// function redirect_to_profile( $redirect_to_calculated, $redirect_url_specified, $user ) { 
//  if ( ! $user || is_wp_error( $user ) ) { 
//  return $redirect_to_calculated; 
//  } 

//  // if the user is not site admin, redirect to his/her profile. 
//  if ( function_exists( 'bp_core_get_user_domain' )  ) { 
//  return bp_core_get_user_domain( $user->ID ); 
//  } 

// } 
// add_filter( 'login_redirect', 'redirect_to_profile', 100, 3 );

