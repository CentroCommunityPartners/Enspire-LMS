<?php
$section = get_field( 'misfit_gear', 'option' );
$gallery =  $section['images'];
if( is_array($gallery)):
?>
<div id="block-misfit-gear" class="wp-block-group block-misfit-gear has-gray-alabaster-color has-bg-black-background-color has-text-color has-background">
    <div class="wp-block-group__inner-container">
        <div class="wp-block-columns">
            <div class="wp-block-column">
                <div class="wp-block-group">
                    <div class="wp-block-group__inner-container">
	                    <?php if ( $section['title'] ): ?>
                            <h2 class="block-title has-huge-font-size">
			                    <?= $section['title'] ?>
                            </h2>
	                    <?php endif; ?>

	                    <?php if ( $section['description'] ): ?>
		                    <?= $section['description'] ?>
	                    <?php endif; ?>

	                    <?php if ( $section['button'] ): ?>
                            <div class="wp-block-buttons">
                                <div class="wp-block-button is-style-full">
                                    <a class="wp-block-button__link" href="<?= $section['button']['url'] ?>" target="<?= $section['button']['target'] ?>">
					                    <?= $section['button']['title'] ?>
                                    </a>
                                </div>
                            </div>
	                    <?php endif; ?>
                    </div>
                </div>
            </div>


            <div class="wp-block-column">

            </div>

            <div class="wp-block-column">
                <figure class="wp-block-image size-large">
	                <?php if($gallery[0]) echo wp_get_attachment_image($gallery[0],'large'); ?>
                </figure>

                <figure class="wp-block-image size-large">
		            <?php if($gallery[1]) echo wp_get_attachment_image($gallery[1],'large'); ?>
                </figure>

                <figure class="wp-block-image size-large">
		            <?php if($gallery[2]) echo wp_get_attachment_image($gallery[2],'large'); ?>
                </figure>
            </div>


            <div class="wp-block-column">
                <figure class="wp-block-image size-large">
		            <?php if($gallery[3]) echo wp_get_attachment_image($gallery[3],'large'); ?>
                </figure>

                <figure class="wp-block-image size-large">
		            <?php if($gallery[4]) echo wp_get_attachment_image($gallery[4],'large'); ?>
                </figure>

                <figure class="wp-block-image size-large">
		            <?php if($gallery[5]) echo wp_get_attachment_image($gallery[5],'large'); ?>
                </figure>
            </div>
        </div>
    </div>
</div>

<?php endif;