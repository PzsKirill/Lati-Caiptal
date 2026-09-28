<?php
/**
 * Section: payoff.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- Payoff example -->
    <section class="section payoff" aria-labelledby="payoff-title">
      <div class="container payoff__inner">
        <div class="payoff__copy">
          <div class="payoff__head">
            <p class="eyebrow">A simple structured product example</p>
            <h2 class="section-title" id="payoff-title">See the mechanics in 30 seconds</h2>
          </div>
          <p class="payoff-step is-active" data-step data-level="45" tabindex="0">You invest <?php lati_e( 'amount' ); ?>. The product offers <?php echo esc_html( rtrim( lati_get( 'coupon' ), '%' ) ); ?>% if the underlying remains above a predefined barrier.</p>
          <p class="payoff-step" data-step data-level="120" data-zone="safe" tabindex="0">If the underlying stays above that level, the coupon is paid and principal is returned in full.</p>
          <p class="payoff-step" data-step data-level="30" data-zone="risk" tabindex="0">If it finishes below the barrier at maturity, you may not get cash back. You may receive shares worth less than the original investment.</p>
        </div>

        <div class="payoff__visual">
          <figure class="payoff-chart" data-payoff aria-label="Payoff at maturity: below the barrier the payoff follows the underlying; at or above it, principal plus coupon is paid">
            <div class="payoff-chart__grid" aria-hidden="true">
              <div class="payoff-chart__row"><span>125 %</span></div>
              <div class="payoff-chart__row"><span>100 %</span></div>
              <div class="payoff-chart__row"><span>75 %</span></div>
              <div class="payoff-chart__row"><span>50 %</span></div>
              <div class="payoff-chart__row"><span>25 %</span></div>
              <div class="payoff-chart__row"><span>0 %</span></div>
            </div>
            <div class="payoff-chart__x" aria-hidden="true">
              <span>0%</span><span>Barrier</span><span>Initial level</span><span>140%</span>
            </div>

            <div class="payoff-chart__plot" data-plot>
              <!-- 786 x 380 at 1440. x and y in % of the plot: 0 % payoff = 100%, payoff at barrier = 44.21%, principal + coupon = 18.68% -->
              <svg class="payoff-chart__svg" aria-hidden="true">
                <rect x="0" y="0" width="36.39%" height="99.74%" fill="#bb2e2e" opacity="0.02"/>
                <rect x="37.02%" y="0" width="62.98%" height="99.74%" fill="#2b86d1" opacity="0.02"/>
                <line x1="36.45%" y1="0" x2="36.45%" y2="100%" stroke="#000" stroke-opacity="0.1"/>
                <line x1="0" y1="100%" x2="36.45%" y2="44.21%" stroke="#bb2e2e" stroke-width="2"/>
                <circle class="pf-dot" cx="0" cy="100%" r="6" fill="#bb2e2e"/>
                <circle class="pf-dot" cx="36.45%" cy="44.21%" r="6" fill="#bb2e2e"/>
              </svg>
              <!-- Principal + coupon: anchored right, length per breakpoint (--safe-w) -->
              <span class="payoff-chart__safe" aria-hidden="true">
                <span class="payoff-chart__label">Principal + coupon</span>
              </span>
              <span class="payoff-chart__point" data-point style="left:21.87%;top:66.53%"></span>
              <div class="payoff-tip" data-tip style="left:21.87%;top:66.53%" role="status">
                <span class="payoff-tip__title" data-tip-title>45% of initial → Payoff 45%</span>
                <span class="payoff-tip__sub" data-tip-sub>Capital at risk</span>
              </div>
            </div>
          </figure>
          <p class="payoff__note">This is the kind of example we walk through live: practical, not theoretical.</p>
        </div>
      </div>
    </section>
