<?php
// Theme foundation
include_once 'includes/config.php';
include_once 'includes/meta.php';
include_once 'includes/template-twocol-functions.php';

// Plugin extras/overrides

if ( class_exists( 'UCF_Degree_Search_Common' ) ) {
	include_once 'includes/degree-search-functions.php';
}
