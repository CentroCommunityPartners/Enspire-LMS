<?php
/**
 * CPT Resources
 */

$resource        = get_field( 'es_resources' );
$resource = $resource ? $resource[0] : '';
$resource_html = '';

if($resource){
	$resource_layout =  isset($resource['acf_fc_layout']) ? $resource['acf_fc_layout'] : [];
	if ( $resource_layout == 'video' ) {
		$video_mp4    = $resource['mp4_video'];
		$video_webm   = $resource['webm_video'];
		$video_flv    = $resource['flv_video'];
		$video_poster = $resource['poster_image']['sizes']['large'];

		$attr = array(
			'mp4'      => $video_mp4,
			'webm'     => $video_webm,
			'flv'      => $video_flv,
			'poster'   => $video_poster,
			'preload'  => 'auto',
			'width'    => '640',
			'loop'     => '',
			'autoplay' => '',
		);

		$video = wp_video_shortcode( $attr );
	} elseif ( $resource_layout == 'external_video' ) {
		$video = $resource['input'];
	} else{
		$target = '';
		$filesize = '';

		if($resource_layout == 'document'){
			$icon = 'bb-icon-attach';
			$title    = $resource['input']['title'];
			$url  = $resource['input']['url'];
			$filesize = size_format( $resource['input']['filesize'], 0 );
			$filesize = sprintf('<span class="material__size">%s</span>', $filesize);
		} elseif ('link'){
			$icon = 'bb-icon-link';
			$url  = $resource['input']['url'];
			$title = $resource['input']['title'];
			$target = $resource['input']['target'];
		}

		ob_start();
		?>
        <div class="ld-table-list-item material__item material__item--file mt-12">
            <a class="ld-table-list-item-preview border p-3" href="<?= $url ?>" target="<?= $target ?>">
                <span class="material__icon <?= $icon ?>"></span>
                <span class="material__title"><?= $title ?></span>
				<?= $filesize ?>
            </a>
        </div>
		<?php
		$resource_html = ob_get_clean();


	}
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post grid md:grid-cols-12 xs:grid-cols-1 md:gap-12 py-12' ); ?>>
    <main class="content xs:col-span-12 md:col-span-8">

		<?php if ( ! empty( $video ) ): ?>
            <div class="post_video">
                <div class="post_video-container">
					<?= $video ?>
                </div>
            </div>
		<?php endif; ?>

        <div class="post__time">
            Posted:
            <time class="posted-time" datetime="<?php the_time( 'Y-m-d\TH:i:s' ); ?>">
				<?php echo get_the_time( 'm/d/Y' ); ?>
            </time>
        </div>
        <h1 class="post__title entry-title"><?php the_title(); ?></h1>

        <div class="post__content">
			<?php the_content() ?>
        </div>

		<?= $resource_html ?>

    </main>
	<aside class="xs:col-span-12 md:col-span-4">

	<?php
	$related_query = related_posts_query( get_the_ID() );

	if ( $related_query->have_posts() ):
		?>
		<div class="widget-areaa">
			<div class="related-posts">
				<div class="roww">
					<div class="">
						<h3 class="section-title"> <?php _e( 'Related', 'enspire_lms' ) ?> </h3>
					</div>
				</div>
				<div class="row related-posts-list">
					<?php
					while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
						<div class="">
							<?php echo get_template_part( 'template-parts/loop/loop', get_post_type() ); ?>
						</div>
					<?php endwhile;
					wp_reset_postdata(); ?>
				</div>
			</div>
		</div><!-- #secondary -->
	<?php
	endif;
	?>
	</aside>
</article>






