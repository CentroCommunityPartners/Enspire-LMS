<?php
$title       = get_the_title();
$class_title = ( strlen( $title ) > 20 ? 'h2' : '' );
?>

<div class="container">
    <div class="single-layout">

        <div class="single-layout__header">
            <div class="single-layout__title">
                <h1 class="block-title <?= $class_title ?>">
					<?php
					if ( substr( $title, 0, 5 ) === "MFT #" ) {
						$title = preg_replace( '/\s+/', '<br>', $title );
						$title = '<span class="wod">WOD:</span><br>' . $title;
					}

					echo $title;
					?>
                </h1>
            </div>

            <div class="single-layout__thumbnail">
                <div class="wp-block-image is-style-ratio-4-3">
					<?php echo enspire_post_thumbnail() ?>
                </div>
            </div>
        </div>

        <div class="single-layout__content">
            <div class="the-content theme-white">
				<?php
				$tabsData     = Posts()->getPostTabsPHP( get_the_ID() );
				$tabs_control = '';
				$tabs_content = '';

				foreach ( $tabsData['tabs'] as $tab ) {
					if ( empty( $tab['content'] ) ) {
						continue;
					}
					$tabs_control .= sprintf( '<a href="#%s">%s</a>', $tab['content_key'], $tab['title'] );
					$tabs_content .= sprintf( '<div id="%s"><div class="box">%s</div></div>', $tab['content_key'], $tab['content'] );
				}

				$outputFormat = '<div class="tabs">';
				$outputFormat .= '<div class="tabs__control">' . $tabs_control . '</div>';
				$outputFormat .= '<div class="tabs__content">' . $tabs_content . '</div>';
				$outputFormat .= '</div>';

				echo $outputFormat;
				?>
            </div>
        </div>
    </div>
</div>

