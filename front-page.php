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
 * @package enspire
 */
get_header();
?>

     <div id="primary" class="content-area">
        <main id="main" class="site-main mt-remove-main">

            <div class="entry-content">
				<?php
				if ( ! is_user_logged_in() ):
					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile;
				else:
					 get_template_part( 'template-parts/content', 'dashboard' );
				endif;
				?>
            </div>
        </main><!-- #main -->
    </div>
<?php
get_footer();
