<?php

function my_dump( $obj ) {
	?>
    <pre style="color: black; font-size: 13px">
		<?php print_r( $obj ) ?>
	</pre>
	<?php
}


/**
 * ENQUEUE
 * loadmore.js
 */
function handler_loadmore_js() {
	wp_enqueue_script( 'ensp_ajax_loadmore', get_stylesheet_directory_uri() . '/inc/modules/loadmore/assets/loadmore.js', array( 'jquery' ),
		filemtime( get_stylesheet_directory() . '/inc/modules/loadmore/assets/loadmore.js' ), true );

	if ( is_page_template( 'tpl-modules.php' ) || is_page_template( 'tpl-cohorts.php' ) ) {
		$post_type = 'sfwd-courses';
		$module    = json_encode( get_field( 'module', get_the_id() ) );
	} else {
		$post_type = get_post_type();
	}

	wp_localize_script( 'ensp_ajax_loadmore', 'ajax_object',
		array(
			'ajax_url'  => admin_url( 'admin-ajax.php' ),
			'post_type' => $post_type,
			'module'    => isset($module) ? $module : ''
		)
	);

}

add_action( 'wp_enqueue_scripts', 'handler_loadmore_js', 9999 );


/**
 * Query Args for Ajax
 *
 * @return array
 */
function ensp_ajax_query_args( $post_type ) {
	$paged     = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$ord       = $_GET['ord'];
	$args = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'order_by'       => 'date',
		'order'          => $ord, //DESC | ASC
		'posts_per_page' => 12,
		'paged'          => $paged,
	);

	//Order
	switch ($ord) {
		case 'DESC':
		case '':
			$args['order'] = 'DESC';
			break;
		case 'ASC':
			$args['order'] = 'ASC';
			break;
		case 'popular':
			$args['meta_key'] = 'ensp_views_count';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
			break;
		case 'alpha':
			$args['orderby'] = 'title';
			$args['order'] = 'DESC';
			break;
	}


	//Taxonomies
	$get_tax_terms = array(
		'audience' => $_GET['audience-id'],
		'program' => $_GET['program-id'],
		'language' => $_GET['language-id'],
		'location' => $_GET['location-id'],
		'sfwd-courses_module' => $_GET['module-id'],
	);

	foreach ($get_tax_terms as $tax => $term_id){
		if ( ! empty( $term ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => $tax,
				'field'    => 'term_id',
				'terms'    => array( $term_id ),
			);
		}
	}

	$type = $_GET['type-id'];
	if ( ! empty( $type ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => $post_type . '_type',
			'field'    => 'term_id',
			'terms'    => array( $type ),
		);
	}

	//Template Modules
	if ( is_page_template( 'tpl-modules.php' ) ) {

		$args['tax_query'][] = array(
			'taxonomy' => 'sfwd-courses_module',
			'field'    => 'term_id',
			'terms'    => get_field( 'module', get_the_id() ),
		);

		$user_id = get_current_user_id();

		if($user_id){
			$user_all_groups = get_learndash_user_groups( $user_id );
			$group_courses_ids = get_learndash_groups_courses_ids( $user_id, $user_all_groups );
			$args['post__in'] = $group_courses_ids;
		}
	}

	return $args;

}


/**
 * AJAX PROCESSING
 */
function ensp_ajax_load_posts_action() {
	$query     = $_POST['query'];
	$paged     = $_POST['page'];
	$post_type = $_POST['post_type'];
	$modules   = json_decode( $query['module'] );
	$ord       = $query['order'];
	$args = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'order_by'       => 'date',
		'posts_per_page' => 12,
		'paged'          => $paged + 1
	);

	//Order
	switch ($ord) {
		case 'DESC':
		case '':
			$args['order'] = 'DESC';
			break;
		case 'ASC':
			$args['order'] = 'ASC';
			break;
		case 'popular':
			$args['meta_key'] = 'ensp_views_count';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
			break;
		case 'alpha':
			$args['orderby'] = 'title';
			$args['order'] = 'DESC';
			break;
	}


	foreach ( $query['taxonomies'] as $taxonomy => $term_id ) {
		$args['tax_query'][] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'term_id',
			'terms'    => array( $term_id ),
		);
	}

	if ( ! empty( $modules ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'sfwd-courses_module',
			'field'    => 'term_id',
			'terms'    => $modules,
		);
	}

	// обычно лучше использовать WP_Query, но не здесь
	query_posts( $args );
	global $wp_query;
	$max_pages = $wp_query->max_num_pages;


	if ( have_posts() ) :
		ob_start();

		while ( have_posts() ): the_post();
			?>
            <div class="col-12 col-md-6 col-lg-4">
				<?php echo get_template_part( 'template-parts/loop/loop', $post_type ); ?>
            </div>
		<?php endwhile;
		$posts = ob_get_clean();
		?>
	<?php endif;

	$response = array(
		'posts'     => $posts,
		'max_pages' => $max_pages
	);
	wp_send_json( $response );

	die();
}

add_action( 'wp_ajax_loadmore', 'ensp_ajax_load_posts_action' );
add_action( 'wp_ajax_nopriv_loadmore', 'ensp_ajax_load_posts_action' );

