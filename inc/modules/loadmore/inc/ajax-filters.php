<?php
/**
 * Ajax Filter Select Radio
 * @param $args
 * @return false|string
 */
function get_filter_fields_radio( $args ) {
	ob_start();

	if ( $args['type'] == 'taxonomy' ) {
		$tax = $args['slug'].'-id';
		$type = $args['type'];
		$data_attribute = "data-type=$type data-taxonomy=$tax";
	} else {
		$type = $args['type'];
		$data_attribute = "data-type=$type";
	}

	?>
    <div class="input-group" <?php echo $data_attribute; ?> >
        <div class="input-group__label">
			<?php _e( $args['label'], 'enspire_portal' ); ?>
        </div>
        <div class="input-group__list">
			<?php foreach ( $args['inputs'] as $input ): ?>
                <div class="input-group__item">
                    <input
                            type="radio"
                            name="<?php echo $args['slug']; ?>"
                            id="<?php echo $input['value']; ?>"
                            value="<?php echo $input['id']; ?>"
                            style="width: 20px"
                    />

                    <label for="<?php echo $input['value']; ?>" style="display: inline-block">
						<?php echo $input['label']; ?>
                    </label>
                    <button class="btn-remove">
                        X
                    </button>
                </div>
			<?php endforeach; ?>
        </div>
    </div>
	<?php
	return ob_get_clean();
}

/**
 * AJAX FILTERS
 */

function get_filter_fields_args_taxonomy( $taxonomy ) {

    $taxonomy_obj       = get_taxonomy( $taxonomy );
	$taxonomy_label = $taxonomy_obj->label;
	$taxonomy_slug  = $taxonomy;

	$terms          = get_terms( array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => true,
	) );

	$inputs = array();

	foreach ( $terms as $term ) {
		$inputs[] = array(
			'id'    => $term->term_id,
			'value' => $term->slug,
			'label' => $term->name
		);
	}

	$args = array(
		'label'  => $taxonomy_label,
		'slug'   => $taxonomy_slug,
		'type'   => 'taxonomy',
		'inputs' => $inputs
	);



	return $args;
}

function ensp_posts_filters($post_type) {

	$filters = '';
    $taxonomies = get_object_taxonomies( $post_type  );

	$args    = array(
		'label'  => __( 'Sort by' ),
		'slug'   => 'order',
		'type'   => 'order',
		'inputs' => array(
			0 => array(
				'id'    => 'asc',
				'value' => 'asc',
				'label' => 'Oldest'
			),
			2 => array(
				'id'    => 'desc',
				'value' => 'desc',
				'label' => 'Newest'
			),
			3 => array(
				'id'    => 'alpha',
				'value' => 'alpha',
				'label' => 'Alphabetical'
			),
			4 => array(
				'id'    => 'popular',
				'value' => 'popular',
				'label' => 'Most commonly used'
			),
		)
	);
	$filters .= get_filter_fields_radio( $args );
	$filters_count = 1; //+1  is sort filter

	foreach ($taxonomies as $taxonomy){

		if( $post_type == 'sfwd-courses'){
			if(!current_user_can('administrator') && in_array( $taxonomy,array( 'program', 'audience' ))) {
				continue;
			}

			if(is_page() && $taxonomy == 'sfwd-courses_module'){
				continue;
			}
        }

		if( $post_type == 'news'){
		    if($taxonomy == 'news_category') continue;
		}

		$args    = get_filter_fields_args_taxonomy( $taxonomy );
		$filters .= get_filter_fields_radio( $args );
		$filters_count++;
	}

	echo "<div class='ensp-posts-filters columns-$filters_count' > $filters </div>";
}