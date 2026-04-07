<?php
defined( 'ABSPATH' ) || exit;

class AK_Frontend {

    public static function init() {
        add_action( 'template_redirect', [ __CLASS__, 'handle_epk_request' ] );

        // AJAX endpoint pour tracker les téléchargements (connecté et non-connecté)
        add_action( 'wp_ajax_ak_log_event',        [ __CLASS__, 'ajax_log_event' ] );
        add_action( 'wp_ajax_nopriv_ak_log_event', [ __CLASS__, 'ajax_log_event' ] );
    }

    /**
     * AJAX handler : log un événement de téléchargement depuis le frontend.
     */
    public static function ajax_log_event() {
        // Vérifier le nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ak_log_event' ) ) {
            wp_send_json_error( 'invalid_nonce', 403 );
        }

        $epk_id     = isset( $_POST['epk_id'] )     ? absint( $_POST['epk_id'] )                              : 0;
        $epk_type   = isset( $_POST['epk_type'] )   ? sanitize_text_field( wp_unslash( $_POST['epk_type'] ) ) : 'artist';
        $event_type = isset( $_POST['event_type'] ) ? sanitize_text_field( wp_unslash( $_POST['event_type'] ) ) : '';

        if ( ! $epk_id || ! $event_type ) {
            wp_send_json_error( 'missing_params', 400 );
        }

