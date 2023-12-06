<?php if (! is_user_logged_in()): ?>
    <div class="form__row row row--gutter row--hc rcp_login_link">
      <p class="col text-center">
        <small><strong>
          <?= __('Already have an account?', 'rcp') ?>
        </strong></small>
      </p>

      <div class="rcp-flex-end">
        <div class="rcp-login-button-wrap">
          <a
            href="<?php echo rcp_get_login_url(rcp_get_current_url()); ?>"
            class="rcp-login-button button button--dark-grey-hollow"
          > Log in </a>
        </div>

        <div class="rcp-fb-button">
          <?= do_shortcode('[TheChamp-Login]'); ?>
        </div>
      </div>
    </div>
<?php endif; ?>


