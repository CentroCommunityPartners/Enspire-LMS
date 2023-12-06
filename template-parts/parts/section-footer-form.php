<?php
$post_type    = get_post_type();
$archive_opts = get_field( 'archives', 'options' );
$archive_opts = $archive_opts[ $post_type ];

if ( isset( $archive_opts['form'] ) && ! empty( $archive_opts['form'] ) ) {
	$form = $archive_opts['form'];
	if ( empty( $form['form_id'] ) ) {
		return;
	}
}
?>

<div class="entry-content">
    <div class="wp-block-group block block-form block-form--title2line has-gray-alabaster-color has-bg-black-background-color has-text-color has-background">
        <div class="wp-block-group__inner-container">
            <h2 class="has-text-align-right block-title has-huge-font-size"><?= $form['block_title'] ?></h2>

            <div class="wp-block-columns">
                <div class="wp-block-column">
					<?= do_shortcode( '[gravityform id="' . $form['form_id'] . '" title="false" description="false" ajax="true" ]' ) ?>
                </div>

                <div class="wp-block-column">
					<?php
					if ( $form['form_description'] ) {
						printf( '<h3 class="has-text-align-right block-subtitle">%s</h3>', $form['form_title'] );
					}
					if ( $form['form_description'] ) {
						printf( '<p class="has-text-align-right">%s</p>', $form['form_description'] );
					}
					?>
                </div>
            </div>
        </div>
    </div>
</div>