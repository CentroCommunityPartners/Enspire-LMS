<div class="wp-block-group block block-single-team has-gray-alabaster-color has-bg-black-background-color has-text-color has-background">
    <div class="wp-block-group__inner-container">
        <h1 class="block-title has-huge-font-size">
			<?= str_replace( " ", "<br>", get_the_title() ) ?>
        </h1>


        <div class="wp-block-columns">
            <div class="wp-block-column">
				<?php the_content() ?>

                <div class="wp-block-buttons">
                    <div class="wp-block-button is-style-transparent">
                        <a class="wp-block-button__link" href="<?= get_post_permalink( get_next_post()->ID ) ?>">See next
                            Coach</a>
                    </div>
                </div>
            </div>


            <div class="wp-block-column" style="flex-basis:51%">
                <figure class="wp-block-image size-large is-style-ratio-3-4">
					<?= get_the_post_thumbnail( get_the_ID(), array( 480, 640 ) ) ?>
                </figure>
            </div>
        </div>
    </div>
</div>