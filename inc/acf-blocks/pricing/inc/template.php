<?php
//ID & Classes
$id = ! empty( $block['anchor'] ) ? $block['anchor'] : 'block-' . $block['id'];
$classes = [ 'block-pricing' ];
if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}

$plans = get_field( 'misfit-pricing' );

?>

<div id="<?php echo esc_attr( $id ); ?>" class="<?= esc_attr( implode( ' ', $classes ) ); ?>">

    <h2 class="has-text-align-center block-title">MEMBERSHIP <br>PRICING</h2>

	<?php
	foreach ( $plans as $plan ) {
		$price_m    = isset( $plan['plan_price']['monthly'] ) ? $plan['plan_price']['monthly'] : 0;
		$price_y    = isset( $plan['plan_price']['yearly'] ) ? $plan['plan_price']['yearly'] : 0;
		$price_save = round( $price_m * 12, 2 ) - $price_y;

		$plan_title       = $plan['plan_title'];
		$plan_description = $plan['plan_description'];
		$plan_benefits    = $plan['plan_benefits'];

		$plan_button_m = $plan['plan_buttons']['monthly'];
		$plan_button_y = $plan['plan_buttons']['yearly'];

		$plan_toggle_id = sanitize_title( 'toggle_' . $plan_title );
		?>
        <div class="pricing-plan block-white block-p" data-plan="monthly">

            <div class="pricing-plan__toggle">

                <div class="toggle">
                    <input id="<?= $plan_toggle_id ?>" type="checkbox">
                    <label for="<?= $plan_toggle_id ?>">
                        <span>Monthly</span>
                        <span class="switcher"></span>
                        <span>Yearly</span>
                    </label>
                </div>

            </div>

			<?php if ( $price_m & $price_y ): ?>
                <div class="pricing-plan__save">
                    <div class="plan-monthly">&nbsp;</div>
                    <div class="plan-yearly">You save <?= $price_save ?>$ with an annual plan</div>
                </div>
			<?php endif; ?>

            <div class="pricing-plan__row">
				<?php
				echo $plan_title ? sprintf( '<div class="pricing-plan__title">%s</div>', $plan_title ) : '';
				echo $price_m ? sprintf( '<div class="pricing-plan__price plan-monthly">%s$ <span>/month</span></div>', $price_m ) : '';
				echo $price_y ? sprintf( '<div class="pricing-plan__price plan-yearly">%s$ <span>/month</span></div>', round( $price_y / 12, 2) )
					: '';
				?>
            </div>

            <div class="pricing-plan__row">
				<?php
				echo $plan_description ? sprintf( '<div class="pricing-plan__description">%s</div>', $plan_description ) : '';
				echo $plan_benefits ? sprintf( '<div class="pricing-plan__benefits">%s</div>', $plan_benefits ) : '';
				?>
            </div>

            <div class="pricing-plan__row">
				<?php
				echo $plan_button_m ? sprintf( ' <a class="button button-primary plan-monthly" href="%s">%s</a>', $plan_button_m['url'],
					$plan_button_m['title'] ) : '';
				echo $plan_button_y ? sprintf( ' <a class="button button-primary plan-yearly" href="%s">%s</a>', $plan_button_y['url'],
					$plan_button_y['title'] ) : '';
				?>
            </div>

        </div>
	<?php } ?>
</div>
