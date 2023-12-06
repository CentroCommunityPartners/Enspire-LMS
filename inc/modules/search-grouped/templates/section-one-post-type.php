<?php
if ( have_posts() ):
	$post_type_obj = get_post_type_object( $post_type );
	?>
	<section id="section-posts-<?= $post_type ?>" class="search-group section-posts">
		<h2 class="section-title"><?= $post_type_obj->labels->name ?></h2>
		<div class="search-group-items section-posts-items <?= $items_class ?>">
			<?php
			require( search_grouped_path . '/templates/loop.php' );
			?>
		</div>
		<?php if($json_query): ?>
			<a href="#" class="button button-secondary button-loadmore" data-query='<?= $json_query ?>'>Load More</a>
		<?php endif; ?>
	</section>
<?php endif;