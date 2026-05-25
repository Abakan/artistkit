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
delete_option( 'ak_settings' );
delete_option( 'ak_version' );
delete_option( 'ak_needs_rewrite_flush' );

// Delete Artist EPK CPT data
$artists = get_posts( [
    'post_type'   => 'ak_artist_epk',
    'numberposts' => -1,
    'post_status' => 'any',
    'fields'      => 'ids',
] );

foreach ( $artists as $artist_id ) {
    wp_delete_post( $artist_id, true );
}

// Clean orphan postmeta
global $wpdb;
$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})" );
