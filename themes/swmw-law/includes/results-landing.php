<?php
/**
 * Results landing page (block editor) replaces the CPT archive at /results/.
 *
 * @package SWMW_Law
 */

namespace SWMW_Law;

/**
 * Slug for the Page that owns the Results listing URL (must match single-result rewrite base).
 */
const SWMW_RESULTS_PAGE_SLUG = 'results';

/**
 * Whether the current request is the Results landing Page (block content + Search & Filter).
 *
 * @return bool
 */
function swmw_law_is_results_landing_page() {
	if ( ! is_page() ) {
		return false;
	}
	if ( is_page_template( 'page-results.php' ) ) {
		return true;
	}
	return is_page( SWMW_RESULTS_PAGE_SLUG );
}

/**
 * The Results landing Page object, if it exists.
 *
 * @return \WP_Post|null
 */
function swmw_law_get_results_landing_page() {
	$page = get_page_by_path( SWMW_RESULTS_PAGE_SLUG );
	return ( $page instanceof \WP_Post ) ? $page : null;
}

/**
 * Permalink to the Results landing Page.
 *
 * @return string Empty string if the Page has not been created yet.
 */
function swmw_law_get_results_page_url() {
	$page = swmw_law_get_results_landing_page();
	return $page ? get_permalink( $page ) : '';
}

/**
 * Point archive URLs and menus at the Page instead of a CPT archive (has_archive is off).
 *
 * @param string $link      Post type archive link.
 * @param string $post_type Post type name.
 * @return string
 */
function swmw_law_filter_results_post_type_archive_link( $link, $post_type ) {
	if ( 'swmw_result' !== $post_type ) {
		return $link;
	}
	$url = swmw_law_get_results_page_url();
	return $url ? $url : $link;
}
add_filter( 'post_type_archive_link', __NAMESPACE__ . '\swmw_law_filter_results_post_type_archive_link', 10, 2 );
