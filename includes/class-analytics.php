<?php
defined( 'ABSPATH' ) || exit;

class AK_Analytics {

    const TABLE_NAME = 'ak_views';

    // Types d'événements trackés
    const EVENT_VIEW            = 'view';
    const EVENT_DOWNLOAD_MP3    = 'download_mp3';
    const EVENT_DOWNLOAD_PHOTOS = 'download_photos';
    const EVENT_DOWNLOAD_RIDER  = 'download_rider';

    public static function create_table() {
        global $wpdb;
        $table_name      = $wpdb->prefix . self::TABLE_NAME;
        $charset_collate = $wpdb->get_charset_collate();

        // event_type ajouté — dbDelta gère la migration si la table existe déjà
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            epk_id      BIGINT(20) UNSIGNED NOT NULL,
            epk_type    VARCHAR(20) NOT NULL DEFAULT 'artist',
            event_type  VARCHAR(30) NOT NULL DEFAULT 'view',
            viewed_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ip_hash     VARCHAR(64) DEFAULT NULL,
            user_agent  VARCHAR(255) DEFAULT NULL,
            referer     VARCHAR(500) DEFAULT NULL,
            PRIMARY KEY (id),
            KEY epk_id (epk_id),
            KEY viewed_at (viewed_at),
            KEY event_type (event_type)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    // ── Logging ───────────────────────────────────────────────────────────────

    /**
     * Log une vue EPK (Pro only).
     */
    public static function log_view( $epk_id, $epk_type = 'artist' ) {
        if ( ! ak_is_pro() ) return;
        self::log_event( $epk_id, $epk_type, self::EVENT_VIEW );
    }

    /**
     * Log un événement (vue ou téléchargement). Appelé aussi via AJAX.
     */
    public static function log_event( $epk_id, $epk_type = 'artist', $event_type = 'view' ) {
        if ( ! ak_is_pro() ) return;

        global $wpdb;

        $ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if ( self::is_bot( $ua ) ) return;

        $allowed_events = [
            self::EVENT_VIEW,
            self::EVENT_DOWNLOAD_MP3,
            self::EVENT_DOWNLOAD_PHOTOS,
            self::EVENT_DOWNLOAD_RIDER,
        ];
        if ( ! in_array( $event_type, $allowed_events, true ) ) return;

        $ip      = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
        $referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';

        $wpdb->insert(
            $wpdb->prefix . self::TABLE_NAME,
            [
                'epk_id'     => absint( $epk_id ),
                'epk_type'   => sanitize_text_field( $epk_type ),
                'event_type' => sanitize_text_field( $event_type ),
                'viewed_at'  => current_time( 'mysql' ),
                'ip_hash'    => ! empty( $ip ) ? hash( 'sha256', $ip . NONCE_SALT ) : null,
                'user_agent' => sanitize_text_field( substr( $ua, 0, 255 ) ),
                'referer'    => esc_url_raw( substr( $referer, 0, 500 ) ),
            ],
            [ '%d', '%s', '%s', '%s', '%s', '%s', '%s' ]
        );
    }

    // ── Queries ───────────────────────────────────────────────────────────────

    /**
     * Stats pour un EPK donné : vues + téléchargements.
     */
    public static function get_stats( $epk_id, $days = 30 ) {
        global $wpdb;
        $table = $wpdb->prefix . self::TABLE_NAME;

        $total_views = (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table}
             WHERE epk_id = %d AND event_type = 'view'
               AND viewed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)",
            $epk_id, $days
        ) );

        $total_downloads = (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table}
             WHERE epk_id = %d AND event_type = 'download_mp3'
               AND viewed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)",
            $epk_id, $days
        ) );

        $by_day = $wpdb->get_results( $wpdb->prepare(
            "SELECT DATE(viewed_at) as day,
                    SUM(event_type = 'view') as views,
                    SUM(event_type = 'download_mp3') as downloads
             FROM {$table}
             WHERE epk_id = %d AND viewed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)
             GROUP BY DATE(viewed_at)
             ORDER BY day ASC",
            $epk_id, $days
        ) );

        return [
            'total'           => $total_views,
            'total_downloads' => $total_downloads,
            'by_day'          => $by_day,
        ];
    }

    /**
     * Stats globales toutes EPK confondues.
     */
    public static function get_global_stats( $days = 30 ) {
        global $wpdb;
        $table = $wpdb->prefix . self::TABLE_NAME;

        $total = (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table}
             WHERE event_type = 'view' AND viewed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)",
            $days
        ) );

        $total_downloads = (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table}
             WHERE event_type = 'download_mp3' AND viewed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)",
            $days
        ) );

        $by_epk = $wpdb->get_results( $wpdb->prepare(
            "SELECT epk_id, epk_type,
                    SUM(event_type = 'view') as views,
                    SUM(event_type = 'download_mp3') as downloads
             FROM {$table}
             WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)
             GROUP BY epk_id, epk_type
             ORDER BY views DESC",
            $days
        ) );

        $by_day = $wpdb->get_results( $wpdb->prepare(
            "SELECT DATE(viewed_at) as day,
                    SUM(event_type = 'view') as views,
                    SUM(event_type = 'download_mp3') as downloads
             FROM {$table}
             WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)
             GROUP BY DATE(viewed_at)
             ORDER BY day ASC",
            $days
        ) );

        $top_referers = $wpdb->get_results( $wpdb->prepare(
            "SELECT referer, COUNT(*) as views
             FROM {$table}
             WHERE event_type = 'view'
               AND viewed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)
               AND referer IS NOT NULL AND referer != ''
             GROUP BY referer
             ORDER BY views DESC
             LIMIT 10",
            $days
        ) );

        return [
            'total'           => $total,
            'total_downloads' => $total_downloads,
            'by_epk'          => $by_epk,
            'by_day'          => $by_day,
            'top_referers'    => $top_referers,
        ];
    }

    // ── Bot detection ─────────────────────────────────────────────────────────

    private static function is_bot( $ua ) {
        $bots = [ 'bot', 'spider', 'crawl', 'slurp', 'facebookexternalhit', 'Twitterbot', 'LinkedInBot' ];
        foreach ( $bots as $bot ) {
            if ( stripos( $ua, $bot ) !== false ) return true;
        }
        return false;
    }
}
