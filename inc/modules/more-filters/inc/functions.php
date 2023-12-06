<?php

/**
 * ENQUEUE
 * loadmore.js
 */
function handler_more_filters_js()
{
    wp_register_script('ensp_ajax_more_filters', get_stylesheet_directory_uri() . '/inc/modules/more-filters/assets/more-filters.js',
        array('jquery'), filemtime(get_stylesheet_directory() . '/inc/modules/more-filters/assets/more-filters.js'), true);

    wp_localize_script('ensp_ajax_more_filters', 'ajaxurl', array('ajaxurl' => admin_url('admin-ajax.php')));

}

add_action('wp_enqueue_scripts', 'handler_more_filters_js', 9999);

function get_ensp_morefilters(
    $atts
    = array(
        'query' => array(
            'post_type' => 'post',
            'posts_per_page' => 12,
            'post_status' => 'publish',
            'order_by' => 'date',
        ),
        'fields' => [
            [
                'id' => 10,
                'field' => 'select',
                'label' => 'Category',
                'labels' => [],
                'taxonomy' => 'category',
                'taxonomy_args' => ['hierarchical' => true],
                'attr' => ['placeholder' => 'Choose one',],
                'class_input' => '',
                'class_wrap' => ''
            ]
        ],
        'class_content' => '',
        'class_items' => 'columns-3',
    )
)
{
    ob_start();
    require(more_filters_path . '/templates/content-initial.php');
    return ob_get_clean();
}


/**
 * HELPERS
 */

function get_param_taxonomies($atts)
{

    $fieldsTaxonomies = [];

    foreach ($atts['fields'] as $field) {
        if (isset($field['taxonomy']) && !empty($field['taxonomy'])) {

            $slug = isset($field['slug']) ? $field['slug'] : $field['taxonomy'];

            if (isset($_GET[$slug]) && !empty($_GET[$slug])) {
                $taxArr = [
                    'taxonomy' => $field['taxonomy'],
                    'terms' => [$_GET[$slug]]
                ];

                array_push($fieldsTaxonomies, $taxArr);
            }
        }
    }


    return $fieldsTaxonomies;
}

function get_param_search($atts)
{
    $search = '';

    foreach ($atts['fields'] as $field) {
        if (isset($field['name']) && $field['name'] == 'search') {
            $slug = isset($field['attr']['data-slug']) ? $field['attr']['data-slug'] : 'search';

            if (isset($_GET[$slug]) && !empty($_GET[$slug])) {
                $search = $_GET[$slug];
                break;
            }
        }
    }

    return $search;
}


function push_search_in_query_args($search, $query_args)
{

    if ($search) {
        $query_args['s'] = urldecode($search);
    }

    return $query_args;
}

/**
 * Add Taxonomies Array to Query Args
 * @model taxonomy_name = [ slug_1, slug_2 ];
 * @param $taxonomies
 * @param $query_args
 * @return array
 */
function push_taxonomies_in_query_args($taxonomies, $query_args)
{
    if ($taxonomies) {
        $query_args['tax_query']['relation'] = 'AND';

        foreach ($taxonomies as $item) {
            if (!isset($item['taxonomy']) || !isset($item['terms'])) {
                continue;
            }

            if (!$item['taxonomy'] || !$item['terms']) {
                continue;
            }

            $query_args['tax_query'][] = array(
                'taxonomy' => $item['taxonomy'],
                'field' => 'term_id',
                'terms' => $item['terms'],
            );
        }
    }

    return $query_args;
}