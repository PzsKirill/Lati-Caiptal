<?php
/**
 * Template helpers: editable landing settings and asset URLs.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;

/**
 * Editable settings and their defaults.
 * Defaults are the design placeholders, so the page looks like the mockup
 * until the client fills the values in Appearance → Customize → Webinar landing.
 *
 * @return array<string, string>
 */
function lati_defaults() {
	return array(
		// Webinar details (repeated in the hero, details strip, registration, FAQ).
		'date'             => '[Date]',
		'time'             => '[Time]',
		'duration'         => '[Duration]',
		'platform'         => '[Platform]',
		'cost'             => '[Cost]',
		'language'         => '[Language]',
		'deadline'         => '[Deadline]',

		// Payoff example.
		'amount'           => '[Amount]',
		'coupon'           => '[Coupon]',

		// What participants get after the webinar.
		'after_recording'  => '[Recording]',
		'after_slides'     => '[Slides]',
		'after_summary'    => '[Summary]',
		'after_diagrams'   => '[Diagrams]',
		'after_materials'  => '[Materials]',

		// Links.
		'speaker_linkedin' => '',
		'social_network'   => 'x',
		'social_url'       => '',

		// Registration form.
		'notify_emails'    => '',
		'webhook_url'      => '',
		'success_title'    => "You're registered",
		'success_text'     => "Thank you! We'll email you the access details and a reminder before the session.",

		// Analytics. GTM container from the client (GA4, Meta Pixel etc. are set up inside it).
		'gtm_id'           => 'GTM-P9MM697P',
		'head_code'        => '',
		'body_code'        => '',

		// SEO.
		'seo_title'        => 'How Do Structured Products Generate a 10-15% Coupon? | Lati Capital Webinar',
		'seo_description'  => 'Educational webinar by Lati Capital: how structured products build a 10-15% coupon, where the downside sits, and how to evaluate them.',
		'share_image'      => '',
	);
}

/**
 * Setting value, falling back to its default when empty.
 *
 * @param string $key Setting key without the "lati_" prefix.
 * @return string
 */
function lati_get( $key ) {
	$defaults = lati_defaults();
	$value    = get_theme_mod( 'lati_' . $key, '' );

	if ( is_string( $value ) ) {
		$value = trim( $value );
	}

	return ( '' === $value || null === $value ) ? ( $defaults[ $key ] ?? '' ) : (string) $value;
}

/**
 * Echo an escaped setting value.
 *
 * @param string $key Setting key.
 */
function lati_e( $key ) {
	echo esc_html( lati_get( $key ) );
}

/**
 * URL of a file inside the theme.
 *
 * @param string $path Path relative to the theme root.
 * @return string
 */
function lati_asset( $path ) {
	return LATI_URI . '/' . ltrim( $path, '/' );
}

/**
 * Echo an escaped theme file URL.
 *
 * @param string $path Path relative to the theme root.
 */
function lati_asset_e( $path ) {
	echo esc_url( lati_asset( $path ) );
}

/**
 * Link target for an optional URL setting: the URL, or "#" while it's empty.
 *
 * @param string $key Setting key.
 */
function lati_url_e( $key ) {
	$url = lati_get( $key );
	echo esc_url( '' !== $url ? $url : '#' );
}
