<?php
/**
 * Plugin Name:  ArtistKit
 * Plugin URI:   https://promotracker.fr/artistkit
 * Description:  Free Electronic Press Kit builder for musicians. Create your artist EPK directly on your WordPress site.
 * Version:      2.0.3
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author:       PromoTracker
 * Author URI:   https://promotracker.fr
 * License:      GPL v2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  artistkit
 * Domain Path:  /languages
 */

defined( 'ABSPATH' ) || exit;

// ─── Constants ───────────────────────────────────────────────────────────────
define( 'AK_VERSION', '2.0.3' );
define( 'AK_FILE',    __FILE__ );
define( 'AK_DIR',     plugin_dir_path( __FILE__ ) );
define( 'AK_URL',     plugin_dir_url( __FILE__ ) );

// ─── Includes (Free only) ────────────────────────────────────────────────────
require_once AK_DIR . 'includes/class-post-types.php';
require_once AK_DIR . 'includes/class-admin.php';
require_once AK_DIR . 'includes/class-frontend.php';

// ─── Activation / Deactivation ───────────────────────────────────────────────
register_activation_hook( __FILE__, 'ak_activate' );
register_deactivation_hook( __FILE__, 'ak_deactivate' );

function ak_activate() {
    // Defer flush_rewrite_rules() to the next `init` hook.
    //
    // The CPT and custom rewrite rules are registered on `init` (priority 10),
    // which fires AFTER the activation callback. Flushing here would rebuild
    // the rules cache without knowledge of `ak_artist_epk` and `^epk/?$`,
    // causing /epk URLs to 404 until the user manually saves permalinks.
    //
    // Setting a flag here and flushing on `init` priority 99 ensures the CPT
    // and rewrite rules are registered first.
    update_option( 'ak_needs_rewrite_flush', '1' );

    if ( ! get_option( 'ak_settings' ) ) {
        $defaults = [
            'accent_color' => '#8b5cf6',
            'font_pair'    => 'inter',
            'template'     => 'dark-minimal',
            'logo_url'     => '',
        ];
        update_option( 'ak_settings', apply_filters( 'artistkit_settings_defaults', $defaults ) );
    }
}

function ak_deactivate() {
    // Safe to flush directly here — the plugin has been running, so the CPT
    // and rewrite rules were registered on `init` earlier in this request.
    // Flushing now removes our rules from the cache before deactivation.
    flush_rewrite_rules();
}

// ─── Boot ────────────────────────────────────────────────────────────────────
add_action( 'plugins_loaded', 'ak_init' );

function ak_init() {
    // load_plugin_textdomain() is no longer needed for plugins hosted on WordPress.org
    // (WP 4.6+ loads translations automatically from the languages/ directory).

    AK_Post_Types::init();
    AK_Frontend::init();

    if ( is_admin() ) {
        AK_Admin::init();
    }

    // Flush rewrite rules once after activation, AFTER the CPT and custom
    // rewrites have been registered. Priority 99 runs after the default
    // priority 10 callbacks that register the CPT and rewrite rules.
    add_action( 'init', 'ak_maybe_flush_rewrite_rules', 99 );

    /**
     * Extension hook for ArtistKit Pro add-on.
     * The Pro plugin hooks here to bootstrap its features without modifying Free code.
     */
    do_action( 'artistkit_init' );
}

function ak_maybe_flush_rewrite_rules() {
    if ( get_option( 'ak_needs_rewrite_flush' ) ) {
        flush_rewrite_rules();
        delete_option( 'ak_needs_rewrite_flush' );
    }
}

// ─── Settings helper ─────────────────────────────────────────────────────────
function ak_get_setting( $key, $default = '' ) {
    $settings = get_option( 'ak_settings', [] );
    return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
}
