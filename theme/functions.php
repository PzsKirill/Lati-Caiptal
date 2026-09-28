<?php
/**
 * Lati Capital Webinar — theme bootstrap.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;

define( 'LATI_VERSION', '1.0.0' );
define( 'LATI_DIR', get_template_directory() );
define( 'LATI_URI', get_template_directory_uri() );

require LATI_DIR . '/inc/helpers.php';
require LATI_DIR . '/inc/setup.php';
require LATI_DIR . '/inc/customizer.php';
require LATI_DIR . '/inc/registrations.php';
require LATI_DIR . '/inc/analytics.php';
