<?php

namespace WPOrg_Learn\Redirects;

add_action( 'template_redirect', __NAMESPACE__ . '\wporg_learn_redirect_meetings' );
add_action( 'template_redirect', __NAMESPACE__ . '\wporg_learn_redirect_old_urls' );

/**
 * Redirect meeting posts to associated link
 *
 * @return void
 */
function wporg_learn_redirect_meetings() {
	global $post;

	if ( is_singular( array( 'meeting' ) ) ) {

		if ( ! empty( $post->ID ) ) {

			$redirect = wp_http_validate_url( get_post_meta( $post->ID, 'link', true ) );

			if ( $redirect && wp_redirect( $redirect ) ) {
				exit;
			}
		}
	}

}

/**
 * Redirect old pages to their new homes.
 *
 * @return void
 */
function wporg_learn_redirect_old_urls() {
	if ( ! is_404() ) {
		return;
	}

	$redirects = array(
		// Source => Destination, any characters after the source will be appended to the destination.
		'/workshop/'                      => '/tutorial/',
		'/workshops'                      => '/tutorials',
		'/social-learning'                => '/online-workshops',
		'/workshop-presenter-application' => '/tutorial-presenter-application',
		'/report-content-errors'          => '/report-content-feedback',
	);

	// Use `REQUEST_URI` rather than `$wp->request`, to get the entire source URI including url parameters.
	$request = $_SERVER['REQUEST_URI'] ?? '';

	foreach ( $redirects as $source => $destination ) {
		if ( str_starts_with( $request, $source ) ) {
			$redirect = $destination;

			// Append any extra request parameters.
			if ( strlen( $request ) > strlen( $source ) ) {
				$redirect .= substr( $request, strlen( $source ) );
			}

			wp_safe_redirect( $redirect );
			die();
		}
	}
}
