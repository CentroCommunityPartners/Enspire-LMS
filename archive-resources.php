<?php
/**
 * The template for displaying news archive
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy
 *
 * @package ET_Investments
 */

get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
	        <?php get_template_part( 'inc/modules/loadmore/templates/archive'); ?>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
