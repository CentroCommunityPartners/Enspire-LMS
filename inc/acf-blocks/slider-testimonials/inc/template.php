<?php
//ID & Classes
$id      = ! empty( $block['anchor'] ) ? $block['anchor'] : 'block-' . $block['id'];
$classes = [ 'block-slider-testimonial' ];
if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}

$block_data  = get_field( 'slider-testimonials' );
$block_title = $block_data['section_title'];
?>


<div id="<?php echo esc_attr( $id ); ?>" class="<?= esc_attr( implode( ' ', $classes ) ); ?>">

	<?= $block_title ? sprintf( ' <h2 class="has-text-align-center block-title">%s</h2>', $block_title ) : '' ?>


    <div class="testimonial-list owl-carousel owl-theme">
		<?php
		foreach ( $block_data['items'] as $item ):
			$text = $item['text'];
			$author_name = $item['author_name'];
			$author_image_id = $item['image'];
			$author_image = $author_image_id ? wp_get_attachment_image( $item['image'], 'large' ) : get_no_image();
			?>
            <div class="testimonial">
                <div class="testimonial__image">
					<?= $author_image ?>
                </div>
                <div class="testimonial__info">
                    <div class="testimonial__text">
						<?= $text ?>
                    </div>
                    <div class="testimonial__author">
						<?= $author_name ?>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>
    </div>

</div>

<script>
  jQuery(document).ready(function () {

    const el = $('.testimonial-list')


    if (el.length) {

      el.owlCarousel({
        loop: true,
        items: 1,
        margin: 0,
        nav: true,
        mouseDrag: true,
        singleItem: true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        navText: ['<i class="icon-arrow-left"></i>', '<i class="icon-arrow-right"></i>'],
        onInitialized: carouselInitialized,
        onChanged: carouselChanged,
      })

      function carouselChanged (event) {
        const el = $(event.target)
        var index = event.item.index
        var current_item = el.find('.owl-item').eq(index)

        // first removing animation for all captions
        $('.testimonial__info').removeClass('animated fadeInUp')
        $('.testimonial__image').removeClass('animated zoomIn')

        current_item.find('.testimonial__info').addClass('animated fadeInUp')
        current_item.find('.testimonial__image').addClass('animated zoomIn')
      }

      function carouselInitialized (event) {
        const el = $(event.target)
        const customNavHTML = '<div class="carousel-nav"><div class="carousel-nav-inner"><div class="carousel-nav-content"></div></div></div>'
        el.append(customNavHTML)
        const elNav = el.find('.carousel-nav-content')

        el.find('.owl-nav').prependTo(elNav)
        el.find('.owl-dots').prependTo(elNav)
      }
    }

  })
</script>