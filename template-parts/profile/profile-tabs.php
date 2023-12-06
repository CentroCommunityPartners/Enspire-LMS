<?php
$tabsArr = array(
	'demographics'       => DEMOGRAPHICS_ACF,
	'benchmarks_classic' => BENCHMARKS_CLASSIC,
	'benchmarks_misfit'  => BENCHMARKS_MISFIT
);
?>

<?php
if ( is_author() ) {
	$user       = get_user_by( 'slug', get_query_var( 'author_name' ) );
	$user_id    = $user->ID;
	$no_content = '<span>This user did not fill in the fields</span>';
} else {
	$user_id    = get_current_user_id();
	$no_content = '<span>Your profile is incomplete. <a href="' . home_url( '/profile-edit/' ) . '">Edit your profile</a></span>';
}

$meta_format_default = '<div class="profile-meta col col-sm-3"><label>%s</label><span class="h3">%s</span></div>';

?>

<div class="tabs tabs--purple">
    <div class="tabs__control">
        <a href="#demographics" class="current">Demographics</a>
        <a href="#benchmarks_classic">Classic Benchmarks</a>
        <a href="#benchmarks_misfit">Misfit Benchmarks</a>
    </div>
    <div class="tabs__content rcp_box">
		<?php
		foreach ( $tabsArr as $keyTab => $arrayInputs ):
			echo '<div id="' . $keyTab . '">';
			echo '<div class="row">';

			$tab_has_content = false;

			foreach ( $arrayInputs as $meta_key => $field ):
				$meta_value  = get_user_meta( $user_id, $meta_key, true );
				$meta_format = $meta_format_default;

				if ( ! $meta_value ) {
					continue;
				}

				switch ( $field['type'] ) {
					case 'time':
						$time        = convert_ms_to_time( $meta_value );
						$time_format = '<span class="%s">
											<i class="time-unit__val">%s</i>
											<small class="time-unit__label">%s</small>
										</span>';

						if ( ! empty( $time['min'] ) ) {
							$time['min'] = ( $time['min'] < 10 ? "0" . $time['min'] : $time['min'] );
						} else {
							$time['min'] = '';
						}

						if ( ! empty( $time['s'] ) ) {
							$time['s'] = ( $time['s'] < 10 ? "0" . $time['s'] : $time['s'] );
						} else {
							$time['s'] = '0';
						}

						if ( ! empty( $time['ms'] ) ) {
							$time['ms'] = ( $time['ms'] < 10 ? "0" . $time['ms'] : $time['ms'] );
						} else {
							$time['ms'] = '00';
						}

						$time['ms'] = ! empty( $time['ms'] ) ? $time['ms'] : "0";

						$meta_value = '';
						$meta_value .= $time['min'] ? sprintf( $time_format, 'time-unit__min', $time['min'], 'min' ) : "";
						$meta_value .= sprintf( $time_format, 'time-unit__s', $time['s'], 's' );
						$meta_value .= sprintf( $time_format, 'time-unit__ms', $time['ms'], 'ms' );
						$meta_value = sprintf( '<div class="time-unit">%s</div>', $meta_value );
						break;
					case 'weight':
					case 'measurement':
						$valObj = json_decode( $meta_value );
						$val    = ( $valObj->value ? $valObj->value : 0 );
						$unit   = $valObj->unit;


						if ( $unit == 'ft' ) {
							$val = explode( ',', $val );
							$ft  = isset( $val[0] ) ? $val[0] : '';
							$inc = isset( $val[1] ) ? $val[1] : '';

							if ( $ft ) {
								$val = $ft . "'";
								$val = $inc ? $val . $inc . '"' : $val;
							}

							$meta_value = $val;
						} else {
							$meta_value = $val . $unit;
						}


						break;
					case 'textarea':
						$meta_format = '<div class="profile-meta col col-sm-12"><label>%s</label><div class="profile-textarea">%s</div></div>';
						break;

				}

				printf( $meta_format, $field['label'], $meta_value );
				$tab_has_content = true;
			endforeach;

			echo( ! $tab_has_content ? $no_content : '' );

			echo '</div>';
			echo '</div>';
		endforeach;
		?>

		<?php /*
        <div id="user-atributes">
            <div class="row">
				<?php
				$tab_display_attribute = false;
				foreach ( $tabsArr['demographics'] as $field ):
					$meta_value = get_user_meta( $user_ID, $field['meta_key'], true );
					if ( ! $meta_value ) {
						continue;
					}
					printf( $meta_format, $field['label'], $meta_value );
					$tab_display_attribute = true;
				endforeach;
				echo( ! $tab_display_attribute ? $no_content : '' );
				?>
            </div>
        </div>
        */ ?>
    </div>
</div>
