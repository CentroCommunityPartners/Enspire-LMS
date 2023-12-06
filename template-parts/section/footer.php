<footer id="colophon" class="site-footer">


    <div class="site-footer__top my-8">
        <div class="container text-center md:flex items-center justify-between">

		<div class="flex items-center justify-center md:text-left">
			<?php  echo get_template_part( 'template-parts/site-logo' ); ?>
		</div>
            <div class="footer-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-footer',
						'menu_id'        => 'menu-footer',
						'items_wrap'     => '<ul id="%1$s" class="flex items-center justify-center footer-menu">%3$s</ul>',
					)
				);
				?>
            </div>

            <div class="footer-widgets">
				<?php dynamic_sidebar( 'footer' ); ?>
            </div>
        </div>
    </div>

		<hr class="w-11/12 text-center mx-auto bg-secondary-content">
		
    <div class="site-footer__bottom my-8">
        <div class="container text-center md:flex items-center justify-center">
                <span class="copyright">
                    <?php printf( '© %s Acces Point | All rights reserved', date( 'Y' ) ); ?>
                </span>

			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-footer-utility',
					'menu_id'        => 'menu-footer-utility',
					'items_wrap'     => '<ul id="%1$s" class="flex items-center justify-center md:text-xs footer-menu">%3$s</ul>',
				)
			);
			?>
        </div>
    </div>
</footer><!-- #colophon -->



