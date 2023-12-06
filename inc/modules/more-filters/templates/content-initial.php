<?php
wp_enqueue_script('ensp_ajax_more_filters');

$args = $atts['query'];

$store['paged'] = 1;
$store['query'] = $args;
$store['search'] = '';
$store['taxonomies'] = [];
$store['meta'] = [];

//GET Params
$getTaxonomies = get_param_taxonomies($atts);
$getSearch = get_param_search($atts);

if ($getTaxonomies) {
    $args = push_taxonomies_in_query_args($getTaxonomies, $args);
    $store['taxonomies'] = $getTaxonomies;
}

if ($getSearch) {
    $args = push_search_in_query_args($getSearch, $args);
    $store['search'] = $getSearch;
}


query_posts($args);

global $wp_query;
$max_pages = $wp_query->max_num_pages;

$store['max_pages'] = $max_pages;


$class_content = isset($atts['class_content']) ? $atts['class_content'] : '';
$class_items = isset($atts['class_items']) ? $atts['class_items'] : '';
$class_btn_hide = $max_pages <= 1 ? 'style="display:none"' : '';

?>
<div class="morefilters">

    <div class="ensp-ajax-filters">
        <?php
            $FormFilters = new ESForm($atts['fields']);
            $FormFilters->render();
        ?>
    </div>

    <div class="ensp-ajax-content <?= $class_content ?>" data-store="<?= htmlspecialchars(json_encode($store)) ?>">

        <div class="ensp-ajax-items <?= $class_items ?>">
            <?php require(more_filters_path . '/templates/loop.php'); ?>
        </div>

        <a href="#" class="button button-secondary button-loadmore" <?= $class_btn_hide ?> >
            <?php _e('Load more', 'enspire') ?>
        </a>
    </div>

</div>