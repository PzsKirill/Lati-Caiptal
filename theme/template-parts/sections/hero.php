<?php
/**
 * Section: hero.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- Hero -->
    <section class="hero" aria-labelledby="hero-title">
      <div class="hero__card">
        <img class="hero__lines hero__lines--top" src="<?php lati_asset_e( 'image/svg/hero-lines-top.svg' ); ?>" alt="" aria-hidden="true">
        <img class="hero__lines hero__lines--bottom" src="<?php lati_asset_e( 'image/svg/hero-lines-bottom.svg' ); ?>" alt="" aria-hidden="true">

        <div class="hero__inner container">
        <div class="hero__copy">
          <div class="hero__main">
            <p class="eyebrow">Live webinar</p>
            <h1 class="hero__title" id="hero-title">How Do Structured Products Generate a 10-15% Coupon?</h1>
            <p class="hero__lead">High headline coupons look attractive, but the numbers alone don't reveal the hidden mechanics. Join us to learn how the coupon is built, where the downside sits, and what you must understand before evaluating these products.</p>
            <a class="btn btn--block-mobile" href="#register">
              Register for the Webinar
              <span class="btn__icon" aria-hidden="true"><svg><use href="#icon-arrow"/></svg></span>
            </a>
            <p class="hero__meta"><?php lati_e( 'date' ); ?> · <?php lati_e( 'time' ); ?> · Online · <?php lati_e( 'duration' ); ?></p>
          </div>

          <p class="hero__disclaimer">Hosted by Lati Capital, an SEC-registered investment advisor. Quoted coupons are not guaranteed. This webinar is for education and is not an offer to buy or sell securities.</p>
        </div>

        <div class="hero-slider" data-hero-slider data-duration="6000" aria-roledescription="carousel" aria-label="Webinar highlights">
          <div class="hero-slider__slides">
            <!-- TODO: replace slides 2–3 with their own photos when provided -->
            <figure class="hero-slider__slide is-active" data-slide>
              <img src="<?php lati_asset_e( 'image/png/hero-image.png' ); ?>" width="1084" height="1512" alt="Gian-Marco Frey in Lower Manhattan" fetchpriority="high">
            </figure>
            <figure class="hero-slider__slide" data-slide aria-hidden="true">
              <img src="<?php lati_asset_e( 'image/png/hero-image.png' ); ?>" width="1084" height="1512" alt="" loading="lazy">
            </figure>
            <figure class="hero-slider__slide" data-slide aria-hidden="true">
              <img src="<?php lati_asset_e( 'image/png/hero-image.png' ); ?>" width="1084" height="1512" alt="" loading="lazy">
            </figure>
          </div>

          <div class="hero-slider__captions" aria-live="polite">
            <p class="hero-slider__caption is-active" data-caption>Where does a 10-15%+ structured product yield actually come from?</p>
            <p class="hero-slider__caption" data-caption>What happens to your principal if the barrier is breached?</p>
            <p class="hero-slider__caption" data-caption>What do you give up inside a bank-issued note?</p>
          </div>

          <div class="hero-slider__progress">
            <button class="hero-slider__bar is-active" type="button" data-bar aria-label="Show slide 1"><span class="hero-slider__bar-fill"></span></button>
            <button class="hero-slider__bar" type="button" data-bar aria-label="Show slide 2"><span class="hero-slider__bar-fill"></span></button>
            <button class="hero-slider__bar" type="button" data-bar aria-label="Show slide 3"><span class="hero-slider__bar-fill"></span></button>
          </div>
        </div>
        </div>
      </div>
    </section>
