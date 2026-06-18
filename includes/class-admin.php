<?php
defined( 'ABSPATH' ) || exit;

class AK_Admin {

    public static function init() {
        add_action( 'admin_menu',                  [ __CLASS__, 'register_menus' ] );
        add_action( 'admin_enqueue_scripts',       [ __CLASS__, 'enqueue_assets' ] );
        add_action( 'add_meta_boxes',              [ __CLASS__, 'register_meta_boxes' ] );
        add_action( 'save_post',                   [ __CLASS__, 'save_meta_boxes' ] );
        add_action( 'admin_post_ak_save_settings', [ __CLASS__, 'save_settings' ] );
        add_action( 'admin_notices',               [ __CLASS__, 'admin_notices' ] );
        add_filter( 'post_row_actions',            [ __CLASS__, 'add_epk_row_action' ], 10, 2 );
    }

    public static function add_epk_row_action( $actions, $post ) {
        if ( $post->post_type === 'ak_artist_epk' && $post->post_status === 'publish' ) {
            $actions['view_epk'] = '<a href="' . esc_url( home_url( '/epk' ) ) . '" target="_blank">' . esc_html__( 'View EPK', 'artistkit' ) . '</a>';
        }
        return $actions;
    }

    // ── Menus ────────────────────────────────────────────────────────────────

    public static function register_menus() {
        add_menu_page(
            __( 'ArtistKit', 'artistkit' ),
            __( 'ArtistKit', 'artistkit' ),
            'edit_posts',
            'artistkit',
            [ __CLASS__, 'page_dashboard' ],
            'dashicons-microphone',
            30
        );

        add_submenu_page(
            'artistkit',
            __( 'My Artist EPK', 'artistkit' ),
            __( 'Artist EPK', 'artistkit' ),
            'edit_posts',
            'edit.php?post_type=ak_artist_epk'
        );

        add_submenu_page(
            'artistkit',
            __( 'Settings', 'artistkit' ),
            __( 'Settings', 'artistkit' ),
            'manage_options',
            'artistkit-settings',
            [ __CLASS__, 'page_settings' ]
        );

        /**
         * Extension hook — Pro adds its submenus (Releases, Analytics) here.
         * Parent slug is passed so Pro can register correctly.
         */
        do_action( 'artistkit_admin_menu', 'artistkit' );

        // Upgrade page added LAST so it appears at the bottom of the submenu list.
        add_submenu_page(
            'artistkit',
            __( 'Upgrade to Pro', 'artistkit' ),
            '<span style="color:#fbbf24">' . esc_html__( 'Upgrade to Pro', 'artistkit' ) . '</span>',
            'manage_options',
            'artistkit-upgrade',
            [ __CLASS__, 'page_upgrade' ]
        );
    }

    // ── Assets ───────────────────────────────────────────────────────────────

