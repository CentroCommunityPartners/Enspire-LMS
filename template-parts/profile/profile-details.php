<?php
$user_id = is_user_logged_in() ? get_current_user_id() : 0;
?>

<div class="rcp_form_section_profile_aditionals">
    <div class="rcp_box">
        <div class="row">
            <div class="col col-sm-12">
                <h3>DEMOGRAPHICS</h3>
            </div>
			<?php rcp_profile_aditionalt_inputs( DEMOGRAPHICS_ACF, $user_id ); ?>
        </div>
    </div>
</div>

<div class="rcp_form_section_profile_benchmarks_classic">
    <div class="rcp_box">
        <div class="row">
            <div class="col col-sm-12">
                <h3>Classic Benchmarks</h3>
            </div>
			<?php rcp_profile_aditionalt_inputs( BENCHMARKS_CLASSIC, $user_id ); ?>
        </div>
    </div>
</div>

<div class="rcp_form_section_profile_benchmarks_misfit">
<div class="rcp_box">
    <div class="row">
        <div class="col col-sm-12">
            <h3>Misfit Benchmarks</h3>
        </div>
		<?php rcp_profile_aditionalt_inputs( BENCHMARKS_MISFIT, $user_id ); ?>
    </div>
</div>
</div>