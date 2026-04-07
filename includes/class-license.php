<?php
defined( 'ABSPATH' ) || exit;

class AK_License {

    public static function init() {
        add_action( 'admin_post_ak_activate_license', [ __CLASS__, 'handle_activation' ] );
        add_action( 'admin_post_ak_deactivate_license', [ __CLASS__, 'handle_deactivation' ] );
    }

    /**
     * Validate license key via API PromoTracker.
     */
    public static function validate( $license_key ) {
        if ( empty( $license_key ) ) {
            return [ 'valid' => false, 'message' => __( 'Clé de licence vide.', 'artistkit' ) ];
        }

        // ── Validation via API PromoTracker ────────────────────────────────
        $response = wp_remote_post( AK_LICENSE_API, [
            'timeout' => 15,
            'body'    => [
                'license_key' => sanitize_text_field( $license_key ),
                'domain'      => home_url(),
                'plugin'      => 'artistkit',
                'version'     => AK_VERSION,
            ],
        ] );

        if ( is_wp_error( $response ) ) {
            return [ 'valid' => false, 'message' => $response->get_error_message() ];
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( empty( $body ) || ! isset( $body['valid'] ) ) {
            return [ 'valid' => false, 'message' => __( 'Réponse invalide du serveur de licence.', 'artistkit' ) ];
        }

        return $body;
    }

    /**
     * Handle license activation form submission.
     */
    public static function handle_activation() {
        check_admin_referer( 'ak_license_action' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Permission refusée.', 'artistkit' ) );
        }

        $license_key = isset( $_POST['ak_license_key'] ) ? sanitize_text_field( $_POST['ak_license_key'] ) : '';
        $result      = self::validate( $license_key );
        $settings    = get_option( 'ak_settings', [] );

        if ( $result['valid'] ) {
            $settings['license_key']   = $license_key;
            $settings['license_valid'] = true;
            $settings['license_email'] = isset( $result['email'] ) ? sanitize_email( $result['email'] ) : '';
            update_option( 'ak_settings', $settings );
            $redirect_status = 'activated';
        } else {
            $settings['license_key']   = $license_key;
            $settings['license_valid'] = false;
            update_option( 'ak_settings', $settings );
            $redirect_status = 'invalid';
        }

        wp_redirect( add_query_arg( [
            'page'           => 'artistkit-settings',
            'license_status' => $redirect_status,
        ], admin_url( 'admin.php' ) ) );
        exit;
    }

    /**
     * Handle license deactivation.
     */
    public static function handle_deactivation() {
        check_admin_referer( 'ak_license_action' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Permission refusée.', 'artistkit' ) );
        }

        $settings = get_option( 'ak_settings', [] );

        // Notify API to free the domain slot
        if ( ! empty( $settings['license_key'] ) ) {
            wp_remote_post( AK_LICENSE_API . '/deactivate', [
                'timeout' => 10,
                'body'    => [
                    'license_key' => $settings['license_key'],
                    'domain'      => home_url(),
                ],
            ] );
        }

        $settings['license_valid'] = false;
        $settings['license_key']   = '';
        $settings['license_email'] = '';
        update_option( 'ak_settings', $settings );

        wp_redirect( add_query_arg( [
            'page'           => 'artistkit-settings',
            'license_status' => 'deactivated',
        ], admin_url( 'admin.php' ) ) );
        exit;
    }
}
