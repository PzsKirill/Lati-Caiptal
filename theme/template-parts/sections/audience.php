<?php
/**
 * Section: audience.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- Who this webinar is for -->
    <section class="section audience" aria-labelledby="audience-title">
      <div class="container audience__inner">
        <h2 class="audience__title" id="audience-title">Who this webinar is for</h2>

        <div class="audience__cards">
          <article class="audience-card">
            <div class="audience-card__body">
              <span class="audience-card__icon" aria-hidden="true"><svg><use href="#icon-briefcase"/></svg></span>
              <h3 class="audience-card__title">Investors</h3>
              <ul class="check-list audience-card__list">
                <li><svg aria-hidden="true"><use href="#icon-check"/></svg>already invest through brokerage or wealth-management accounts</li>
                <li><svg aria-hidden="true"><use href="#icon-check"/></svg>own or are considering structured notes</li>
                <li><svg aria-hidden="true"><use href="#icon-check"/></svg>are looking for income while managing downside exposure</li>
                <li><svg aria-hidden="true"><use href="#icon-check"/></svg>want to understand the relationship between yield, risk, liquidity, and fees</li>
                <li><svg aria-hidden="true"><use href="#icon-check"/></svg>want to evaluate products based on economics rather than headline coupon</li>
              </ul>
            </div>
            <div class="audience-card__media">
              <img src="<?php lati_asset_e( 'image/png/audience-investors.png' ); ?>" width="1024" height="594" alt="" loading="lazy">
            </div>
          </article>

          <article class="audience-card audience-card--flip">
            <div class="audience-card__body">
              <span class="audience-card__icon" aria-hidden="true"><svg><use href="#icon-chart"/></svg></span>
              <h3 class="audience-card__title">RIAs, Wealth Managers &amp; Financial Advisors</h3>
              <ul class="check-list audience-card__list">
                <li><svg aria-hidden="true"><use href="#icon-check"/></svg>evaluate structured products for affluent clients</li>
                <li><svg aria-hidden="true"><use href="#icon-check"/></svg>need to explain payoff structures and downside scenarios clearly</li>
                <li><svg aria-hidden="true"><use href="#icon-check"/></svg>care about suitability, transparency, liquidity, and client outcomes</li>
                <li><svg aria-hidden="true"><use href="#icon-check"/></svg>want to better understand the economics behind the products they recommend</li>
              </ul>
            </div>
            <div class="audience-card__media">
              <img src="<?php lati_asset_e( 'image/png/audience-advisors.png' ); ?>" width="942" height="594" alt="" loading="lazy">
            </div>
          </article>
        </div>
      </div>
    </section>
