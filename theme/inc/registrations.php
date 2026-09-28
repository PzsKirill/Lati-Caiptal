<?php
/**
 * Webinar registrations.
 *
 * Every submission is stored as a private "Registration" entry (with CSV
 * export), then optionally e-mailed and/or POSTed to a webhook — so any
 * delivery the client chooses (inbox, Zoom, HubSpot, Zapier/Make) is a
 * setting, not new code.
 *
 * Spam protection without nonces (page caching would make them stale):
 * honeypot field, minimum fill time and a per-IP rate limit.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;

const LATI_REG_TYPE  = 'lati_registration';
const LATI_REG_ROLES = array( 'Investor', 'RIA', 'Wealth Manager', 'Advisor', 'Other' );
const LATI_REG_META  = array( 'first_name', 'last_name', 'email', 'role', 'company', 'updates', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'referrer' );

/**
 * Private post type holding the registrations.
 */
function lati_register_post_type() {
	register_post_type(
		LATI_REG_TYPE,
		array(
			'labels'          => array(
				'name'          => 'Registrations',
				'singular_name' => 'Registration',
				'menu_name'     => 'Registrations',
				'all_items'     => 'All registrations',
				'edit_item'     => 'Registration',
				'search_items'  => 'Search registrations',
				'not_found'     => 'No registrations yet.',
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => false,
			'menu_position'   => 3,
			'menu_icon'       => 'dashicons-groups',
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'lati_register_post_type' );

/* -------------------------------------------------------------------------
 * Front end: REST endpoint (JS) and admin-post fallback (no JS)
 * ---------------------------------------------------------------------- */

/**
 * POST /wp-json/lati/v1/register
 */
function lati_register_route() {
	register_rest_route(
		'lati/v1',
		'/register',
		array(
			'methods'             => 'POST',
			'callback'            => 'lati_rest_register',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'lati_register_route' );

/**
 * REST callback.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function lati_rest_register( $request ) {
	$result = lati_process_registration( (array) $request->get_params() );

	if ( is_wp_error( $result ) ) {
		return new WP_REST_Response(
			array(
				'ok'      => false,
				'message' => $result->get_error_message(),
			),
			(int) ( $result->get_error_data()['status'] ?? 400 )
		);
	}

	return new WP_REST_Response(
		array(
			'ok'    => true,
			'title' => lati_get( 'success_title' ),
			'text'  => lati_get( 'success_text' ),
		),
		200
	);
}

/**
 * Fallback for browsers without JavaScript: regular form POST.
 */
function lati_post_register() {
	$result = lati_process_registration( wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.NonceVerification -- public form, see file header.
	$status = is_wp_error( $result ) ? 'error' : 'ok';
	wp_safe_redirect( add_query_arg( 'registered', $status, home_url( '/' ) ) . '#register' );
	exit;
}
add_action( 'admin_post_nopriv_lati_register', 'lati_post_register' );
add_action( 'admin_post_lati_register', 'lati_post_register' );

/**
 * Validate, store and deliver one registration.
 *
 * @param array $input Raw form fields.
 * @return int|WP_Error Registration ID or error.
 */
function lati_process_registration( array $input ) {
	// Honeypot: real people never see this field. Pretend success to bots.
	if ( ! empty( $input['website'] ) ) {
		return 0;
	}

	// Too fast to be a person (timestamp set by JS on page load).
	$started = isset( $input['started'] ) ? (int) $input['started'] : 0;
	if ( $started && ( time() * 1000 - $started ) < 2500 ) {
		return 0;
	}

	// Rate limit: 5 submissions per 10 minutes per IP.
	$ip_key = 'lati_rl_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$hits   = (int) get_transient( $ip_key );
	if ( $hits >= 5 ) {
		return new WP_Error( 'lati_rate', 'Too many attempts. Please try again in a few minutes.', array( 'status' => 429 ) );
	}
	set_transient( $ip_key, $hits + 1, 10 * MINUTE_IN_SECONDS );

	$data = array(
		'first_name' => sanitize_text_field( $input['first_name'] ?? '' ),
		'last_name'  => sanitize_text_field( $input['last_name'] ?? '' ),
		'email'      => sanitize_email( $input['email'] ?? '' ),
		'role'       => sanitize_text_field( $input['role'] ?? '' ),
		'company'    => sanitize_text_field( $input['company'] ?? '' ),
		'updates'    => empty( $input['updates'] ) ? 'no' : 'yes',
		'referrer'   => esc_url_raw( $input['referrer'] ?? '' ),
	);
	foreach ( array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ) as $utm ) {
		$data[ $utm ] = sanitize_text_field( $input[ $utm ] ?? '' );
	}

	if ( '' === $data['first_name'] || '' === $data['last_name'] ) {
		return new WP_Error( 'lati_name', 'Please enter your first and last name.' );
	}
	if ( ! is_email( $data['email'] ) ) {
		return new WP_Error( 'lati_email', 'Please enter a valid email address.' );
	}
	if ( ! in_array( $data['role'], LATI_REG_ROLES, true ) ) {
		return new WP_Error( 'lati_role', 'Please choose your role.' );
	}

	// One entry per e-mail: a repeat submission updates it instead of duplicating.
	$existing = get_posts(
		array(
			'post_type'      => LATI_REG_TYPE,
			'post_status'    => 'private',
			'meta_key'       => 'email', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $data['email'], // phpcs:ignore WordPress.DB.SlowDBQuery
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	$post = array(
		'post_type'   => LATI_REG_TYPE,
		'post_status' => 'private',
		'post_title'  => trim( $data['first_name'] . ' ' . $data['last_name'] ) . ' — ' . $data['email'],
	);

	if ( $existing ) {
		$post['ID'] = $existing[0];
		$id         = wp_update_post( $post, true );
		$is_repeat  = true;
	} else {
		$id        = wp_insert_post( $post, true );
		$is_repeat = false;
	}

	if ( is_wp_error( $id ) ) {
		return new WP_Error( 'lati_save', 'Registration could not be saved. Please try again.', array( 'status' => 500 ) );
	}

	foreach ( $data as $key => $value ) {
		// A repeat submission keeps the first-touch source and company if the new ones are empty.
		if ( $is_repeat && '' === $value && in_array( $key, array( 'company', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ), true ) ) {
			continue;
		}
		update_post_meta( $id, $key, $value );
	}

	lati_notify_email( $id, $data, $is_repeat );
	lati_notify_webhook( $id, $data, $is_repeat );

	return $id;
}

/**
 * E-mail notification to the addresses set in the Customizer.
 *
 * @param int   $id        Registration ID.
 * @param array $data      Fields.
 * @param bool  $is_repeat Whether this e-mail had registered before.
 */
function lati_notify_email( $id, array $data, $is_repeat ) {
	$to = lati_get( 'notify_emails' );
	if ( ! $to ) {
		return;
	}

	$name  = trim( $data['first_name'] . ' ' . $data['last_name'] );
	$lines = array(
		'Name: ' . $name,
		'Email: ' . $data['email'],
		'Role: ' . $data['role'],
		'Company: ' . ( $data['company'] ? $data['company'] : '—' ),
		'Agreed to updates: ' . $data['updates'],
	);
	foreach ( array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'referrer' ) as $key ) {
		if ( $data[ $key ] ) {
			$lines[] = ucfirst( str_replace( '_', ' ', $key ) ) . ': ' . $data[ $key ];
		}
	}
	$lines[] = '';
	$lines[] = 'All registrations: ' . admin_url( 'edit.php?post_type=' . LATI_REG_TYPE );

	wp_mail(
		array_map( 'trim', explode( ',', $to ) ),
		( $is_repeat ? 'Repeat webinar registration: ' : 'New webinar registration: ' ) . $name,
		implode( "\n", $lines ),
		array( 'Reply-To: ' . $name . ' <' . $data['email'] . '>' )
	);
}

/**
 * JSON POST to the webhook set in the Customizer (non-blocking).
 *
 * @param int   $id        Registration ID.
 * @param array $data      Fields.
 * @param bool  $is_repeat Whether this e-mail had registered before.
 */
function lati_notify_webhook( $id, array $data, $is_repeat ) {
	$url = lati_get( 'webhook_url' );
	if ( ! $url ) {
		return;
	}

	wp_remote_post(
		$url,
		array(
			'blocking' => false,
			'timeout'  => 5,
			'headers'  => array( 'Content-Type' => 'application/json' ),
			'body'     => wp_json_encode(
				array_merge(
					$data,
					array(
						'id'            => $id,
						'repeat'        => $is_repeat,
						'registered_at' => gmdate( 'c' ),
						'webinar'       => array(
							'date'     => lati_get( 'date' ),
							'time'     => lati_get( 'time' ),
							'platform' => lati_get( 'platform' ),
						),
					)
				)
			),
		)
	);
}

/* -------------------------------------------------------------------------
 * Admin: list columns, read-only details, CSV export
 * ---------------------------------------------------------------------- */

/**
 * List table columns.
 *
 * @return array
 */
function lati_reg_columns() {
	return array(
		'cb'           => '<input type="checkbox" />',
		'lati_name'    => 'Name',
		'lati_email'   => 'Email',
		'lati_role'    => 'Role',
		'lati_company' => 'Company',
		'lati_updates' => 'Updates',
		'lati_source'  => 'Source',
		'date'         => 'Registered',
	);
}
add_filter( 'manage_' . LATI_REG_TYPE . '_posts_columns', 'lati_reg_columns' );

/**
 * List table cells.
 *
 * @param string $column  Column key.
 * @param int    $post_id Registration ID.
 */
function lati_reg_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'lati_name':
			printf(
				'<strong><a href="%s">%s</a></strong>',
				esc_url( get_edit_post_link( $post_id ) ),
				esc_html( trim( get_post_meta( $post_id, 'first_name', true ) . ' ' . get_post_meta( $post_id, 'last_name', true ) ) )
			);
			break;
		case 'lati_email':
			$email = get_post_meta( $post_id, 'email', true );
			printf( '<a href="mailto:%1$s">%1$s</a>', esc_html( $email ) );
			break;
		case 'lati_role':
			echo esc_html( get_post_meta( $post_id, 'role', true ) );
			break;
		case 'lati_company':
			echo esc_html( get_post_meta( $post_id, 'company', true ) );
			break;
		case 'lati_updates':
			echo 'yes' === get_post_meta( $post_id, 'updates', true ) ? '✓' : '—';
			break;
		case 'lati_source':
			$source = array_filter( array( get_post_meta( $post_id, 'utm_source', true ), get_post_meta( $post_id, 'utm_campaign', true ) ) );
			echo esc_html( $source ? implode( ' / ', $source ) : '—' );
			break;
	}
}
add_action( 'manage_' . LATI_REG_TYPE . '_posts_custom_column', 'lati_reg_column_content', 10, 2 );

/**
 * Read-only details box on the registration screen.
 */
function lati_reg_meta_box() {
	add_meta_box(
		'lati_reg_details',
		'Registration details',
		function ( $post ) {
			echo '<table class="widefat striped"><tbody>';
			foreach ( LATI_REG_META as $key ) {
				$value = get_post_meta( $post->ID, $key, true );
				printf( '<tr><th style="width:180px">%s</th><td>%s</td></tr>', esc_html( ucfirst( str_replace( '_', ' ', $key ) ) ), esc_html( '' !== $value ? $value : '—' ) );
			}
			echo '</tbody></table>';
		},
		LATI_REG_TYPE,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_' . LATI_REG_TYPE, 'lati_reg_meta_box' );

/**
 * "Export CSV" button above the list.
 *
 * @param array $views List views.
 * @return array
 */
function lati_reg_export_button( $views ) {
	$url                  = wp_nonce_url( admin_url( 'admin-post.php?action=lati_export_registrations' ), 'lati_export' );
	$views['lati_export'] = sprintf( '<a href="%s" class="button" style="margin-left:8px">Export CSV</a>', esc_url( $url ) );
	return $views;
}
add_filter( 'views_edit-' . LATI_REG_TYPE, 'lati_reg_export_button' );

/**
 * Stream all registrations as CSV.
 */
function lati_export_registrations() {
	if ( ! current_user_can( 'edit_posts' ) || ! check_admin_referer( 'lati_export' ) ) {
		wp_die( 'Not allowed.' );
	}

	$ids = get_posts(
		array(
			'post_type'      => LATI_REG_TYPE,
			'post_status'    => 'private',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'orderby'        => 'date',
			'order'          => 'ASC',
		)
	);

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=webinar-registrations-' . gmdate( 'Y-m-d' ) . '.csv' );

	$out = fopen( 'php://output', 'w' );
	fwrite( $out, "\xEF\xBB\xBF" ); // UTF-8 BOM so Excel opens it correctly.
	fputcsv( $out, array_merge( array( 'registered_at' ), LATI_REG_META ) );
	foreach ( $ids as $id ) {
		$row = array( get_the_date( 'Y-m-d H:i', $id ) );
		foreach ( LATI_REG_META as $key ) {
			$row[] = get_post_meta( $id, $key, true );
		}
		fputcsv( $out, $row );
	}
	fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
}
add_action( 'admin_post_lati_export_registrations', 'lati_export_registrations' );