    public static function enqueue_assets( $hook ) {
        $screen    = get_current_screen();
        $post_type = $screen ? $screen->post_type : '';
        $page      = $screen ? $screen->id : '';

        $is_artistkit_screen = ( $post_type === 'ak_artist_epk' )
            || in_array( $page, [
                'toplevel_page_artistkit',
                'artistkit_page_artistkit-settings',
                'artistkit_page_artistkit-upgrade',
            ], true );

        /**
         * Filter — Pro extends the list of screens that load ArtistKit assets.
         */
        $is_artistkit_screen = apply_filters( 'artistkit_is_admin_screen', $is_artistkit_screen, $hook, $screen );

        if ( ! $is_artistkit_screen ) return;

        wp_enqueue_media();
        wp_enqueue_style( 'ak-admin', ARTISTKIT_URL . 'assets/css/admin.css', [], ARTISTKIT_VERSION );
        wp_enqueue_script( 'ak-admin', ARTISTKIT_URL . 'assets/js/admin.js', [ 'jquery' ], ARTISTKIT_VERSION, true );
        wp_localize_script( 'ak-admin', 'AK', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'ak_admin' ),
            'siteUrl' => home_url( '/epk' ),
            'strings' => [
                'selectImage' => __( 'Select an image', 'artistkit' ),
                'useImage'    => __( 'Use this image', 'artistkit' ),
                'removeImage' => __( 'Remove', 'artistkit' ),
            ],
        ] );

        /**
         * Extension hook — Pro enqueues its own admin assets here.
         */
        do_action( 'artistkit_admin_enqueue', $hook );
    }

    // ── Pages ────────────────────────────────────────────────────────────────

    public static function page_dashboard() {
        $artist_epk = self::get_artist_epk();
        include ARTISTKIT_DIR . 'admin/views/dashboard.php';
    }

    public static function page_settings() {
        $settings = get_option( 'artistkit_settings', [] );
        include ARTISTKIT_DIR . 'admin/views/settings.php';
    }

    public static function page_upgrade() {
        include ARTISTKIT_DIR . 'admin/views/upgrade.php';
    }

    public static function save_settings() {
        // Nonce is verified here for the whole function.
        check_admin_referer( 'ak_save_settings' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Permission denied.', 'artistkit' ) );
        }

        $settings = get_option( 'artistkit_settings', [] );

        $settings['accent_color'] = isset( $_POST['accent_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['accent_color'] ) ) : '#8b5cf6';
        $settings['font_pair']    = isset( $_POST['font_pair'] ) ? sanitize_text_field( wp_unslash( $_POST['font_pair'] ) ) : 'inter';
        $settings['template']     = isset( $_POST['template'] ) ? sanitize_text_field( wp_unslash( $_POST['template'] ) ) : 'dark-minimal';
        $settings['logo_url']     = isset( $_POST['logo_url'] ) ? esc_url_raw( wp_unslash( $_POST['logo_url'] ) ) : '';

        /**
         * Filter — Pro saves its own settings fields (e.g. license_key) on top.
         *
         * $_POST is unslashed and shallow-sanitized before being exposed to
         * filter callbacks. Callbacks remain responsible for re-sanitizing each
         * field according to its own expected type.
         */
        $raw_post       = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce checked above
        $sanitized_post = is_array( $raw_post ) ? map_deep( $raw_post, 'sanitize_text_field' ) : [];
        $settings       = apply_filters( 'artistkit_save_settings', $settings, $sanitized_post );

        update_option( 'artistkit_settings', $settings );

        wp_safe_redirect( add_query_arg( [
            'page'    => 'artistkit-settings',
            'updated' => '1',
        ], admin_url( 'admin.php' ) ) );
        exit;
    }

    // ── Meta Boxes (Artist EPK only) ─────────────────────────────────────────

    public static function register_meta_boxes() {
        add_meta_box( 'ak_artist_main',     __( '🎤 Artist Identity', 'artistkit' ),       [ __CLASS__, 'mb_artist_identity' ],  'ak_artist_epk', 'normal', 'high' );
        add_meta_box( 'ak_artist_links',    __( '🎵 Streaming Links', 'artistkit' ),       [ __CLASS__, 'mb_artist_links' ],     'ak_artist_epk', 'normal', 'high' );
        add_meta_box( 'ak_artist_audio',    __( '🔊 Audio', 'artistkit' ),                  [ __CLASS__, 'mb_artist_audio' ],     'ak_artist_epk', 'normal', 'default' );
        add_meta_box( 'ak_artist_press',    __( '📰 Press & Quotes', 'artistkit' ),         [ __CLASS__, 'mb_artist_press' ],     'ak_artist_epk', 'normal', 'default' );
        add_meta_box( 'ak_artist_assets',   __( '📦 Assets & Contact', 'artistkit' ),       [ __CLASS__, 'mb_artist_assets' ],    'ak_artist_epk', 'side', 'high' );
        add_meta_box( 'ak_artist_epk_link', __( '🔗 EPK Link', 'artistkit' ),               [ __CLASS__, 'mb_epk_link_artist' ],  'ak_artist_epk', 'side', 'default' );

        /**
         * Extension hook — Pro registers its Release meta-boxes here.
         */
        do_action( 'artistkit_register_meta_boxes' );
    }

    public static function mb_artist_identity( $post ) {
        wp_nonce_field( 'ak_save_meta', 'ak_meta_nonce' );
        $d = self::get_post_meta_all( $post->ID, [
            'ak_genre', 'ak_bio_short', 'ak_bio_long',
            'ak_monthly_listeners', 'ak_total_streams',
            'ak_location', 'ak_founded_year',
        ] );
        include ARTISTKIT_DIR . 'admin/views/meta-artist-identity.php';
    }

    public static function mb_artist_links( $post ) {
        $d = self::get_post_meta_all( $post->ID, [
            'ak_spotify_url', 'ak_apple_music_url', 'ak_youtube_url',
            'ak_soundcloud_url', 'ak_bandcamp_url', 'ak_deezer_url',
            'ak_instagram_url', 'ak_tiktok_url', 'ak_facebook_url',
            'ak_website_url',
        ] );
        include ARTISTKIT_DIR . 'admin/views/meta-artist-links.php';
    }

    public static function mb_artist_audio( $post ) {
        $d = self::get_post_meta_all( $post->ID, [
            'ak_audio_mp3_url', 'ak_audio_mp3_label', 'ak_audio_downloadable',
            'ak_embed_type', 'ak_embed_url',
        ] );
        include ARTISTKIT_DIR . 'admin/views/meta-audio.php';
    }

    public static function mb_artist_press( $post ) {
        $quotes = get_post_meta( $post->ID, 'ak_press_quotes', true ) ?: [];
        include ARTISTKIT_DIR . 'admin/views/meta-artist-press.php';
    }

    public static function mb_artist_assets( $post ) {
        $d = self::get_post_meta_all( $post->ID, [
            'ak_contact_booking', 'ak_contact_management', 'ak_contact_press',
            'ak_rider_url', 'ak_photos_zip_url',
        ] );
        include ARTISTKIT_DIR . 'admin/views/meta-artist-assets.php';
    }

    public static function mb_epk_link_artist( $post ) {
        $url = home_url( '/epk' );
        include ARTISTKIT_DIR . 'admin/views/meta-epk-link.php';
    }

    // ── Save meta boxes ──────────────────────────────────────────────────────

    public static function save_meta_boxes( $post_id ) {
        // Nonce is verified at function entry — all $_POST accesses below are guarded.
        if ( ! isset( $_POST['ak_meta_nonce'] ) ) return;
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ak_meta_nonce'] ) ), 'ak_save_meta' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        $post_type = get_post_type( $post_id );

        if ( $post_type === 'ak_artist_epk' ) {
            self::save_text_fields( $post_id, [
                'ak_genre',
                'ak_monthly_listeners', 'ak_total_streams',
                'ak_location', 'ak_founded_year',
                'ak_spotify_url', 'ak_apple_music_url', 'ak_youtube_url',
                'ak_soundcloud_url', 'ak_bandcamp_url', 'ak_deezer_url',
                'ak_instagram_url', 'ak_tiktok_url', 'ak_facebook_url',
                'ak_website_url',
                'ak_contact_booking', 'ak_contact_management', 'ak_contact_press',
                'ak_rider_url', 'ak_photos_zip_url',
                'ak_audio_mp3_url', 'ak_audio_mp3_label',
                'ak_embed_type', 'ak_embed_url',
            ] );
            self::save_textarea_fields( $post_id, [
                'ak_bio_short', 'ak_bio_long',
            ] );
            update_post_meta( $post_id, 'ak_audio_downloadable', isset( $_POST['ak_audio_downloadable'] ) ? '1' : '0' );

            // Press quotes (array)
            if ( isset( $_POST['ak_press_quotes'] ) && is_array( $_POST['ak_press_quotes'] ) ) {
                $ak_quotes = [];
                // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce checked at function entry
                foreach ( wp_unslash( $_POST['ak_press_quotes'] ) as $ak_q ) {
                    $ak_quotes[] = [
                        'quote'  => sanitize_textarea_field( $ak_q['quote'] ?? '' ),
                        'source' => sanitize_text_field( $ak_q['source'] ?? '' ),
                        'url'    => esc_url_raw( $ak_q['url'] ?? '' ),
                    ];
                }
                update_post_meta( $post_id, 'ak_press_quotes', $ak_quotes );
            }
        }

        /**
         * Extension hook — Pro saves its Release fields here.
         */
        do_action( 'artistkit_save_post', $post_id, $post_type );
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * @phpcs:disable WordPress.Security.NonceVerification.Missing
     * Called only from save_meta_boxes() which verifies the nonce on entry.
     */
    private static function save_text_fields( $post_id, $fields ) {
        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }
    }

    private static function save_textarea_fields( $post_id, $fields ) {
        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }
    }
    // phpcs:enable WordPress.Security.NonceVerification.Missing

    private static function get_post_meta_all( $post_id, $keys ) {
        $data = [];
        foreach ( $keys as $key ) {
            $data[ $key ] = get_post_meta( $post_id, $key, true );
        }
        return $data;
    }

    public static function get_artist_epk() {
        $posts = get_posts( [ 'post_type' => 'ak_artist_epk', 'posts_per_page' => 1, 'post_status' => 'any' ] );
        return $posts ? $posts[0] : null;
    }

    // ── Admin notices ────────────────────────────────────────────────────────

    public static function admin_notices() {
        $screen = get_current_screen();
        if ( ! $screen || strpos( $screen->id, 'artistkit' ) === false ) return;

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- flash flag only, no destructive action
        if ( isset( $_GET['updated'] ) && '1' === sanitize_text_field( wp_unslash( $_GET['updated'] ) ) ) {
            echo '<div class="notice notice-success is-dismissible"><p>✅ ' . esc_html__( 'Settings saved.', 'artistkit' ) . '</p></div>';
        }

        /**
         * Extension hook — Pro renders its own admin notices (license activation, etc.).
         */
        do_action( 'artistkit_admin_notices', $screen );
    }
}
