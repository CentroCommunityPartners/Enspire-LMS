<?php
if ( ! is_user_logged_in() ):
	$section = get_field( 'try_free', 'option' );
	?>
    <div id="block-try-free"
         class="wp-block-group block block-img-text has-gray-alabaster-color has-bg-black-background-color has-text-color has-background">
        <div class="wp-block-group__inner-container">

			<?php if ( $section['title'] ): ?>
                <h2 class="has-text-align-right block-title has-huge-font-size">
					<?= $section['title'] ?>
                </h2>
			<?php endif; ?>


            <div class="wp-block-columns">
                <div class="wp-block-column" style="flex-basis:79.6%">
                    <figure class="wp-block-image size-full is-style-ratio-4-3">
						<?= wp_get_attachment_image( $section['image'], 'large' ) ?>
                    </figure>
                </div>


                <div class="wp-block-column" style="flex-basis:33.33%">

					<?php if ( $section['description'] ): ?>
						<?= $section['description'] ?>
					<?php endif; ?>

					<?php if ( $section['button'] ): ?>
                        <div class="wp-block-buttons">
                            <div class="wp-block-button">
                                <a class="wp-block-button__link" href="<?= $section['button']['url'] ?>" target="<?= $section['button']['target'] ?>">
									<?= $section['button']['title'] ?>
                                </a>
                            </div>
                        </div>
					<?php endif; ?>


                </div>
            </div>
        </div>
    </div>
<?php
endif;