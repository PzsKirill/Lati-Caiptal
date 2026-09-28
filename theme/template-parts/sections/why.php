<?php
/**
 * Section: why.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- Why this topic matters -->
    <section class="section section--alt why" aria-labelledby="why-title">
      <div class="container why__inner">
        <div class="section-head">
          <div class="section-head__main">
            <p class="eyebrow">Why this topic matters</p>
            <h2 class="section-title" id="why-title">The question is not whether 12% looks attractive.</h2>
          </div>
          <p class="section-head__lead section-lead">A high coupon can look like simple income, but the real question is what creates that return, what has to happen for you to receive it, and what happens when the scenario breaks.</p>
        </div>

        <div class="why-grid">
          <article class="why-card why-card--structure">
            <div class="why-card__text">
              <span class="num-tag">01</span>
              <h3 class="why-card__title">The coupon has a structure</h3>
              <p class="why-card__desc">Structured-product returns are created through a combination of lending, options, and downside risk. Understanding those components makes the quoted coupon easier to evaluate.</p>
            </div>
            <div class="why-card__media">
              <img src="<?php lati_asset_e( 'image/png/why-structure.png' ); ?>" width="596" height="780" alt="" loading="lazy">
            </div>
          </article>

          <div class="why-grid__side">
            <article class="why-card why-card--conditions">
              <div class="why-card__text">
                <span class="num-tag">02</span>
                <h3 class="why-card__title">Conditions matter</h3>
                <p class="why-card__desc">The question is not simply whether 12% is attractive. It is what has to happen for you to receive that return.</p>
              </div>
              <div class="why-card__media">
                <img src="<?php lati_asset_e( 'image/png/why-conditions.png' ); ?>" width="680" height="476" alt="" loading="lazy">
              </div>
            </article>

            <div class="why-grid__row">
              <article class="why-card why-card--downside">
                <div class="why-card__text">
                  <span class="num-tag">03</span>
                  <h3 class="why-card__title">Downside changes the picture</h3>
                  <p class="why-card__desc">When the barrier is breached, the outcome can look very different from when it isn't. The coupon only makes sense when considered together with that risk.</p>
                </div>
                <div class="why-card__media">
                  <picture>
                    <source media="(max-width: 767px)" srcset="<?php lati_asset_e( 'image/png/Rectangle.png' ); ?>" width="722" height="360">
                    <img src="<?php lati_asset_e( 'image/png/why-downside.png' ); ?>" width="872" height="574" alt="" loading="lazy">
                  </picture>
                </div>
              </article>

              <article class="why-card why-card--term">
                <div class="why-card__text">
                  <span class="num-tag">04</span>
                  <h3 class="why-card__title">The term is not fixed</h3>
                  <p class="why-card__desc">Many of these notes are autocallable: if the underlying meets the set condition on an observation date, the issuer redeems it early and returns your principal – before the term you originally planned for. Your actual holding period can end up shorter than expected.</p>
                </div>
                <div class="why-card__media">
                  <img src="<?php lati_asset_e( 'image/png/why-term.png' ); ?>" width="702" height="526" alt="" loading="lazy">
                </div>
              </article>
            </div>
          </div>
        </div>
      </div>
    </section>
