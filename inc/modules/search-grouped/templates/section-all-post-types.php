<?php
if ( have_posts() ):
	?>
	<section id="search-group-all-post-types" class="search-group search-group-all-post-types">
		<?php /* <h2 class="section-title"></h2> */ ?>
		<div class="search-group-items section-posts-items <?= $items_class ?>">
			<?php
			    require( search_grouped_path . '/templates/loop.php' );
			?>
		</div>
		<?php if($json_query): ?>
			<a href="#" class="button button-secondary button-loadmore" data-query='<?= $json_query ?>'>Load More</a>
		<?php endif; ?>
	</section>
<?php  else: ?>

<?php endif;