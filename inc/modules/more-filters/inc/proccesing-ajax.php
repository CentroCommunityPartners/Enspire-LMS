<?php
/**
 * AJAX PROCESSING
 */
function ensp_ajax_more_filters_action()
{

    $error = '';
    $posts = '';
    $store = $_POST['store'];
    $query_args = $store['query'];
    $search = $store['search'];
    $taxonomies = $store['taxonomies'];
    $paged = 1;

    //next page (load more)
    if ($_POST['context'] == 'loadmore') {
        $paged = $store['paged'] + 1;
        $query_args['paged'] = $paged;
    }


    if ($taxonomies) {
        $query_args = push_taxonomies_in_query_args($taxonomies, $query_args);
    }

    if ($search) {
        $query_args = push_search_in_query_args($search, $query_args);
    }


    query_posts($query_args);

    global $wp_query;
    $max_pages = $wp_query->max_num_pages;

    ob_start();
    require(more_filters_path . '/templates/loop.php');
    $posts = ob_get_clean();

    $response = array(
        'error' => $error,
        'posts' => $posts,
        'max_pages' => $max_pages,
        'paged' => $paged,
        'args' => $query_args,
    );

    wp_send_json($response);

    die();
}

add_action('wp_ajax_more_filters', 'ensp_ajax_more_filters_action');
add_action('wp_ajax_nopriv_more_filters', 'ensp_ajax_more_filters_action');


/**
 * AJAX PROCESSING
 */
function ensp_ajax_taxonomy_populate_action()
{

    $taxonomy = $_POST['taxonomy'];
    $term = $_POST['term'];
    $term = get_term_by('slug', $term, $taxonomy);
    $term_id = $term->term_id;
    $input = $_POST['input'];
    $default = '';
    $current_key = $_POST['term'];

    ob_start();
    $inputs = get_taxonomy_inputs_values($taxonomy, $term_id);
    include more_filters_path . '/templates/inputs/' . $input . '.php';
    $html = ob_get_clean();

//	$response = array(
//		'html' => $html
//	);

    wp_send_json($html);
    die();
}

add_action('wp_ajax_taxonomy_populate', 'ensp_ajax_taxonomy_populate_action');
add_action('wp_ajax_nopriv_taxonomy_populate', 'ensp_ajax_taxonomy_populate_action');