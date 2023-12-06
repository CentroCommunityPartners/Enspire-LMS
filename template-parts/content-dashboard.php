<?php
/**
 * BuddyBoss - Users Cover Photo Header
 *
 * @since   BuddyPress 3.0.0
 * @version 3.0.0
 */

if (!function_exists('bp_core_get_user_domain')) {
    echo 'BuddyBoss Plugin Required';

    return;
}

$user_id = get_current_user_id();
$profile_url = bp_core_get_user_domain($user_id);
$avatar = bp_get_displayed_user_avatar(['type' => 'full', 'item_id' => $user_id]);
$avatar_url = $profile_url;

$user_name = bp_core_get_user_displayname($user_id);

?>

<div class="container dashboard">

    <div class="dashboard-header">

        <div class="wp-block-cover flex flex-col items-start" style="min-height:210px">
           
            <div class="wp-block-cover__inner-container">
                <div class="container">
                    <h1 class="page-title">Dashboard</h1>
                </div>
            </div>

        <div class="user-badge user-badge--dashboard flex items-center">
            <div class="user-badge__avatar">
                <a href="<?= $avatar_url ?>"><?= $avatar ?></a>
            </div>
            <div class="user-badge__info">
                <h4 class="user-badge__title">
                    <span class="span-greating">Hello,</span>
                    <span class="span-name"><?= $user_name ?></span>
                </h4>
                <span class="user-badge__subtitle">Good to see you again.</span>
            </div>
        </div>

        </div>


    </div>

    <?php

    $iconListArgs = [
        [
            'title' => 'My Cohorts',
            'icon' => 'bb-icon-users',
            'url' => $profile_url . 'groups/'
        ],
        [
            'title' => 'Settings',
            'icon' => 'bb-icon-sliders-h',
            'url' => $profile_url . 'settings/'
        ],
        [
            'title' => 'Profile',
            'icon' => 'bb-icon-user-badge',
            'url' => $profile_url
        ],

        [
            'title' => 'Resources',
            'icon' => 'bb-icon-attach',
            'url' => get_post_type_archive_link('resources')
        ],
    ];

    get_template_part('template-parts/section/section', 'day-workout');
    get_template_part('template-parts/section/section', 'icon-list-menu', $iconListArgs);
    ?>


    <?php
es_enqueue_style('es-posts');
es_enqueue_style('swiper');
es_enqueue_style('es-swiper');
es_enqueue_script('es-swiper-init');

$args['query'] = [ 
    'post_type' => 'news' 
];

$args['swiper_options'] = [
    'slidesPerView' => 1,
    'spaceBetween' => 30,
    'loop'=> true,
    'loopAdditionalSlides' => 30,
    'navigation' => [
        'nextEl' => '.swiper-button-next',
        'prevEl' => '.swiper-button-prev',
    ],
    'breakpoints' => [
        992 => [
            'slidesPerView' => 3
        ],
    ],
    'observer' => true,
    'slideClass' => 'postcard'
];

$args['block_title'] = 'Latest News';
$args['block_goto'] =  ['url' => 'news-archive' , 'title' => 'Read all news'];
// $args['template'] = 'loop-resources';
$args['uid'] = 'news-slider';
$args['classes'] = 'es-posts-slider-block';
es_template_fragment('slider-posts', $args);
?>


</div>
