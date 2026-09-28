<?php
/**
 * Section: expect.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- What to expect -->
    <section class="section expect" aria-labelledby="expect-title">
      <div class="container expect__inner">
        <h2 class="expect__title" id="expect-title">What to expect</h2>

        <div class="expect__grid">
          <article class="expect-card">
            <h3 class="expect-card__title">During the webinar</h3>
            <ul class="expect-card__list">
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg class="expect-item__svg--desktop"><use href="#icon-airplay"/></svg><svg class="expect-item__svg--mobile"><use href="#icon-play"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Live</span><span class="expect-item__desc"><?php lati_e( 'duration' ); ?> expert presentation</span></span>
              </li>
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg><use href="#icon-file"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Examples</span><span class="expect-item__desc">Practical payoff examples</span></span>
              </li>
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg><use href="#icon-diagram"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Diagrams</span><span class="expect-item__desc">Visual diagrams explaining barriers and risk</span></span>
              </li>
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg><use href="#icon-question"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Q&amp;A</span><span class="expect-item__desc">Live Q&amp;A</span></span>
              </li>
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg><use href="#icon-archive"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Materials</span><span class="expect-item__desc">Recording + Slides</span></span>
              </li>
            </ul>
          </article>

          <article class="expect-card expect-card--accent">
            <h3 class="expect-card__title">After the webinar</h3>
            <ul class="expect-card__list">
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg class="expect-item__svg--desktop"><use href="#icon-airplay"/></svg><svg class="expect-item__svg--mobile"><use href="#icon-play"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Recording</span><span class="expect-item__desc"><?php lati_e( 'after_recording' ); ?></span></span>
              </li>
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg><use href="#icon-file"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Slides</span><span class="expect-item__desc"><?php lati_e( 'after_slides' ); ?></span></span>
              </li>
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg><use href="#icon-diagram"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Webinar summary</span><span class="expect-item__desc"><?php lati_e( 'after_summary' ); ?></span></span>
              </li>
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg><use href="#icon-question"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Example payoff diagrams</span><span class="expect-item__desc"><?php lati_e( 'after_diagrams' ); ?></span></span>
              </li>
              <li class="expect-item">
                <span class="expect-item__icon" aria-hidden="true"><svg><use href="#icon-archive"/></svg></span>
                <span class="expect-item__text"><span class="expect-item__title">Additional materials</span><span class="expect-item__desc"><?php lati_e( 'after_materials' ); ?></span></span>
              </li>
            </ul>
          </article>
        </div>
      </div>
    </section>
