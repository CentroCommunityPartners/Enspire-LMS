<?php

function mfgym_set_terms() {
	$args = array(
		'posts_per_page' => - 1,
		'post_type'      => 'mf-gym'
	);

	$posts = get_posts( $args );

	foreach ( $posts as $post ) {
		$post_id = $post->ID;
		$country = get_post_meta( $post_id, 'country' )[0];
		$state   = get_post_meta( $post_id, 'state' )[0];

		$parent_exist = term_exists( $country, 'gym-location' );


		if ( ! $parent_exist ) {
			wp_insert_term(
				$country,   // the term
				'gym-location' // the taxonomy
			);
		} else {
			$child_exist = term_exists( $state, 'gym-location' );
			$parent_term_id = $parent_exist['term_id'];

			if ( ! $child_exist ) {

				wp_insert_term(
					$state,   // the term
					'gym-location', // the taxonomy
					array(
						'parent' => $parent_term_id,
					)
				);
			}else{
				$child_term_id  = $child_exist['term_id'];
				wp_set_post_terms( $post_id, array( $parent_term_id, $child_term_id ), 'gym-location' );
			}
		}
	}
}


add_action( 'init', function () {
	//mfgym_set_terms();
} );