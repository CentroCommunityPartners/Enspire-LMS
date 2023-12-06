<?php
//ID & Classes
$id = ! empty( $block['anchor'] ) ? $block['anchor'] : 'block-' . $block['id'];
$classes = [ 'acf-map' ];
if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}


$map      = get_field( 'map_content' );
$settings = get_field( 'map_settings' );


$map_zoom        = $settings['zoom'] ? $settings['zoom'] : 16;
$map_styles      = $settings['styles'] ? "data-styles='" . $settings['styles'] . "'" : "";
$map_marker_icon = $settings['marker_icon'] ? wp_get_attachment_image_src( $settings['marker_icon'], 'thumbnail' )[0] : '';

if ( $map ): ?>
    <div id="<?= esc_attr( $id ); ?>" class="<?= esc_attr( implode( ' ', $classes ) ); ?>" data-zoom="<?= $map_zoom ?>" <?= $map_styles ?>>
        <div class="marker"
             data-lat="<?php echo esc_attr( $map['lat'] ); ?>"
             data-lng="<?php echo esc_attr( $map['lng'] ); ?>"
             data-icon="<?= $map_marker_icon ?>">
        </div>
    </div>
<?php endif; ?>