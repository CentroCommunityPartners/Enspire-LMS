<?php
//ID & Classes
$id = ! empty( $block['anchor'] ) ? $block['anchor'] : 'block-' . $block['id'];
$classes = [ 'block-iconbox-columns', 'block-p' ];
if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}

$iconboxes = get_field( 'iconbox-columns' );
?>

<div id="<?php echo esc_attr( $id ); ?>" class="<?= esc_attr( implode( ' ', $classes ) ); ?>">
    <div class="columns-2">
		<?php foreach ( $iconboxes as $iconbox ):
			$icon = $iconbox['icon'] ? wp_get_attachment_image( $iconbox['icon'], 'thumbnail', true ) : '';
			?>
            <div class="iconbox">
                <div class="iconbox__header">
					<?php echo $iconbox['icon'] ? sprintf( '<div class="iconbox__icon">%s</div>', $icon ) : ''; ?>
					<?php echo $iconbox['title'] ? sprintf( '<h4 class="iconbox__title">%s</h4>', nl2br( $iconbox['title'] ) ) : ''; ?>
                </div>
				<?= $iconbox['description'] ? sprintf( '<div class="iconbox__description">%s</div>', nl2br( $iconbox['description'] ) ) : ''; ?>
            </div>
		<?php endforeach; ?>
    </div>
</div>