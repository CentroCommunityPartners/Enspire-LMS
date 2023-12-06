<div class="wp-block-group block block-team block-team-columns has-gray-alabaster-color has-bg-black-background-color has-text-color has-background">
    <div class="wp-block-group__inner-container">
        <h2 class="has-text-align-right block-title"><?= $term->name ?></h2>

        <div class="wp-block-group block-team-items">
            <div class="wp-block-group__inner-container">
				<?php while ( $members->have_posts() ) : $members->the_post(); ?>
                    <div class="wp-block-group team-member">
                        <div class="wp-block-group__inner-container">
                            <figure class="wp-block-image size-large team-member__image is-style-ratio-1-1">
								<?php echo get_the_post_thumbnail( get_the_id(), array( 400, 400 ) ); ?>
                            </figure>

                            <div class="wp-block-group team-member__info">
                                <div class="wp-block-group__inner-container">
                                    <h4 class="team-member__name"><?php the_title() ?></h4>
                                    <div class="team-member__description"><?php echo the_excerpt( 12 ) ?></div>
                                    <div class="wp-block-buttons">
                                        <div class="wp-block-button is-style-transparent team-member__button">
                                            <a class="wp-block-button__link" href="<?= get_post_permalink() ?>">Read Bio</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
            </div>
        </div>
    </div>
</div>