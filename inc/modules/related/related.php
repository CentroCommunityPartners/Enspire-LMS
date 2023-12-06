<?php

function related_posts_query( $post_id ) {


	$post_per_page   = 3;
	$post_type       = get_post_type( $post_id );
	$related_acf_ids = get_field( 'related_ids', $post_id );

	if ( is_array( $related_acf_ids ) ) {
		//ACF QUERY
		$related_args = array(
			'post_type'    => $post_type,
			'post__not_in' => [ $post_id ],
			'post__in'     => $related_acf_ids
		);
		$query        = new WP_Query( $related_args );

	} else {
		//WP QUERY
		$taxonomies = get_object_taxonomies( $post_type );

		$related_args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $post_per_page,
			'post__not_in'   => [ $post_id ],
			'orderby'        => 'rand',
		);


		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( $post_id, $taxonomy );
			if ( ! is_array( $terms ) ) {
				continue;
			}
			$terms = wp_list_pluck( $terms, 'term_id' );

			$related_args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $terms,
				'operator' => 'IN'
			);
		}

		$query        = new WP_Query( $related_args );
		$query_counts = $query->found_posts;

		//if results is 0, remove taxonomies from query
		if ( $query_counts == 0 ) {
			$related_args['tax_query'] = '';
			$query                     = new WP_Query( $related_args );
		} else if ( $query_counts < $post_per_page ) {
			$related_args['tax_query']['relation'] = 'OR';
			$query                                 = new WP_Query( $related_args );
		}
	}

	return $query;
}

function get_related_posts( $label = 'Related Posts', $col = 'columns-3' ) {
	$post_id       = get_the_ID();
	$related_query = related_posts_query( $post_id );
	ob_start();
	if ( $related_query->have_posts() ):
		?>
        <div class="related-posts">
            <div class="container">
                <h2 class="section-title"> <?= $label ?></h2>
                <div class="<?= $col ?>">
					<?php
					while ( $related_query->have_posts() ) : $related_query->the_post();
						echo get_template_part( 'template-parts/loop', get_post_type() );
					endwhile;
					wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
	<?php
	endif;

	return ob_get_clean();
}


