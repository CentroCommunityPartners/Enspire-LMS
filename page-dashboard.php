<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BuddyBoss_Theme
 */

get_header();
?>


    <main id="main" class="site-main mt-0">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'dashboard' );
		endwhile; // End of the loop.
		?>
    </main><!-- #main -->


<?php
get_footer();
