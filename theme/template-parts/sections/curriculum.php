<?php
/**
 * Section: curriculum.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- What you will learn -->
    <section class="section curriculum" id="learn" aria-labelledby="learn-title">
      <div class="container curriculum__inner">
        <div class="section-head">
          <div class="section-head__main">
            <p class="eyebrow">What you will learn</p>
            <h2 class="section-title" id="learn-title">Exactly what you'll be able to evaluate after <?php lati_e( 'duration' ); ?></h2>
          </div>
          <p class="section-head__lead section-lead">The session follows the mechanics in order: how the product works, why the headline coupon sits above ordinary interest, what the bank wrapper adds, and what changes if that wrapper is removed.</p>
        </div>

        <div class="curriculum__body">
          <figure class="price-chart" data-price-chart aria-label="Example note: underlying share price with coupon events, barrier and autocall trigger">
            <div class="price-chart__frame">
              <span class="price-chart__y-title">Share  Price</span>
              <div class="price-chart__area">
                <div class="price-chart__grid" data-grid data-min="250" data-max="500">
                  <div class="price-chart__row"><span>500.00</span></div>
                  <div class="price-chart__row"><span>450.00</span></div>
                  <div class="price-chart__row"><span>400.00</span></div>
                  <div class="price-chart__row"><span>350.00</span></div>
                  <div class="price-chart__row"><span>300.00</span></div>
                  <div class="price-chart__row"><span>250.00</span></div>
                  <div class="price-chart__plot">
                    <svg class="price-chart__svg" data-svg aria-hidden="true"></svg>
                  </div>
                </div>
                <div class="price-chart__x" data-x-axis aria-hidden="true"></div>
              </div>
            </div>
            <ul class="price-chart__legend">
              <li><span class="legend-key legend-key--spot"></span>Spot</li>
              <li><span class="legend-key legend-key--coupon"></span>Coupon Event</li>
              <li><span class="legend-key legend-key--barrier"></span>Barrier</li>
              <li><span class="legend-key legend-key--autocall"></span>Autocall-Trigger</li>
            </ul>
          </figure>

          <div class="note-table" data-note-table role="table" aria-label="Example note data">
            <div class="note-table__row note-table__head" role="row">
              <span role="columnheader">Spot</span>
              <span role="columnheader">Delta</span>
              <span role="columnheader">Cash</span>
              <span role="columnheader">Coupon Event</span>
              <span role="columnheader">Barrier</span>
              <span role="columnheader">Autocall-Trigger</span>
            </div>
            <div class="note-table__viewport">
              <div class="note-table__body" data-rows role="rowgroup"></div>
            </div>
          </div>

          <ol class="learn-grid" data-learn-grid>
            <li class="learn-card is-active"><span class="learn-card__num">01</span><span class="learn-card__title">How structured products generate their headline coupon</span></li>
            <li class="learn-card"><span class="learn-card__num">02</span><span class="learn-card__title">Why the quoted coupon can be significantly higher than ordinary interest</span></li>
            <li class="learn-card"><span class="learn-card__num">03</span><span class="learn-card__title">What barriers, coupons, caps, downside scenarios, and autocallable features actually mean</span></li>
            <li class="learn-card"><span class="learn-card__num">04</span><span class="learn-card__title">How the bank manages the risk behind the product</span></li>
            <li class="learn-card"><span class="learn-card__num">05</span><span class="learn-card__title">What investors pay for through the traditional note wrapper</span></li>
            <li class="learn-card"><span class="learn-card__num">06</span><span class="learn-card__title">Why a bank-issued note is typically worth less than the amount invested on day one</span></li>
            <li class="learn-card"><span class="learn-card__num">07</span><span class="learn-card__title">How issuer credit risk, liquidity, and structuring costs affect the economics</span></li>
            <li class="learn-card"><span class="learn-card__num">08</span><span class="learn-card__title">How similar payoff structures can be built directly inside an investment account</span></li>
            <li class="learn-card"><span class="learn-card__num">09</span><span class="learn-card__title">What is gained, and what is given up, when the bank-issued wrapper is removed</span></li>
          </ol>
        </div>
      </div>
    </section>
