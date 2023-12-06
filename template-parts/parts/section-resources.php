<?php
/**
 * WEAKNESS TEMPLATES
 */

$post_type = 'weakness_templates';
$query     = new WP_Query( array(
	'post_type'      => $post_type,
	'post_status'    => 'publish',
	'order_by'       => 'date',
	'posts_per_page' => 6,
) );

if ( $query->have_posts() ):
	?>
    <section id="search-group-weakness-templates" class="section-posts">
        <h2 class="section-title">Weakness Templates</h2>
        <div class="search-group-items columns-3">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				echo get_template_part( 'template-parts/loop' );
			endwhile;
			?>
        </div>

        <a href="<?= get_post_type_archive_link( $post_type ) ?>" class="button button-secondary button-full button-big"> Go to Weakness Templates</a>
    </section>
<?php endif;
?>

    <section id="search-group-youtube" class="section-youtube-lasts">
        <h2 class="section-title">Videos</h2>
        <div class="search-group-items">
			<?php
			echo do_shortcode( '[youtube-feed  num=6  gridcol=3 layout="grid"]' );
			?>
        </div>

        <a href="<?= home_url( '/videos/' ) ?>" class="button button-secondary button-full button-big"> Go to Videos</a>
    </section>

<?php
/**
 * BLOG
 */

$post_type = 'article';
$query     = new WP_Query( array(
	'post_type'      => $post_type,
	'post_status'    => 'publish',
	'order_by'       => 'date',
	'posts_per_page' => 6,
) );

if ( $query->have_posts() ):
	?>
    <section id="search-group-weakness-templates" class="section-posts">
        <h2 class="section-title">Blog</h2>
        <div class="search-group-items columns-3">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				echo get_template_part( 'template-parts/loop' );
			endwhile;
			?>
        </div>

        <a href="<?= get_post_type_archive_link( $post_type ) ?>" class="button button-secondary button-full button-big"> Go to Blog page</a>
    </section>
<?php endif;
