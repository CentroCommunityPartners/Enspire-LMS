<?php
/**
 * The template for displaying all single posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package enspire
 */

get_header();


?>
    <div id="primary" class="content-area">
        <main id="primary" class="site-main">
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'post' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( ( comments_open() || get_comments_number() ) && is_user_logged_in() ) :
					echo '<div class="container">';
					echo '<div class="comments-container">';
					comments_template();
					echo '</div>';
					echo '</div>';
				endif;
			endwhile; // End of the loop.

			if ( 'article' == get_post_type() ) {
				echo get_related_posts();
			}
			?>
        </main><!-- #main -->
    </div>

<?php

get_footer();
