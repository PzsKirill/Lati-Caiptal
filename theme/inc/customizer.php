<?php
/**
 * Appearance → Customize → Webinar landing.
 * Every placeholder of the design is one field here; a value entered once
 * appears everywhere it is used on the page.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;

/**
 * Keep raw code only for users allowed to post unfiltered HTML (admins).
 *
 * @param string $value Submitted code.
 * @return string
 */
function lati_sanitize_code( $value ) {
	return current_user_can( 'unfiltered_html' ) ? (string) $value : wp_kses_post( $value );
}

/**
 * Comma-separated list of valid e-mail addresses.
 *
 * @param string $value Submitted list.
 * @return string
 */
function lati_sanitize_emails( $value ) {
	$emails = array_filter( array_map( 'sanitize_email', explode( ',', (string) $value ) ), 'is_email' );
	return implode( ', ', $emails );
}

/**
 * GTM container ID or empty.
 *
 * @param string $value Submitted ID.
 * @return string
 */
function lati_sanitize_gtm( $value ) {
	$value = strtoupper( trim( (string) $value ) );
	return preg_match( '/^GTM-[A-Z0-9]+$/', $value ) ? $value : '';
}

/**
 * Social network choice.
 *
 * @param string $value Submitted value.
 * @return string
 */
function lati_sanitize_network( $value ) {
	return in_array( $value, array( 'x', 'instagram' ), true ) ? $value : 'x';
}

