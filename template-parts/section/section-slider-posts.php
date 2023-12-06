<?php

$defaultQueryArgs = array(
	'post_type'      => 'post',
	'posts_per_page' => 12,
	'orderby'        => 'rand',
);

$title      = isset( $args['title'] ) ? $args['title'] : '';
$buttonText = isset( $args['button_text'] ) ? $args['button_text'] : 'View more';
$buttonLink = isset( $args['button_link'] ) ? $args['button_link'] : '';
$queryArgs  = isset( $args['query_args'] ) ? $args['query_args'] : $defaultQueryArgs;
$query      = new WP_Query( $queryArgs );

if ( $query->have_posts() ):
	?>
    <div class="slider-posts">
		<?= $title ? '<h2 class="block-title">'.$title.'</h2>' : ''; ?>
        <div class="post-items">
			<?php
			while ( $query->have_posts() ) : $query->the_post();
				echo get_template_part( 'template-parts/loop', get_post_type() );
			endwhile;
			wp_reset_postdata(); ?>
        </div>
		<?= $buttonLink ? '<a href="' . $buttonLink . '" class="button button-primary">' . $buttonText . '</a>' : ''; ?>
    </div>
<?php endif;