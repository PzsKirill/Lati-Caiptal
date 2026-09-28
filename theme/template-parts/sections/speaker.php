<?php
/**
 * Section: speaker.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- Who you'll hear from -->
    <section class="section speaker" id="speaker" aria-labelledby="speaker-title">
      <div class="container speaker__inner">
        <figure class="speaker__photo">
          <img src="<?php lati_asset_e( 'image/png/hero-image.png' ); ?>" width="1084" height="1512" alt="Gian-Marco Frey" loading="lazy">
        </figure>

        <div class="speaker__content">
          <p class="eyebrow eyebrow--light">Who you'll hear from</p>
          <h2 class="speaker__name" id="speaker-title">Gian-Marco Frey</h2>
          <p class="speaker__role">Quant and Derivatives Expert</p>
          <p class="speaker__bio">Former Goldman Sachs, Morgan Stanley, and AQR Capital. Has created structured products, fixed index annuities, and related products of the type discussed in this session.</p>
          <a class="speaker__link" href="<?php lati_url_e( 'speaker_linkedin' ); ?>" target="_blank" rel="noopener">
            <img src="<?php lati_asset_e( 'image/svg/linkedin.svg' ); ?>" width="20" height="20" alt="">
            LinkedIn
          </a>

          <div class="speaker__hosted">
            <p class="speaker__hosted-label">Hosted by</p>
            <div class="speaker__badges">
              <img src="<?php lati_asset_e( 'image/svg/logo-light.svg' ); ?>" width="112" height="40" alt="Lati Capital">
              <p class="sec-badge">
                <img src="<?php lati_asset_e( 'image/svg/shield-check.svg' ); ?>" width="42" height="42" alt="">
                <span>SEC-Registered<br>Investment Advisor</span>
              </p>
            </div>
            <p class="speaker__disclaimer">Lati Capital develops account-based investment strategies designed around structured payoff profiles and market scenarios. SEC registration does not imply a certain level of skill or training.</p>
          </div>
        </div>
      </div>
    </section>