/**
 * Register the panel, sections and fields.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function lati_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'lati',
		array(
			'title'       => 'Webinar landing',
			'description' => 'Content and settings of the landing page. Empty fields show the design placeholder, e.g. [Date].',
			'priority'    => 20,
		)
	);

	$sections = array(
		'lati_details'   => array( 'Webinar details', 'Shown in the hero, the orange details strip, the registration block and the FAQ.' ),
		'lati_content'   => array( 'Example & materials', 'Payoff example and what participants receive after the webinar.' ),
		'lati_links'     => array( 'Links', 'Speaker profile and the second social network in the footer.' ),
		'lati_form'      => array( 'Registration form', 'Every registration is saved in Registrations (with CSV export). Optionally also e-mailed and/or sent to a webhook (Zapier, Make, Zoom, HubSpot…).' ),
		'lati_analytics' => array( 'Analytics & tracking', 'Recommended: add GA4, Meta Pixel etc. inside Google Tag Manager and enter only the container ID here. A successful registration pushes the "webinar_registration" event to the dataLayer.' ),
		'lati_seo'       => array( 'SEO & sharing', 'Browser title, search description and the image shown when the link is shared.' ),
	);

	$priority = 10;
	foreach ( $sections as $id => $section ) {
		$wp_customize->add_section(
			$id,
			array(
				'title'       => $section[0],
				'description' => $section[1],
				'panel'       => 'lati',
				'priority'    => $priority,
			)
		);
		$priority += 10;
	}

	$defaults = lati_defaults();

	// key => [section, label, type, sanitize, description].
	$fields = array(
		'date'             => array( 'lati_details', 'Date', 'text', 'sanitize_text_field', 'e.g. Thursday, November 12' ),
		'time'             => array( 'lati_details', 'Time', 'text', 'sanitize_text_field', 'e.g. 12:00 PM ET' ),
		'duration'         => array( 'lati_details', 'Duration', 'text', 'sanitize_text_field', 'e.g. 60 minutes — also used in "Exactly what you\'ll be able to evaluate after …"' ),
		'platform'         => array( 'lati_details', 'Platform', 'text', 'sanitize_text_field', 'e.g. Zoom' ),
		'cost'             => array( 'lati_details', 'Cost', 'text', 'sanitize_text_field', 'e.g. Free' ),
		'language'         => array( 'lati_details', 'Language', 'text', 'sanitize_text_field', 'e.g. English' ),
		'deadline'         => array( 'lati_details', 'Registration deadline', 'text', 'sanitize_text_field', '' ),

		'amount'           => array( 'lati_content', 'Example: amount invested', 'text', 'sanitize_text_field', 'e.g. $100,000' ),
		'coupon'           => array( 'lati_content', 'Example: coupon, %', 'text', 'sanitize_text_field', 'Number only, e.g. 12 — the % sign is added automatically' ),
		'after_recording'  => array( 'lati_content', 'After the webinar: Recording', 'text', 'sanitize_text_field', '' ),
		'after_slides'     => array( 'lati_content', 'After the webinar: Slides', 'text', 'sanitize_text_field', '' ),
		'after_summary'    => array( 'lati_content', 'After the webinar: Webinar summary', 'text', 'sanitize_text_field', '' ),
		'after_diagrams'   => array( 'lati_content', 'After the webinar: Example payoff diagrams', 'text', 'sanitize_text_field', '' ),
		'after_materials'  => array( 'lati_content', 'After the webinar: Additional materials', 'text', 'sanitize_text_field', '' ),

		'speaker_linkedin' => array( 'lati_links', 'Speaker LinkedIn URL', 'url', 'esc_url_raw', '' ),
		'social_url'       => array( 'lati_links', 'Footer: second social network URL', 'url', 'esc_url_raw', 'LinkedIn (company page) is always shown.' ),

		'notify_emails'    => array( 'lati_form', 'E-mail notifications to', 'text', 'lati_sanitize_emails', 'One or more addresses, comma-separated. Leave empty to only store registrations.' ),
		'webhook_url'      => array( 'lati_form', 'Webhook URL', 'url', 'esc_url_raw', 'Each registration is POSTed here as JSON.' ),
		'success_title'    => array( 'lati_form', 'Success message: title', 'text', 'sanitize_text_field', '' ),
		'success_text'     => array( 'lati_form', 'Success message: text', 'textarea', 'sanitize_textarea_field', '' ),

		'gtm_id'           => array( 'lati_analytics', 'Google Tag Manager container ID', 'text', 'lati_sanitize_gtm', 'Format GTM-XXXXXXX' ),
		'head_code'        => array( 'lati_analytics', 'Extra code in <head>', 'textarea', 'lati_sanitize_code', 'Only if something can\'t go through GTM.' ),
		'body_code'        => array( 'lati_analytics', 'Extra code after <body>', 'textarea', 'lati_sanitize_code', '' ),

		'seo_title'        => array( 'lati_seo', 'Page title', 'text', 'sanitize_text_field', '' ),
		'seo_description'  => array( 'lati_seo', 'Meta description', 'textarea', 'sanitize_textarea_field', '' ),
	);

	foreach ( $fields as $key => $field ) {
		$wp_customize->add_setting(
			'lati_' . $key,
			array(
				'default'           => in_array( $field[0], array( 'lati_details', 'lati_content' ), true ) ? '' : $defaults[ $key ],
				'sanitize_callback' => $field[3],
			)
		);
		$wp_customize->add_control(
			'lati_' . $key,
			array(
				'section'     => $field[0],
				'label'       => $field[1],
				'type'        => $field[2],
				'description' => $field[4],
				'input_attrs' => in_array( $field[0], array( 'lati_details', 'lati_content' ), true ) ? array( 'placeholder' => $defaults[ $key ] ) : array(),
			)
		);
	}

	// Second social network: X or Instagram.
	$wp_customize->add_setting(
		'lati_social_network',
		array(
			'default'           => 'x',
			'sanitize_callback' => 'lati_sanitize_network',
		)
	);
	$wp_customize->add_control(
		'lati_social_network',
		array(
			'section'  => 'lati_links',
			'label'    => 'Footer: second social network',
			'type'     => 'radio',
			'choices'  => array(
				'x'         => 'X (Twitter)',
				'instagram' => 'Instagram',
			),
			'priority' => 5,
		)
	);

	// Share image.
	$wp_customize->add_setting(
		'lati_share_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'lati_share_image',
			array(
				'section'     => 'lati_seo',
				'label'       => 'Share image (1200 × 630)',
				'description' => 'Shown when the link is posted in LinkedIn, Slack, messengers. Defaults to the hero photo.',
			)
		)
	);
}
add_action( 'customize_register', 'lati_customize_register' );
