<?php
wp_enqueue_script( 'ajax_simple_loadmore' );

$args          = $atts['query'];
$args['paged'] = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
query_posts( $args );
global $wp_query;
$max_page               = $wp_query->max_num_pages;
$json_args              = $args;
$json_args['max_pages'] = $max_page;
$json_args              = htmlspecialchars( json_encode( $json_args ) );
?>

<div class="simple-loadmore <?php echo( $atts['class_content'] ? $atts['class_content'] : '' ) ?>">
    <div class="simple-loadmore-items <?php echo( $atts['class_items'] ? $atts['class_items'] : '' ) ?>">
		<?php require( simple_loadmore_path . '/templates/loop.php' ); ?>
    </div>
	<?php if ( $max_page > 1 ): ?>
        <a href="#" class="button button-secondary simple-loadmore-button" data-query="<?= $json_args ?>">
			<?php _e( 'Load more', 'enspire' ) ?>
        </a>
	<?php endif; ?>
</div>