<?php
defined( 'ABSPATH' ) || exit;

class AK_Frontend {

    public static function init() {
        add_action( 'template_redirect', [ __CLASS__, 'handle_epk_request' ] );
    }

    public static function handle_epk_request() {
        $ak_page = get_query_var( 'ak_page' );
        if ( ! $ak_page ) return;

        /**
         * Extension hook — Pro intercepts to handle 'release' page type.
         * Pro hooks at priority 5 (before Free's default handling).
         */
        do_action( 'artistkit_handle_epk_request', $ak_page );

        if ( $ak_page === 'artist' ) {
            self::render_artist_epk();
        }
    }

    // ── Artist EPK ───────────────────────────────────────────────────────────

    public static function render_artist_epk() {
        $posts = get_posts( [
            'post_type'      => 'ak_artist_epk',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        ] );

        if ( empty( $posts ) ) {
            self::render_404( __( 'Artist EPK not found.', 'artistkit' ) );
            return;
        }

        $epk  = $posts[0];
        $data = self::get_artist_data( $epk );

        /**
         * Extension hook — Pro tracks the view here via analytics.
         */
        do_action( 'artistkit_before_render', $epk, 'artist' );

        self::render_template( 'epk-artist', $data );
    }

    // ── Render template ──────────────────────────────────────────────────────

    public static function render_template( $template, $data ) {
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
        include AK_DIR . 'templates/' . $template . '.php';
        $content = ob_get_clean();

        echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- full standalone HTML page
        exit;
    }

    // ── Data getter ──────────────────────────────────────────────────────────

    public static function get_artist_data( $post ) {
        $id = $post->ID;

        $data = [
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
            'embed_height'      => '152',
            'settings'          => get_option( 'ak_settings', [] ),
            'site_name'         => get_bloginfo( 'name' ),
            'site_logo'         => self::get_site_logo(),
        ];

        /**
         * Extension filter — Pro adds featured_tracks, releases, etc.
         */
        return apply_filters( 'artistkit_artist_data', $data, $post );
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public static function get_site_logo() {
        // Priority 1: logo uploaded in ArtistKit settings
        $settings = get_option( 'ak_settings', [] );
        if ( ! empty( $settings['logo_url'] ) ) {
            return $settings['logo_url'];
        }
        // Priority 2: WordPress theme logo
        $logo_id = get_theme_mod( 'custom_logo' );
        if ( $logo_id ) {
            $image = wp_get_attachment_image_src( $logo_id, 'full' );
            return $image ? $image[0] : '';
        }
        return '';
    }

    public static function render_404( $message = '' ) {
        status_header( 404 );
        $template_404 = AK_DIR . 'templates/404.php';
        if ( file_exists( $template_404 ) ) {
            include $template_404;
        } else {
            echo esc_html( $message );
        }
        exit;
    }
}
