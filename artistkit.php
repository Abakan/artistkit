<?php
/**
 * Plugin Name:  ArtistKit
 * Plugin URI:   https://promotracker.fr/artistkit
 * Description:  Professional Electronic Press Kit builder for musicians. Create artist & release EPKs directly on your WordPress site.
 * Version:      1.3.7
 * Author:       PromoTracker
 * Author URI:   https://promotracker.fr
 * License:      GPL v2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  artistkit
 * Domain Path:  /languages
 */

defined( 'ABSPATH' ) || exit;

// ─── Constants ───────────────────────────────────────────────────────────────
define( 'AK_VERSION',     '1.3.7' );
define( 'AK_FILE',        __FILE__ );
define( 'AK_DIR',         plugin_dir_path( __FILE__ ) );
define( 'AK_URL',         plugin_dir_url( __FILE__ ) );
define( 'AK_LICENSE_API', 'https://promotracker.fr/api/artistkit/validate-license' );

// ─── Autoload ─────────────────────────────────────────────────────────────────
require_once AK_DIR . 'includes/class-post-types.php';
require_once AK_DIR . 'includes/class-license.php';
require_once AK_DIR . 'includes/class-admin.php';
require_once AK_DIR . 'includes/class-frontend.php';
require_once AK_DIR . 'includes/class-analytics.php';

// ─── Activation / Deactivation ───────────────────────────────────────────────
register_activation_hook( __FILE__, 'ak_activate' );
register_deactivation_hook( __FILE__, 'ak_deactivate' );

function ak_activate() {
    AK_Post_Types::register();
    flush_rewrite_rules();
    AK_Analytics::create_table();

    // Default settings
    if ( ! get_option( 'ak_settings' ) ) {
        update_option( 'ak_settings', [
            'accent_color' => '#8b5cf6',
            'font_pair'    => 'inter',
            'template'     => 'dark-minimal',
            'license_key'  => '',
            'license_valid' => false,
        ] );
    }
}

function ak_deactivate() {
    flush_rewrite_rules();
}

// ─── Boot ─────────────────────────────────────────────────────────────────────
add_action( 'plugins_loaded', 'ak_init' );

function ak_init() {
    load_plugin_textdomain( 'artistkit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    AK_Post_Types::init();
    AK_Frontend::init();

    if ( is_admin() ) {
        AK_Admin::init();
        AK_License::init();
    }
}

// ─── Helper: is Pro? ─────────────────────────────────────────────────────────
function ak_is_pro() {
    $settings = get_option( 'ak_settings', [] );
    return ! empty( $settings['license_valid'] ) && $settings['license_valid'] === true;
}

function ak_get_setting( $key, $default = '' ) {
    $settings = get_option( 'ak_settings', [] );
    return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
}
