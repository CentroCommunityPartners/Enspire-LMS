<?php
$post_type = isset( $args['post_type'] ) ? $args['post_type'] : get_post_type();
$title     = isset( $args['title'] ) ? $args['title'] : get_queried_object()->labels->name;
?>

<section class="section all-modules container">

    <div class="entry-header">

        <h1 class="entry-title">
			<?= $title; ?>
        </h1>

		<?php ensp_posts_filters( $post_type ); ?>
    </div>

    <div class="row archive-posts">
        <div class="col-lg-12">
            <div class="archive-posts-container">
				<?php
				// the query
				$args  = ensp_ajax_query_args( $post_type );
				$query = new WP_Query( $args );
				?>

				<?php if ( $query->have_posts() ) : ?>

                    <div class="ensp-ajax-posts">
                        <div class="row row-posts">

							<?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                <div class="col-12 col-md-6 col-lg-4">
									<?php echo get_template_part( 'template-parts/loop/loop', $post_type ); ?>
                                </div>
							<?php endwhile; ?>

                        </div>
                    </div>
                    <a href="#" class="btn btn--line" id="ensp_loadmore" style="display: none"> <?php _e( 'Load more', 'enspire-portal' ) ?> </a>

					<?php wp_reset_query();
					wp_reset_postdata(); ?>

				<?php else :
					?>
                    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
				<?php endif; ?>

            </div>
        </div>
    </div>
</section>  <!-- .container -->

<script>
  var max_pages = <?php echo esc_js( $query->max_num_pages ); ?>
</script>


