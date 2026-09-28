<?php
/**
 * Landing page: all sections in order.
 *
 * @package Lati
 */

defined( 'ABSPATH' ) || exit;

get_header();

foreach ( array( 'hero', 'details', 'why', 'curriculum', 'payoff', 'audience', 'headline', 'framework', 'speaker', 'expect', 'register', 'faq' ) as $lati_section ) {
	get_template_part( 'template-parts/sections/' . $lati_section );
}

get_footer();
