<?php
/**
 * Theme setup, assets, head output and WordPress clean-up.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme supports.
 */
function lati_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'script', 'style', 'navigation-widgets' ) );
}
add_action( 'after_setup_theme', 'lati_setup' );

/**
 * Stylesheets and scripts. Each file is versioned by its modification time,
 * so browsers and page caches pick up changes after a deploy.
 */
function lati_enqueue_assets() {
	$styles = array(
		'base', 'header', 'hero', 'details', 'why', 'curriculum', 'payoff', 'audience',
		'headline', 'framework', 'speaker', 'expect', 'register', 'faq', 'footer',
	);

	$prev = array();
	foreach ( $styles as $name ) {
		$file = "/style/{$name}.css";
		wp_enqueue_style( "lati-{$name}", LATI_URI . $file, $prev, filemtime( LATI_DIR . $file ) );
		$prev = array( "lati-{$name}" );
	}

	wp_enqueue_script( 'lati-chart-data', LATI_URI . '/js/chart-data.js', array(), filemtime( LATI_DIR . '/js/chart-data.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'lati-main', LATI_URI . '/js/main.js', array( 'lati-chart-data' ), filemtime( LATI_DIR . '/js/main.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );

	wp_localize_script(
		'lati-main',
		'LATI',
		array(
			'registerUrl' => esc_url_raw( rest_url( 'lati/v1/register' ) ),
			'errorText'   => 'Something went wrong. Please try again or email us.',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'lati_enqueue_assets' );

/**
 * Head: meta description, social sharing tags, preloads, "js" class.
 */
function lati_head() {
	$title       = lati_get( 'seo_title' );
	$description = lati_get( 'seo_description' );
	$image       = lati_get( 'share_image' );
	$image       = $image ? $image : lati_asset( 'image/png/hero-image.png' );
	?>
	<meta name="description" content="<?php echo esc_attr( $description ); ?>">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo esc_url( home_url( '/' ) ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $image ); ?>">
	<meta name="twitter:card" content="summary_large_image">
	<link rel="preload" href="<?php lati_asset_e( 'fonts/inter-latin.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="<?php lati_asset_e( 'image/png/hero-image.png' ); ?>" as="image">
	<script>document.documentElement.classList.add("js");</script>
	<?php
}
add_action( 'wp_head', 'lati_head', 2 );

/**
 * Document title from the settings.
 *
 * @return string
 */
function lati_document_title() {
	return lati_get( 'seo_title' );
}
add_filter( 'pre_get_document_title', 'lati_document_title' );

/**
 * Admin bar offset for the sticky header when logged in.
 */
function lati_admin_bar_css() {
	if ( ! is_admin_bar_showing() ) {
		return;
	}
	?>
	<style>
		.admin-bar .site-header { top: 32px; }
		@media (max-width: 782px) { .admin-bar .site-header { top: 46px; } }
	</style>
	<?php
}
add_action( 'wp_head', 'lati_admin_bar_css', 99 );

/**
 * Drop WordPress front-end extras this page doesn't use.
 */
function lati_cleanup() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
}
add_action( 'init', 'lati_cleanup' );

/**
 * No block-editor styles on the front end: the page is fully hand-coded.
 */
function lati_dequeue_block_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'lati_dequeue_block_styles', 100 );
