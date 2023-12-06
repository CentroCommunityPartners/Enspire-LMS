<?php if ( isset( $args ) && $args ): ?>
    <div class="icon-list icon-list-menu flex justify-around">
		<?php foreach ( $args as $item ): ?>
            <div class="icon-list-item relative w-full text-center py-14">
                <div class="icon-list-item__icon">
                    <i class=" <?= $item['icon'] ?>"></i>
                </div>
                <div class="icon-list-item__text text-3xl font-semibold">
					<?= $item['title'] ?>
                </div>
                <a class="absolute-fill" href="<?= $item['url'] ?>"></a>
            </div>
		<?php endforeach; ?>
    </div>
<?php
endif;