<?php

/**
 * Enqueue front-end css and js.
 *
 * @author Jo Dickson
 * @since 1.0.0
 **/
function admissions_enqueue_frontend_assets() {
	// Register child theme stylesheet
	$theme = wp_get_theme();
	$theme_version = $theme->get( 'Version' );
	wp_enqueue_style( 'style-child', ADMISSIONS_THEME_CSS_URL . '/style.min.css', array( 'style' ), $theme_version );
}

add_action( 'wp_enqueue_scripts', 'admissions_enqueue_frontend_assets', 11, 0 );