        AK_Analytics::log_event( $epk_id, $epk_type, $event_type );
        wp_send_json_success();
    }

    public static function handle_epk_request() {
        $ak_page = get_query_var( 'ak_page' );
        if ( ! $ak_page ) return;

        if ( $ak_page === 'artist' ) {
            self::render_artist_epk();
        } elseif ( $ak_page === 'release' ) {
            self::render_release_epk();
        }
    }

    // ── Artist EPK ───────────────────────────────────────────────────────────

    private static function render_artist_epk() {
        $posts = get_posts( [
            'post_type'      => 'ak_artist_epk',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        ] );

        if ( empty( $posts ) ) {
            self::render_404( __( 'EPK Artiste non trouvé.', 'artistkit' ) );
            return;
        }

        $epk  = $posts[0];
        $data = self::get_artist_data( $epk );

        AK_Analytics::log_view( $epk->ID, 'artist' );

        self::render_template( 'epk-artist', $data );
    }

    // ── Release EPK ──────────────────────────────────────────────────────────

    private static function render_release_epk() {
        if ( ! ak_is_pro() ) {
            self::render_404( __( 'Les EPK Release nécessitent ArtistKit Pro.', 'artistkit' ) );
            return;
        }

        $slug = get_query_var( 'ak_slug' );
        if ( ! $slug ) {
            wp_redirect( home_url( '/epk' ) );
            exit;
        }

        $posts = get_posts( [
            'post_type'   => 'ak_release_epk',
            'name'        => $slug,
            'post_status' => 'publish',
        ] );

        if ( empty( $posts ) ) {
            self::render_404( __( 'EPK Release introuvable.', 'artistkit' ) );
            return;
        }

        $epk  = $posts[0];
        $data = self::get_release_data( $epk );

        // Password protection
        if ( get_post_meta( $epk->ID, 'ak_password_protected', true ) === '1' ) {
            $password = get_post_meta( $epk->ID, 'ak_password', true );
            if ( ! self::check_password( $epk->ID, $password ) ) {
                self::render_password_form( $epk->ID );
                return;
            }
        }

        AK_Analytics::log_view( $epk->ID, 'release' );

        self::render_template( 'epk-release', $data );
    }

    // ── Render template ──────────────────────────────────────────────────────

    private static function render_template( $template, $data ) {
        $settings = get_option( 'ak_settings', [] );
        $theme    = $settings['template'] ?? 'dark-minimal';

        status_header( 200 );
        nocache_headers();

        ob_start();
        // Explicit variable assignment instead of extract() for security
        $post               = $data['post']               ?? null;
        $settings           = $data['settings']           ?? [];
        $site_name          = $data['site_name']          ?? '';
        $site_logo          = $data['site_logo']          ?? '';
        $artist_name        = $data['artist_name']        ?? '';
        $genre              = $data['genre']              ?? '';
        $bio_short          = $data['bio_short']          ?? '';
        $bio_long           = $data['bio_long']           ?? '';
        $monthly_listeners  = $data['monthly_listeners']  ?? '';
        $total_streams      = $data['total_streams']      ?? '';
        $location           = $data['location']           ?? '';
        $founded_year       = $data['founded_year']       ?? '';
        $cover_image        = $data['cover_image']        ?? '';
        $spotify_url        = $data['spotify_url']        ?? '';
        $apple_music_url    = $data['apple_music_url']    ?? '';
        $youtube_url        = $data['youtube_url']        ?? '';
        $soundcloud_url     = $data['soundcloud_url']     ?? '';
        $bandcamp_url       = $data['bandcamp_url']       ?? '';
        $deezer_url         = $data['deezer_url']         ?? '';
        $instagram_url      = $data['instagram_url']      ?? '';
        $tiktok_url         = $data['tiktok_url']         ?? '';
        $facebook_url       = $data['facebook_url']       ?? '';
        $website_url        = $data['website_url']        ?? '';
        $press_quotes       = $data['press_quotes']       ?? [];
        $contact_booking    = $data['contact_booking']    ?? '';
        $contact_management = $data['contact_management'] ?? '';
        $contact_press      = $data['contact_press']      ?? '';
        $rider_url          = $data['rider_url']          ?? '';
        $photos_zip_url     = $data['photos_zip_url']     ?? '';
        $audio_mp3_url      = $data['audio_mp3_url']      ?? '';
        $audio_mp3_label    = $data['audio_mp3_label']    ?? '';
        $audio_downloadable = $data['audio_downloadable'] ?? '';
        $embed_type         = $data['embed_type']         ?? '';
        $embed_url          = $data['embed_url']          ?? '';
        $embed_height       = $data['embed_height']       ?? '152';
        $featured_tracks    = $data['featured_tracks']    ?? [];
        $releases           = $data['releases']           ?? [];
        $release_title      = $data['release_title']      ?? '';
        $release_type       = $data['release_type']       ?? '';
        $release_date       = $data['release_date']       ?? '';
        $release_label      = $data['release_label']      ?? '';
        $release_genre      = $data['release_genre']      ?? '';
        $tracklist          = $data['tracklist']          ?? '';
        $release_story      = $data['release_story']      ?? '';
        $talking_points     = $data['talking_points']     ?? '';
        $release_quotes     = $data['release_quotes']     ?? '';
        $artwork_url        = $data['artwork_url']        ?? '';
        $promo_photos_zip   = $data['promo_photos_zip']   ?? '';
        $clip_url           = $data['clip_url']           ?? '';
        $bpm                = $data['bpm']                ?? '';
        $key                = $data['key']                ?? '';
        $duration           = $data['duration']           ?? '';
        $has_radio_edit     = $data['has_radio_edit']     ?? '';
        $isrc               = $data['isrc']               ?? '';
        $artist_epk_url     = $data['artist_epk_url']     ?? home_url( '/epk' );
        include AK_DIR . 'templates/' . $template . '.php';
        $content = ob_get_clean();

        echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- full standalone HTML page
        exit;
    }

    // ── Data getters ─────────────────────────────────────────────────────────

    public static function get_artist_data( $post ) {
        $id = $post->ID;
        return [
            'post'              => $post,
            'artist_name'       => $post->post_title,
            'genre'             => get_post_meta( $id, 'ak_genre', true ),
            'bio_short'         => get_post_meta( $id, 'ak_bio_short', true ),
            'bio_long'          => get_post_meta( $id, 'ak_bio_long', true ),
            'monthly_listeners' => get_post_meta( $id, 'ak_monthly_listeners', true ),
            'total_streams'     => get_post_meta( $id, 'ak_total_streams', true ),
            'location'          => get_post_meta( $id, 'ak_location', true ),
            'founded_year'      => get_post_meta( $id, 'ak_founded_year', true ),
            'cover_image'       => get_the_post_thumbnail_url( $id, 'large' ),
            'spotify_url'       => get_post_meta( $id, 'ak_spotify_url', true ),
            'apple_music_url'   => get_post_meta( $id, 'ak_apple_music_url', true ),
            'youtube_url'       => get_post_meta( $id, 'ak_youtube_url', true ),
            'soundcloud_url'    => get_post_meta( $id, 'ak_soundcloud_url', true ),
            'bandcamp_url'      => get_post_meta( $id, 'ak_bandcamp_url', true ),
            'deezer_url'        => get_post_meta( $id, 'ak_deezer_url', true ),
            'instagram_url'     => get_post_meta( $id, 'ak_instagram_url', true ),
            'tiktok_url'        => get_post_meta( $id, 'ak_tiktok_url', true ),
            'facebook_url'      => get_post_meta( $id, 'ak_facebook_url', true ),
            'website_url'       => get_post_meta( $id, 'ak_website_url', true ),
            'press_quotes'      => get_post_meta( $id, 'ak_press_quotes', true ) ?: [],
            'contact_booking'   => get_post_meta( $id, 'ak_contact_booking', true ),
            'contact_management'=> get_post_meta( $id, 'ak_contact_management', true ),
            'contact_press'     => get_post_meta( $id, 'ak_contact_press', true ),
            'rider_url'         => get_post_meta( $id, 'ak_rider_url', true ),
            'photos_zip_url'    => get_post_meta( $id, 'ak_photos_zip_url', true ),
            'audio_mp3_url'     => get_post_meta( $id, 'ak_audio_mp3_url', true ),
            'audio_mp3_label'   => get_post_meta( $id, 'ak_audio_mp3_label', true ),
            'audio_downloadable'=> get_post_meta( $id, 'ak_audio_downloadable', true ),
            'embed_type'        => get_post_meta( $id, 'ak_embed_type', true ),
            'embed_url'         => get_post_meta( $id, 'ak_embed_url', true ),
            'embed_height'      => get_post_meta( $id, 'ak_embed_height', true ) ?: '152',
            'featured_tracks'   => get_post_meta( $id, 'ak_featured_tracks', true ) ?: [],
            'releases'          => self::get_published_releases(),
            'settings'          => get_option( 'ak_settings', [] ),
            'site_name'         => get_bloginfo( 'name' ),
            'site_logo'         => self::get_site_logo(),
        ];
    }

    public static function get_release_data( $post ) {
        $id = $post->ID;
        return [
            'post'               => $post,
            'release_title'      => $post->post_title,
            'release_type'       => get_post_meta( $id, 'ak_release_type', true ),
            'release_date'       => get_post_meta( $id, 'ak_release_date', true ),
            'release_label'      => get_post_meta( $id, 'ak_release_label', true ),
            'release_genre'      => get_post_meta( $id, 'ak_release_genre', true ),
            'tracklist'          => get_post_meta( $id, 'ak_tracklist', true ),
            'release_story'      => get_post_meta( $id, 'ak_release_story', true ),
            'talking_points'     => get_post_meta( $id, 'ak_talking_points', true ),
            'release_quotes'     => get_post_meta( $id, 'ak_release_quotes', true ),
            'artwork_url'        => get_post_meta( $id, 'ak_artwork_url', true ) ?: get_the_post_thumbnail_url( $id, 'large' ),
            'promo_photos_zip'   => get_post_meta( $id, 'ak_promo_photos_zip', true ),
            'spotify_url'        => get_post_meta( $id, 'ak_spotify_url', true ),
            'apple_music_url'    => get_post_meta( $id, 'ak_apple_music_url', true ),
            'youtube_url'        => get_post_meta( $id, 'ak_youtube_url', true ),
            'soundcloud_url'     => get_post_meta( $id, 'ak_soundcloud_url', true ),
            'bandcamp_url'       => get_post_meta( $id, 'ak_bandcamp_url', true ),
            'deezer_url'         => get_post_meta( $id, 'ak_deezer_url', true ),
            'embed_type'         => get_post_meta( $id, 'ak_embed_type', true ),
            'embed_url'          => get_post_meta( $id, 'ak_embed_url', true ),
            'embed_height'       => get_post_meta( $id, 'ak_embed_height', true ) ?: '152',
            'clip_url'           => get_post_meta( $id, 'ak_clip_url', true ),
            'audio_mp3_url'      => get_post_meta( $id, 'ak_audio_mp3_url', true ),
            'audio_mp3_label'    => get_post_meta( $id, 'ak_audio_mp3_label', true ),
            'audio_downloadable' => get_post_meta( $id, 'ak_audio_downloadable', true ),
            'bpm'                => get_post_meta( $id, 'ak_bpm', true ),
            'key'                => get_post_meta( $id, 'ak_key', true ),
            'duration'           => get_post_meta( $id, 'ak_duration', true ),
            'has_radio_edit'     => get_post_meta( $id, 'ak_has_radio_edit', true ),
            'isrc'               => get_post_meta( $id, 'ak_isrc', true ),
            'artist_epk_url'     => home_url( '/epk' ),
            'settings'           => get_option( 'ak_settings', [] ),
            'site_name'          => get_bloginfo( 'name' ),
            'site_logo'          => self::get_site_logo(),
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private static function get_published_releases() {
        return get_posts( [
            'post_type'      => 'ak_release_epk',
            'post_status'    => 'publish',
            'posts_per_page' => 10,
            'orderby'        => 'meta_value',
            'meta_key'       => 'ak_release_date',
            'order'          => 'DESC',
        ] );
    }

    private static function get_site_logo() {
        // Priorité 1 : logo uploadé dans les réglages ArtistKit
        $settings = get_option( 'ak_settings', [] );
        if ( ! empty( $settings['logo_url'] ) ) {
            return $settings['logo_url'];
        }
        // Priorité 2 : logo du thème WordPress
        $logo_id = get_theme_mod( 'custom_logo' );
        if ( $logo_id ) {
            $image = wp_get_attachment_image_src( $logo_id, 'full' );
            return $image ? $image[0] : '';
        }
        return '';
    }

    private static function check_password( $post_id, $password ) {
        $cookie_key  = 'ak_epk_' . absint( $post_id );
        $hashed      = wp_hash( $password . $post_id );

        if ( isset( $_COOKIE[ $cookie_key ] ) && hash_equals( $hashed, $_COOKIE[ $cookie_key ] ) ) {
            return true;
        }

        if ( isset( $_POST['ak_epk_password'], $_POST['ak_epk_nonce'] )
            && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ak_epk_nonce'] ) ), 'ak_epk_password_' . $post_id )
            && hash_equals( $password, sanitize_text_field( wp_unslash( $_POST['ak_epk_password'] ) ) )
        ) {
            setcookie( $cookie_key, $hashed, time() + DAY_IN_SECONDS, '/', '', is_ssl(), true );
            return true;
        }

        return false;
    }

    private static function render_password_form( $post_id ) {
        status_header( 200 );
        $wrong = isset( $_POST['ak_epk_password'] );
        include AK_DIR . 'templates/password-form.php';
        exit;
    }

    private static function render_404( $message = '' ) {
        status_header( 404 );
        include AK_DIR . 'templates/404.php';
        exit;
    }
}
