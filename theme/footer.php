<?php
/**
 * Footer: closes <main>, site footer, scripts.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
  </main>

  <footer class="site-footer">
    <div class="container site-footer__inner">
      <div class="site-footer__top">
        <a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>#top" aria-label="Lati Capital — back to top">
          <img src="<?php lati_asset_e( 'image/svg/logo-light.svg' ); ?>" width="112" height="40" alt="Lati Capital">
        </a>
        <p class="site-footer__tagline">Understand the formula <br>before you accept the risk.</p>
        <div class="site-footer__social">
          <a href="https://www.linkedin.com/company/lati-capital/" target="_blank" rel="noopener" aria-label="Lati Capital on LinkedIn">
            <img src="<?php lati_asset_e( 'image/svg/linkedin.svg' ); ?>" width="32" height="32" alt="">
          </a>
          <?php $lati_network = lati_get( 'social_network' ); ?>
          <a href="<?php lati_url_e( 'social_url' ); ?>" target="_blank" rel="noopener" aria-label="Lati Capital on <?php echo 'instagram' === $lati_network ? 'Instagram' : 'X'; ?>">
            <img src="<?php lati_asset_e( 'image/svg/' . ( 'instagram' === $lati_network ? 'instagram' : 'x' ) . '.svg' ); ?>" width="32" height="32" alt="">
          </a>
        </div>
      </div>

      <div class="site-footer__bottom">
        <ul class="site-footer__legal">
          <li><a href="https://cms.lati-capital.com/v1/files/lati-capital/privacy-policy" target="_blank" rel="noopener">Privacy Policy</a></li>
          <li><a href="https://cms.lati-capital.com/v1/files/lati-capital/terms-of-use" target="_blank" rel="noopener">Terms of Use</a></li>
          <li><a href="https://cms.lati-capital.com/v1/files/lati-capital/adv-2a-brochure" target="_blank" rel="noopener">Form ADV Part 2A</a></li>
          <li><a href="https://cms.lati-capital.com/v1/files/lati-capital/form-crs" target="_blank" rel="noopener">Form CRS</a></li>
        </ul>
        <p>© Lati Capital</p>
      </div>
    </div>
  </footer>

<?php wp_footer(); ?>
</body>
</html>
