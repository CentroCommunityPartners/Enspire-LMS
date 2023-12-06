<?php
// Show the profile detail fields.
if ( ! is_user_logged_in() ):
	?>
    <div class="rcp_form_section_profile">
        <div class="rcp_box">

            <div class="account-details">
                <div class="rcp_message error" style="display: none;"></div>

                <div class="row">
                    <div class="col col-sm-12">
                        <h3>Create Profile</h3>
                    </div>
                    <div class="col col-sm-6" id="rcp_user_first_wrap">
                        <label class="sr-only" for="rcp_user_first">
							<?= apply_filters( 'rcp_registration_firstname_label', __( 'First Name', 'rcp' ) ); ?>
                        </label>

                        <input
                                class="form__input input"
                                name="rcp_user_first"
                                id="rcp_user_first"
                                type="text"
							<?php if ( isset( $_POST['rcp_user_first'] ) ) {
								echo 'value="' . esc_attr( $_POST['rcp_user_first'] ) . '"';
							} ?>
                        />
                    </div>
                    <div class="col col-sm-6" id="rcp_user_last_wrap">
                        <label
                                class="sr-only"
                                for="rcp_user_last"
                        >
							<?= apply_filters( 'rcp_registration_lastname_label', __( 'Last Name', 'rcp' ) ); ?>
                        </label>
                        <input class="form__input input"
                               name="rcp_user_last"
                               id="rcp_user_last"
                               type="text"
							<?php if ( isset( $_POST['rcp_user_last'] ) ) {
								echo 'value="' . esc_attr( $_POST['rcp_user_last'] ) . '"';
							} ?>/>
                    </div>
                    <div class="col col-sm-6" id="rcp_user_email_wrap">
                        <label
                                class="sr-only"
                                for="rcp_user_email"
                        >
							<?= apply_filters( 'rcp_registration_email_label', __( 'Email', 'rcp' ) ); ?>
                        </label>
                        <input
                                class="form__input input"
                                name="rcp_user_email"
                                id="rcp_user_email"
                                class="required"
                                type="text"
							<?php if ( isset( $_POST['rcp_user_email'] ) ) {
								echo 'value="' . esc_attr( $_POST['rcp_user_email'] ) . '"';
							} ?>
                        />
                    </div>

                    <div class="col col-sm-6" id="rcp_user_login_wrap">
                        <label
                                class="sr-only"
                                for="rcp_user_login"
                        >
							<?= apply_filters( 'rcp_registration_username_label', __( 'Username', 'rcp' ) ); ?>
                        </label>

                        <input class="form__input input"
                               name="rcp_user_login"
                               id="rcp_user_login" class="required"
                               type="text"
							<?php if ( isset( $_POST['rcp_user_login'] ) ) {
								echo 'value="' . esc_attr( $_POST['rcp_user_login'] ) . '"';
							} ?>/>
                    </div>
                    <div class="col col-sm-6">
                        <label class="sr-only" for="rcp_gender"><?= apply_filters( 'rcp_gender_label', __( 'Gender', 'rcp' ) ); ?></label>
                        <select name="rcp_gender" id="rcp_gender" class="rcp_gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-sm-6">
                        <div class="row">
                            <div class="col col-sm-12">
                                <h3>Profile Photo</h3>
                            </div>
                            <div class="col col-sm-12">
                                <?php echo do_shortcode( '[eavatar]' ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="row">
                            <div class="col col-sm-12">
                                <h3>CHOOSE PASSWORD</h3>
                            </div>
                            <div class="col col-sm-12" id="rcp_password_wrap">
                                <label class="sr-only" for="rcp_password">
									<?= apply_filters( 'rcp_registration_password_label', __( 'Password', 'rcp' ) ); ?>
                                </label>
                                <input class="form__input input"
                                       name="rcp_user_pass" id="rcp_password"
                                       class="required"
                                       type="password"/>
                            </div>
                            <div class="col col-sm-12" id="rcp_password_again_wrap">
                                <label class="sr-only" for="rcp_password_again">
									<?= apply_filters( 'rcp_registration_password_again_label', __( 'Password Again', 'rcp' ) ); ?>
                                </label>
                                <input class="form__input input" name="rcp_user_pass_confirm" id="rcp_password_again" class="required"
                                       type="password"/>
                            </div>

							<?php do_action( 'rcp_after_password_registration_field' ); ?>
                        </div>
                    </div>
                </div>

            </div> <!-- // .account-details-col -->

			<?php get_template_part( 'rcp/register/register-btwb' ); ?>

        </div>
    </div>
<?php endif; ?>
