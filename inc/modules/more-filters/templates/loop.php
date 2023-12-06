<?php

if ( have_posts() ) :
		while ( have_posts() ): the_post();
            get_template_part( 'template-parts/loop', get_post_type() );
		 endwhile;
         wp_reset_query();
else:
	?>
    <div class="item item-noposts">
        <div class="item-inner">
            <?php echo 'none'; ?>
        </div>
    </div>
<?php
endif;
