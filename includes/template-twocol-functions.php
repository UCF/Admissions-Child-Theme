<?php

/**
 * Determine if a given Spotlight's layout is suitable for use in the
 * Two Column template's sidebar.
 *
 * Suitable layouts are defined in ADMISSIONS_TWOCOL_SIDEBAR_SPOTLIGHT_LAYOUTS.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $spotlight  a WP_Post object for the Spotlight post
 * @return bool
 */
function admissions_twocol_spotlight_layout_isvalid( $spotlight ) {
	$valid = false;

	if ( $spotlight instanceof WP_Post ) {
		$layout = $spotlight->ucf_spotlight_layout;

		$valid_layouts = unserialize( ADMISSIONS_TWOCOL_SIDEBAR_SPOTLIGHT_LAYOUTS );
		if ( in_array( $layout, $valid_layouts ) ) {
			$valid = true;
		}
	}

	return $valid;
}


/**
 * Displays a Spotlight in the Two Column page template's sidebar.
 *
 * Performs sanity checks to ensure the Spotlight's layout is suitable for the
 * sidebar (e.g. isn't using the 'horizontal' layout.)
 *
 * Requires the UCF Spotlights Plugin.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $spotlight  a WP_Post object for the Spotlight post
 * @return string  Spotlight markup, or empty string on failure
 */
function admissions_twocol_display_spotlight( $spotlight ) {
	$retval = '';

	if (
		$spotlight instanceof WP_Post
		&& class_exists( 'UCF_Spotlight_Common' )
		&& admissions_twocol_spotlight_layout_isvalid( $spotlight )
	) {
		$retval = UCF_Spotlight_Common::display_spotlight( $spotlight );
	}

	return $retval;
}


/**
 * Displays a Section in the Two Column page template's sidebar.
 *
 * Requires the UCF Sections Plugin.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param object $section  a WP_Post object for the Section post
 * @return string  Section markup, or empty string on failure
 */
function admissions_twocol_display_section( $section ) {
	$retval = '';

	if (
		$section instanceof WP_Post
		&& class_exists( 'UCF_Section_Common' )
	) {
		$retval = UCF_Section_Common::display_section( array(
			'id' => $section->ID
		) );
	}

	return $retval;
}
