<div class="wp-block-group block block-team block-team-slider-right has-gray-alabaster-color has-bg-black-background-color has-text-color has-background">
    <div class="wp-block-group__inner-container">
        <h2 class="block-title has-text-align-right">
            <?= str_replace(" ", "<br>", $term->name) ?>
        </h2>

        <div class="wp-block-group team-slider owl-carousel">
            <div class="wp-block-group__inner-container">
				<?php while ( $members->have_posts() ) : $members->the_post(); ?>
                    <div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-top team-member"
                         style="grid-template-columns:auto 68%">
                        <figure class="wp-block-media-text__media">
							<?php echo get_the_post_thumbnail( get_the_id(), array( 400, 400 ) ); ?>
                        </figure>
                        <div class="wp-block-media-text__content">
                            <h3 class="team-member__name"><?php the_title() ?></h3>

<!--                            <p class="team-member__follow">Follow: @alexisripped</p>-->

                            <div class="wp-block-group team-member__info">
                                <div class="wp-block-group__inner-container">
									<?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
            </div>
        </div>
    </div>
</div>