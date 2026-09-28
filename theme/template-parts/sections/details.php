<?php
/**
 * Section: details.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- Webinar details -->
    <section class="details" aria-label="Webinar details">
      <div class="details__card">
        <img class="details__lines details__lines--left" src="<?php lati_asset_e( 'image/svg/details-lines-left.svg' ); ?>" alt="" aria-hidden="true">
        <img class="details__lines details__lines--right" src="<?php lati_asset_e( 'image/svg/details-lines-right.svg' ); ?>" alt="" aria-hidden="true">

        <div class="details__inner container">
        <dl class="details__list">
          <div class="details__item">
            <dt class="details__label">Date</dt>
            <dd class="details__value"><?php lati_e( 'date' ); ?></dd>
          </div>
          <div class="details__item">
            <dt class="details__label">Time</dt>
            <dd class="details__value"><?php lati_e( 'time' ); ?></dd>
          </div>
          <div class="details__item">
            <dt class="details__label">Duration</dt>
            <dd class="details__value"><?php lati_e( 'duration' ); ?></dd>
          </div>
          <div class="details__item">
            <dt class="details__label">Format</dt>
            <dd class="details__value">Online / <?php lati_e( 'platform' ); ?></dd>
          </div>
          <div class="details__item">
            <dt class="details__label">Cost</dt>
            <dd class="details__value"><?php lati_e( 'cost' ); ?></dd>
          </div>
          <div class="details__item">
            <dt class="details__label">Language</dt>
            <dd class="details__value"><?php lati_e( 'language' ); ?></dd>
          </div>
          <div class="details__item">
            <dt class="details__label">Registration deadline</dt>
            <dd class="details__value"><?php lati_e( 'deadline' ); ?></dd>
          </div>
        </dl>

        <div class="details__cta">
          <a class="btn btn--dark btn--block-mobile" href="#register">
            Reserve Your Spot
            <span class="btn__icon" aria-hidden="true"><svg><use href="#icon-arrow"/></svg></span>
          </a>
          <p class="details__note">Can't make the live session?<br> Register anyway. We'll send the recording.</p>
        </div>
        </div>
      </div>
    </section>
