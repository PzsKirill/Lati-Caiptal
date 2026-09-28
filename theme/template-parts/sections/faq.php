<?php
/**
 * Section: faq.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
    <!-- FAQ -->
    <section class="section faq" id="faq" aria-labelledby="faq-title">
      <div class="container faq__inner">
        <div class="faq__head">
          <p class="eyebrow">FAQ</p>
          <h2 class="faq__title" id="faq-title">Questions, <br>answered plainly.</h2>
        </div>

        <!-- TODO: answers 2–4 are drafts (not in the design) — confirm wording with the client -->
        <div class="faq__list" data-accordion>
          <div class="faq-item is-open">
            <h3><button class="faq-item__question" type="button" aria-expanded="true" aria-controls="faq-a1" id="faq-q1">Is this webinar a product pitch?</button></h3>
            <span class="faq-item__icon" aria-hidden="true"></span>
            <div class="faq-item__answer" id="faq-a1" role="region" aria-labelledby="faq-q1">
              <div><p>No. This is an educational session focused on mechanics and evaluation, not a sales deck.</p></div>
            </div>
          </div>
          <div class="faq-item">
            <h3><button class="faq-item__question" type="button" aria-expanded="false" aria-controls="faq-a2" id="faq-q2">Who is this webinar for?</button></h3>
            <span class="faq-item__icon" aria-hidden="true"></span>
            <div class="faq-item__answer" id="faq-a2" role="region" aria-labelledby="faq-q2">
              <div><p>Investors who hold or are considering structured notes, and RIAs, wealth managers, and financial advisors who evaluate these products for clients.</p></div>
            </div>
          </div>
          <div class="faq-item">
            <h3><button class="faq-item__question" type="button" aria-expanded="false" aria-controls="faq-a3" id="faq-q3">Will this be live, and will I get the recording if I miss it?</button></h3>
            <span class="faq-item__icon" aria-hidden="true"></span>
            <div class="faq-item__answer" id="faq-a3" role="region" aria-labelledby="faq-q3">
              <div><p>Yes. The session runs live on <?php lati_e( 'platform' ); ?>. Everyone who registers receives the recording afterwards, so register even if you can't attend.</p></div>
            </div>
          </div>
          <div class="faq-item">
            <h3><button class="faq-item__question" type="button" aria-expanded="false" aria-controls="faq-a4" id="faq-q4">How is my registration data used?</button></h3>
            <span class="faq-item__icon" aria-hidden="true"></span>
            <div class="faq-item__answer" id="faq-a4" role="region" aria-labelledby="faq-q4">
              <div><p>We use your details to send webinar access, reminders, and the recording. Occasional updates from Lati Capital are sent only if you opt in. See our <a href="https://cms.lati-capital.com/v1/files/lati-capital/privacy-policy" target="_blank" rel="noopener"><u>Privacy Policy</u></a>.</p></div>
            </div>
          </div>
        </div>
      </div>
    </section>
