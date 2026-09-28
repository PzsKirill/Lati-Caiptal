<?php
/**
 * Header: document head, icon sprite, site header. Opens <main>.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'template-parts/sprite' ); ?>

  <header class="site-header" data-header>
    <div class="site-header__inner">
      <div class="site-header__bar">
      <a class="site-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>#top" aria-label="Lati Capital — back to top">
        <img src="<?php lati_asset_e( 'image/svg/logo.svg' ); ?>" width="118" height="40" alt="Lati Capital">
      </a>

      <nav class="site-header__nav" aria-label="Main">
        <ul class="site-header__menu">
          <li><a href="#learn">What you'll learn</a></li>
          <li><a href="#speaker">Speaker</a></li>
          <li><a href="#faq">FAQ</a></li>
        </ul>
      </nav>

      <div class="site-header__actions">
        <a class="btn btn--sm site-header__cta" href="#register">
          Register
          <span class="btn__icon" aria-hidden="true"><svg><use href="#icon-arrow"/></svg></span>
        </a>
        <button class="site-header__burger" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu" data-menu-toggle>
          <span></span>
        </button>
      </div>
      </div>
    </div>

    <!-- Tablet / mobile menu -->
    <div class="menu-backdrop" data-menu-backdrop aria-hidden="true"></div>
    <div class="mobile-menu" id="mobile-menu" data-menu hidden>
      <ul class="mobile-menu__list">
        <li><a href="#learn">What you'll learn</a></li>
        <li><a href="#speaker">Speaker</a></li>
        <li><a href="#faq">FAQ</a></li>
      </ul>
      <a class="btn mobile-menu__cta" href="#register">
        Register for the Webinar
        <span class="btn__icon" aria-hidden="true"><svg><use href="#icon-arrow"/></svg></span>
      </a>
    </div>
  </header>

  <main id="top">
