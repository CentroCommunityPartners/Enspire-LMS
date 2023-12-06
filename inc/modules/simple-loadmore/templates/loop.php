<?php
if ( have_posts() ) :
	while ( have_posts() ): the_post();
		get_template_part( 'template-parts/loop', get_post_type() );
	endwhile;
else:
	get_template_part( 'template-parts/content', 'none' );
endif;
