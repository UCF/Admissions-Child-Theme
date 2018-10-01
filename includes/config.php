<?php
/**
 * Handle all theme configuration here
 **/
define( 'ADMISSIONS_THEME_URL', get_stylesheet_directory_uri() );
define( 'ADMISSIONS_THEME_STATIC_URL', ADMISSIONS_THEME_URL . '/static' );
define( 'ADMISSIONS_THEME_CSS_URL', ADMISSIONS_THEME_STATIC_URL . '/css' );
define( 'ADMISSIONS_THEME_JS_URL', ADMISSIONS_THEME_STATIC_URL . '/js' );
define( 'ADMISSIONS_THEME_IMG_URL', ADMISSIONS_THEME_STATIC_URL . '/img' );
define( 'ADMISSIONS_THEME_CUSTOMIZER_PREFIX', 'admissions_' );

// Defines permitted layouts for Spotlights in the
// Two Column template's sidebar.
define( 'ADMISSIONS_TWOCOL_SIDEBAR_SPOTLIGHT_LAYOUTS', serialize( array(
	'square',
	'vertical'
) ) );


/**
 * Adds a custom ACF WYSIWYG toolbar called 'Inline Text' that only includes
 * simple inline text formatting tools and link insertion/deletion.
 *
 * @since 0.0.0
 * @author Jo Dickson
 * @param array $toolbars Array of toolbar information from ACF
 * @return array
 */
function admissions_acf_text_toolbar( $toolbars ) {
	$toolbars['Inline Text'] = array();
	$toolbars['Inline Text'][1] = array( 'bold', 'italic', 'link', 'unlink', 'undo', 'redo' );

	return $toolbars;
}

add_filter( 'acf/fields/wysiwyg/toolbars', 'admissions_acf_text_toolbar' );
