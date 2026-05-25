<?php
/**
 * Plugin Name:  ArtistKit
 * Plugin URI:   https://promotracker.fr/artistkit
 * Description:  Free Electronic Press Kit builder for musicians. Create your artist EPK directly on your WordPress site.
 * Version:      2.0.0
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
define( 'AK_VERSION', '2.0.0' );
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
    AK_Post_Types::register();
    flush_rewrite_rules();

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
    flush_rewrite_rules();
}

// ─── Boot ────────────────────────────────────────────────────────────────────
add_action( 'plugins_loaded', 'ak_init' );

function ak_init() {
    load_plugin_textdomain( 'artistkit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    AK_Post_Types::init();
    AK_Frontend::init();

    if ( is_admin() ) {
        AK_Admin::init();
    }

    /**
     * Extension hook for ArtistKit Pro add-on.
     * The Pro plugin hooks here to bootstrap its features without modifying Free code.
     */
    do_action( 'artistkit_init' );
}

// ─── Settings helper ─────────────────────────────────────────────────────────
function ak_get_setting( $key, $default = '' ) {
    $settings = get_option( 'ak_settings', [] );
    return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
}
