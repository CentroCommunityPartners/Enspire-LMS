<?php

add_shortcode('try_free', function () {
    ob_start();
    get_template_part('template-parts/parts/section-try-free');

    return ob_get_clean();
});

add_shortcode('misfit_gear', function () {
    ob_start();
    get_template_part('template-parts/parts/section-misfit-shop');

    return ob_get_clean();
});


add_shortcode('unsubscribe', function () {
    ob_start();
    get_template_part('template-parts/parts/shortcode-unsubscribe');
    return ob_get_clean();
});

add_shortcode('today_wod_link', function () {
    $url = get_the_permalink(get_today_wod_id());
    $url = str_replace('https://', '', $url);
    $url = str_replace('http://', '', $url);
    return $url;
});


add_shortcode('link_subscriptions', function () {
    $user_id = get_current_user_id();
    $profile_url = bp_core_get_user_domain($user_id);
    $url = esc_url($profile_url . 'mp-membership/mp-subscriptions/');
    return sprintf('<a href="%s"> subscription </a>', $url);
});

