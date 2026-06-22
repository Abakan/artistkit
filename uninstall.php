<?php
/**
 * Uninstall ArtistKit.
 *
 * Cleans up all plugin data when the user deletes the plugin via the WordPress admin.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete plugin options
delete_option( 'artistkit_settings' );
delete_option( 'artistkit_version' );
delete_option( 'artistkit_needs_rewrite_flush' );

// Delete Artist EPK CPT data
$ak_artists = get_posts( [
    'post_type'   => 'artistkit_epk',
    'numberposts' => -1,
    'post_status' => 'any',
    'fields'      => 'ids',
] );

foreach ( $ak_artists as $ak_artist_id ) {
    wp_delete_post( $ak_artist_id, true );
}

// Clean orphan postmeta. Direct query is acceptable here — uninstall.php is a
// one-shot teardown, not a runtime path, so the lack of caching is expected.
global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- uninstall teardown
$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})" );
