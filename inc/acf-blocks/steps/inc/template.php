<?php
//ID & Classes
$id = ! empty( $block['anchor'] ) ? $block['anchor'] : 'block-' . $block['id'];
$classes = [ 'wodsteps' ];
if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}

$acf = get_field( 'wodsteps' );

if ( ! is_array( $acf ) ) {
	return;
}


$wodsteps = '';
foreach ( $acf as $step ) {
	$wodsteps .= '<div class="wodstep">';
	$wodsteps .= sprintf( '<h3 class="wodstep__title"">%s</h3>',  $step['title'] );
	$wodsteps .= sprintf( '<div class="wodstep__content">%s</div>', $step['content'] );
	$wodsteps .= '</div>';
}

?>

<div id="<?php echo esc_attr( $id ); ?>" class="<?= esc_attr( implode( ' ', $classes ) ); ?>">

    <div class="wodsteps-viewslider owl-carousel owl-theme-black">
		<?= $wodsteps ?>
    </div>

    <?php if(!is_admin()): ?>
    <div class="wodsteps-viewlist">
        <div class="wodsteps-viewlist__header">
            <a href="#"  aria-hidden="true" class="button button-secondary button-full  button-big toggle-viewlist">Show List View</a>
        </div>

        <div class="wodsteps-viewlist__content" style="display: none">
        </div>
    </div>
    <?php endif; ?>
</div>
