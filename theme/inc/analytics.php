<?php
/**
 * Google Tag Manager and custom tracking code from the Customizer.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;

/**
 * GTM container + extra head code, as early as possible in <head>.
 */
function lati_analytics_head() {
	if ( is_customize_preview() ) {
		return;
	}

	$gtm = lati_get( 'gtm_id' );
	if ( $gtm ) {
		?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo esc_js( $gtm ); ?>');</script>
		<!-- End Google Tag Manager -->
		<?php
	}

	$code = lati_get( 'head_code' );
	if ( $code ) {
		echo $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- admin-only raw code, sanitized on save.
	}
}
add_action( 'wp_head', 'lati_analytics_head', 0 );   // before <title> and everything else wp_head prints

/**
 * GTM noscript + extra body code right after <body>.
 */
function lati_analytics_body() {
	if ( is_customize_preview() ) {
		return;
	}

	$gtm = lati_get( 'gtm_id' );
	if ( $gtm ) {
		printf(
			"<!-- Google Tag Manager (noscript) -->\n" .
			'<noscript><iframe src="%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n" .
			"<!-- End Google Tag Manager (noscript) -->\n",
			esc_url( 'https://www.googletagmanager.com/ns.html?id=' . $gtm )
		);
	}

	$code = lati_get( 'body_code' );
	if ( $code ) {
		echo $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- admin-only raw code, sanitized on save.
	}
}
add_action( 'wp_body_open', 'lati_analytics_body' );
