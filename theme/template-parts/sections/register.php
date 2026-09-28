<?php
/**
 * Section: register.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- Final registration -->
    <section class="section register" id="register" aria-labelledby="register-title">
      <img class="register__lines" src="<?php lati_asset_e( 'image/svg/register-lines.svg' ); ?>" alt="" aria-hidden="true">

      <div class="container register__inner">
        <div class="register__copy">
          <p class="eyebrow eyebrow--light eyebrow--white-dot">Final registration</p>
          <h2 class="register__title" id="register-title">Before you focus on the coupon, understand what creates it.</h2>
          <p class="register__lead">Join the webinar to understand the economics, risks, and trade-offs behind structured-product returns.</p>
          <p class="register__meta"><?php lati_e( 'date' ); ?> · <?php lati_e( 'time' ); ?> · <?php lati_e( 'duration' ); ?> · Online</p>
          <p class="register__note">No product pitch. Methodology only.</p>
        </div>

        <div class="register__side">
          <?php $lati_status = isset( $_GET['registered'] ) ? sanitize_key( wp_unslash( $_GET['registered'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification ?>
          <div class="reg-form__success" data-reg-success <?php echo 'ok' === $lati_status ? '' : 'hidden'; ?>>
            <span class="reg-form__success-icon" aria-hidden="true"><svg><use href="#icon-check"/></svg></span>
            <p class="reg-form__success-title" data-reg-success-title><?php lati_e( 'success_title' ); ?></p>
            <p class="reg-form__success-text" data-reg-success-text><?php lati_e( 'success_text' ); ?></p>
          </div>

          <form class="reg-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" data-reg-form <?php echo 'ok' === $lati_status ? 'hidden' : ''; ?>>
            <input type="hidden" name="action" value="lati_register">
            <input type="hidden" name="started" value="" data-reg-started>
            <div class="reg-form__trap" aria-hidden="true">
              <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="reg-form__row">
              <label class="field">
                <span class="field__label">First name</span>
                <input class="field__control" type="text" name="first_name" autocomplete="given-name" required>
              </label>
              <label class="field">
                <span class="field__label">Last name</span>
                <input class="field__control" type="text" name="last_name" autocomplete="family-name" required>
              </label>
            </div>

            <label class="field">
              <span class="field__label">Email</span>
              <input class="field__control" type="email" name="email" autocomplete="email" required>
            </label>

            <label class="field">
              <span class="field__label">Role: Investor / RIA / Wealth Manager / Advisor / Other</span>
              <span class="field__select">
                <select class="field__control" name="role" required>
                  <option value="" selected disabled hidden></option>
                  <option>Investor</option>
                  <option>RIA</option>
                  <option>Wealth Manager</option>
                  <option>Advisor</option>
                  <option>Other</option>
                </select>
              </span>
            </label>

            <label class="field">
              <span class="field__label">Company (optional)</span>
              <input class="field__control" type="text" name="company" autocomplete="organization">
            </label>

            <label class="check">
              <input type="checkbox" name="updates" value="1">
              I agree to receive occasional updates from Lati Capital.
            </label>

            <button class="btn btn--block-mobile" type="submit">
              Register for the Webinar
              <span class="btn__icon" aria-hidden="true"><svg><use href="#icon-arrow"/></svg></span>
            </button>

            <p class="reg-form__error" data-reg-error role="alert" <?php echo 'error' === $lati_status ? '' : 'hidden'; ?>>Something went wrong. Please check the fields and try again.</p>

            <p class="reg-form__legal">
              <a href="https://cms.lati-capital.com/v1/files/lati-capital/privacy-policy" target="_blank" rel="noopener">Privacy Policy</a> ·
              <a href="https://cms.lati-capital.com/v1/files/lati-capital/terms-of-use" target="_blank" rel="noopener">Terms of Use</a>
            </p>
          </form>

          <p class="register__after">Can't make the live session? Register anyway. We'll send the recording.</p>
        </div>
      </div>
    </section>
