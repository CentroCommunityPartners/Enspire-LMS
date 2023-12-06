<?php
/**
 * The template for displaying search results pages
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package enspire
 */

get_header();
?>

    <div id="primary" class="content-area">
    <main id="main" class="site-main">


        <div class="container">

            <div class="archive-header">
                <h1 class="archive-header__title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search: %s', 'enspire' ), '<span class="search-keywords">' . get_search_query() . '</span>' );
					?>
                </h1>
            </div>

			<?php

			$args = array(
				'query'                 => array(
					'post_type'      => [ 'post','article', 'weakness_templates','page' ],
					'posts_per_page' => 12
				),
				'has_grouped_section'   => true,
				'has_separeted_section' => false,
				'items_class'           => 'columns-3'
			);
			ensp_search_grouped( $args )
            ?>
        </div>

    </main><!-- #main -->
    </div>

<?php
get_footer();
