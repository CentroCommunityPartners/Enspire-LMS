<?php

function es_mepr_upme_activate($txn) {
    $memberpress_phone = get_user_meta($txn->user_id, 'mepr_cell_phone_number', true);
    $field_id = 20;
    xprofile_set_field_data($field_id, $txn->user_id, $memberpress_phone);
}
add_action('mepr-signup', 'es_mepr_upme_activate' );


function capture_completed_transaction() {

    $user = MeprUtils::get_currentuserinfo();
    $subscriptions = $user->active_product_subscriptions('ids');


    $membership_id =  19212;
    $url = get_post_meta($membership_id, '_mepr_custom_login_urls_default', true);

       if(in_array( "19212" ,$subscriptions ) && ($GLOBALS['pagenow'] !== 'wp-login.php')){
            wp_redirect( $url );
            exit;
        }

}
add_action('mepr-txn-status-complete', 'capture_completed_transaction');