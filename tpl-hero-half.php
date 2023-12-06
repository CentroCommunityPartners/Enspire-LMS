<?php
/* Template Name: Hero Half */

get_header();
?>

    <div id="primary" class="content-area">
    <main id="main" class="site-main">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
            <div class="container">
                <div class="entry-content">
                    <div class="hero-half">
                        <div class="row">
                            <div class="col col-sm-8">
                                <h1 class="block-title">
									<?php
									the_title();
									?>
                                </h1>
                                <div class="wp-block-image is-style-ratio-16-9">
									<?php echo enspire_post_thumbnail( 'large' ) ?>
                                </div>
                            </div>
                        </div>
                    </div>

					<?php the_content(); ?>
                </div><!-- .entry-content -->
            </div>
		<?php
		endwhile; // End of the loop.
		?>
    </main><!-- #main -->
    </div>

<?php
get_footer();
