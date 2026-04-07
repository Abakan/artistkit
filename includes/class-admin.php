<?php
defined( 'ABSPATH' ) || exit;

class AK_Admin {

    public static function init() {
        add_action( 'admin_menu',          [ __CLASS__, 'register_menus' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
        add_action( 'add_meta_boxes',      [ __CLASS__, 'register_meta_boxes' ] );
        add_action( 'save_post',           [ __CLASS__, 'save_meta_boxes' ] );
        add_action( 'admin_post_ak_save_settings', [ __CLASS__, 'save_settings' ] );
        add_action( 'admin_notices',       [ __CLASS__, 'admin_notices' ] );
        add_filter( 'post_row_actions',    [ __CLASS__, 'add_epk_row_action' ], 10, 2 );
    }

    public static function add_epk_row_action( $actions, $post ) {
        if ( $post->post_type === 'ak_artist_epk' && $post->post_status === 'publish' ) {
            $actions['view_epk'] = '<a href="' . esc_url( home_url( '/epk' ) ) . '" target="_blank">Voir l\'EPK</a>';
        }
        if ( $post->post_type === 'ak_release_epk' && $post->post_status === 'publish' ) {
            $actions['view_epk'] = '<a href="' . esc_url( home_url( '/epk/' . $post->post_name ) ) . '" target="_blank">Voir la sortie</a>';
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
            __( 'Mon EPK Artiste', 'artistkit' ),
            __( 'EPK Artiste', 'artistkit' ),
            'edit_posts',
            'edit.php?post_type=ak_artist_epk'
        );

        add_submenu_page(
            'artistkit',
            __( 'EPK Releases', 'artistkit' ),
            __( 'Releases', 'artistkit' ),
            'edit_posts',
            'edit.php?post_type=ak_release_epk'
        );

        $analytics_label = ak_is_pro()
            ? __( 'Analytics', 'artistkit' )
            : __( 'Analytics', 'artistkit' ) . ' <span style="font-size:9px;background:#7c3aed;color:#fff;padding:1px 6px;border-radius:3px;font-weight:700;vertical-align:middle;letter-spacing:0.03em">PRO</span>';

        add_submenu_page(
            'artistkit',
            __( 'Analytics', 'artistkit' ),
            $analytics_label,
            'edit_posts',
            'artistkit-analytics',
            [ __CLASS__, 'page_analytics' ]
        );

        add_submenu_page(
            'artistkit',
            __( 'Réglages & Licence', 'artistkit' ),
            __( 'Réglages', 'artistkit' ),
            'manage_options',
            'artistkit-settings',
            [ __CLASS__, 'page_settings' ]
        );
    }

    // ── Assets ───────────────────────────────────────────────────────────────

    public static function enqueue_assets( $hook ) {
        $screens = [
            'ak_artist_epk', 'ak_release_epk',
            'artistkit_page_artistkit-settings',
            'toplevel_page_artistkit',
        ];

        $post_type = get_current_screen() ? get_current_screen()->post_type : '';
        $page      = get_current_screen() ? get_current_screen()->id : '';

        if ( ! in_array( $post_type, [ 'ak_artist_epk', 'ak_release_epk' ] ) &&
             ! in_array( $page, [ 'artistkit_page_artistkit-settings', 'toplevel_page_artistkit', 'artistkit_page_artistkit-analytics' ] ) ) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style( 'ak-admin', AK_URL . 'assets/css/admin.css', [], AK_VERSION );
        wp_enqueue_script( 'ak-admin', AK_URL . 'assets/js/admin.js', [ 'jquery' ], AK_VERSION, true );
        wp_localize_script( 'ak-admin', 'AK', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'ak_admin' ),
            'isPro'   => ak_is_pro(),
            'siteUrl' => home_url( '/epk' ),
            'strings' => [
                'selectImage'  => __( 'Sélectionner une image', 'artistkit' ),
                'useImage'     => __( 'Utiliser cette image', 'artistkit' ),
                'removeImage'  => __( 'Supprimer', 'artistkit' ),
            ],
        ] );
    }

    // ── Dashboard page ───────────────────────────────────────────────────────

    public static function page_dashboard() {
        $artist_epk = self::get_artist_epk();
        $releases   = self::get_releases();
        include AK_DIR . 'admin/views/dashboard.php';
    }

    // ── Analytics page ───────────────────────────────────────────────────────

    public static function page_analytics() {
        include AK_DIR . 'admin/views/analytics.php';
    }

    // ── Settings page ────────────────────────────────────────────────────────

    public static function page_settings() {
        $settings = get_option( 'ak_settings', [] );
        include AK_DIR . 'admin/views/settings.php';
    }

    public static function save_settings() {
        check_admin_referer( 'ak_save_settings' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Permission refusée.' );

        $settings = get_option( 'ak_settings', [] );

        $settings['accent_color'] = isset( $_POST['accent_color'] ) ? sanitize_hex_color( $_POST['accent_color'] ) : '#8b5cf6';
        $settings['font_pair']    = isset( $_POST['font_pair'] ) ? sanitize_text_field( $_POST['font_pair'] ) : 'inter';
        $settings['template']     = isset( $_POST['template'] ) ? sanitize_text_field( $_POST['template'] ) : 'dark-minimal';
        $settings['logo_url']     = isset( $_POST['logo_url'] ) ? esc_url_raw( $_POST['logo_url'] ) : '';

        update_option( 'ak_settings', $settings );

        wp_redirect( add_query_arg( [
            'page'    => 'artistkit-settings',
            'updated' => '1',
        ], admin_url( 'admin.php' ) ) );
        exit;
    }

    // ── Meta Boxes ───────────────────────────────────────────────────────────

    public static function register_meta_boxes() {
        // Artist EPK
        add_meta_box( 'ak_artist_main',   __( '🎤 Identité Artiste', 'artistkit' ),       [ __CLASS__, 'mb_artist_identity' ],   'ak_artist_epk', 'normal', 'high' );
        add_meta_box( 'ak_artist_links',  __( '🎵 Liens Streaming', 'artistkit' ),         [ __CLASS__, 'mb_artist_links' ],      'ak_artist_epk', 'normal', 'high' );
        add_meta_box( 'ak_artist_audio',  __( '🔊 Audio', 'artistkit' ),                  [ __CLASS__, 'mb_artist_audio' ],      'ak_artist_epk', 'normal', 'default' );
        add_meta_box( 'ak_artist_press',  __( '📰 Presse & Citations', 'artistkit' ),      [ __CLASS__, 'mb_artist_press' ],      'ak_artist_epk', 'normal', 'default' );
        add_meta_box( 'ak_artist_assets', __( '📦 Assets & Contact', 'artistkit' ),        [ __CLASS__, 'mb_artist_assets' ],     'ak_artist_epk', 'side', 'high' );
        add_meta_box( 'ak_artist_epk_link', __( '🔗 Lien EPK', 'artistkit' ),             [ __CLASS__, 'mb_epk_link_artist' ],   'ak_artist_epk', 'side', 'default' );

        // Release EPK (Pro only)
        add_meta_box( 'ak_release_main',   __( '🎵 Infos Release', 'artistkit' ),          [ __CLASS__, 'mb_release_main' ],     'ak_release_epk', 'normal', 'high' );
        add_meta_box( 'ak_release_story',  __( '✍️ Angle Presse & Talking Points', 'artistkit' ), [ __CLASS__, 'mb_release_story' ], 'ak_release_epk', 'normal', 'high' );
        add_meta_box( 'ak_release_links',  __( '🔊 Audio & Embed', 'artistkit' ),          [ __CLASS__, 'mb_release_links' ],    'ak_release_epk', 'normal', 'default' );
        add_meta_box( 'ak_release_radio',  __( '📻 Infos Radio', 'artistkit' ),            [ __CLASS__, 'mb_release_radio' ],    'ak_release_epk', 'side', 'high' );
        add_meta_box( 'ak_release_assets', __( '📦 Assets & Sécurité', 'artistkit' ),      [ __CLASS__, 'mb_release_assets' ],   'ak_release_epk', 'side', 'default' );
        add_meta_box( 'ak_release_epk_link', __( '🔗 Lien EPK', 'artistkit' ),            [ __CLASS__, 'mb_epk_link_release' ], 'ak_release_epk', 'side', 'low' );
    }

    // ─── Meta Box: Artist Identity ────────────────────────────────────────────

    public static function mb_artist_identity( $post ) {
        wp_nonce_field( 'ak_save_meta', 'ak_meta_nonce' );
        $d = self::get_post_meta_all( $post->ID, [
            'ak_genre', 'ak_bio_short', 'ak_bio_long',
            'ak_monthly_listeners', 'ak_total_streams',
            'ak_location', 'ak_founded_year',
        ] );
        include AK_DIR . 'admin/views/meta-artist-identity.php';
    }

    // ─── Meta Box: Artist Links ───────────────────────────────────────────────

    public static function mb_artist_links( $post ) {
        $d = self::get_post_meta_all( $post->ID, [
            'ak_spotify_url', 'ak_apple_music_url', 'ak_youtube_url',
            'ak_soundcloud_url', 'ak_bandcamp_url', 'ak_deezer_url',
            'ak_instagram_url', 'ak_tiktok_url', 'ak_facebook_url',
            'ak_website_url',
        ] );
        include AK_DIR . 'admin/views/meta-artist-links.php';
    }

    // ─── Meta Box: Artist Audio ───────────────────────────────────────────────

    public static function mb_artist_audio( $post ) {
        $d = self::get_post_meta_all( $post->ID, [
            'ak_audio_mp3_url', 'ak_audio_mp3_label', 'ak_audio_downloadable',
            'ak_embed_type', 'ak_embed_url', 'ak_embed_height',
        ] );
        $featured_tracks = get_post_meta( $post->ID, 'ak_featured_tracks', true ) ?: [];
        include AK_DIR . 'admin/views/meta-audio.php';
    }

    // ─── Meta Box: Artist Press ───────────────────────────────────────────────

    public static function mb_artist_press( $post ) {
        $quotes = get_post_meta( $post->ID, 'ak_press_quotes', true ) ?: [];
        include AK_DIR . 'admin/views/meta-artist-press.php';
    }

    // ─── Meta Box: Artist Assets ──────────────────────────────────────────────

    public static function mb_artist_assets( $post ) {
        $d = self::get_post_meta_all( $post->ID, [
            'ak_contact_booking', 'ak_contact_management', 'ak_contact_press',
            'ak_rider_url', 'ak_photos_zip_url',
        ] );
        include AK_DIR . 'admin/views/meta-artist-assets.php';
    }

    // ─── Meta Box: EPK Link (artist) ──────────────────────────────────────────

    public static function mb_epk_link_artist( $post ) {
        $url = home_url( '/epk' );
        include AK_DIR . 'admin/views/meta-epk-link.php';
    }

    // ─── Meta Box: Release Main ───────────────────────────────────────────────

    public static function mb_release_main( $post ) {
        if ( ! ak_is_pro() ) {
            include AK_DIR . 'admin/views/pro-gate.php';
            return;
        }
        $d = self::get_post_meta_all( $post->ID, [
            'ak_release_type', 'ak_release_date', 'ak_release_label',
            'ak_release_genre', 'ak_tracklist',
            'ak_spotify_url', 'ak_apple_music_url', 'ak_youtube_url',
            'ak_soundcloud_url', 'ak_bandcamp_url', 'ak_deezer_url',
        ] );
        include AK_DIR . 'admin/views/meta-release-main.php';
    }

    // ─── Meta Box: Release Story ──────────────────────────────────────────────

    public static function mb_release_story( $post ) {
        if ( ! ak_is_pro() ) return;
        $d = self::get_post_meta_all( $post->ID, [
            'ak_release_story', 'ak_talking_points', 'ak_release_quotes',
        ] );
        include AK_DIR . 'admin/views/meta-release-story.php';
    }

    // ─── Meta Box: Release Links ──────────────────────────────────────────────

    public static function mb_release_links( $post ) {
        if ( ! ak_is_pro() ) return;
        $d = self::get_post_meta_all( $post->ID, [
            'ak_embed_type', 'ak_embed_url', 'ak_embed_height',
            'ak_audio_mp3_url', 'ak_audio_mp3_label', 'ak_audio_downloadable',
            'ak_clip_url',
        ] );
        include AK_DIR . 'admin/views/meta-release-links.php';
    }

    // ─── Meta Box: Release Radio ──────────────────────────────────────────────

    public static function mb_release_radio( $post ) {
        if ( ! ak_is_pro() ) return;
        $d = self::get_post_meta_all( $post->ID, [
            'ak_bpm', 'ak_key', 'ak_duration', 'ak_has_radio_edit', 'ak_isrc',
        ] );
        include AK_DIR . 'admin/views/meta-release-radio.php';
    }

    // ─── Meta Box: Release Assets ─────────────────────────────────────────────

    public static function mb_release_assets( $post ) {
        if ( ! ak_is_pro() ) return;
        $d = self::get_post_meta_all( $post->ID, [
            'ak_artwork_url', 'ak_promo_photos_zip', 'ak_password_protected', 'ak_password',
        ] );
        include AK_DIR . 'admin/views/meta-release-assets.php';
    }

    // ─── Meta Box: EPK Link (release) ─────────────────────────────────────────

    public static function mb_epk_link_release( $post ) {
        $slug = $post->post_name ?: 'votre-release';
        $url  = home_url( '/epk/' . $slug );
        include AK_DIR . 'admin/views/meta-epk-link.php';
    }

    // ── Save meta boxes ──────────────────────────────────────────────────────

    public static function save_meta_boxes( $post_id ) {
        if ( ! isset( $_POST['ak_meta_nonce'] ) ) return;
        if ( ! wp_verify_nonce( $_POST['ak_meta_nonce'], 'ak_save_meta' ) ) return;
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
                'ak_embed_type', 'ak_embed_url', 'ak_embed_height',
            ] );
            self::save_textarea_fields( $post_id, [
                'ak_bio_short', 'ak_bio_long',
            ] );
            update_post_meta( $post_id, 'ak_audio_downloadable', isset( $_POST['ak_audio_downloadable'] ) ? '1' : '0' );

            // Featured tracks patchwork (array)
            if ( isset( $_POST['ak_featured_tracks'] ) && is_array( $_POST['ak_featured_tracks'] ) ) {
                $tracks = [];
                foreach ( $_POST['ak_featured_tracks'] as $t ) {
                    $url = esc_url_raw( $t['url'] ?? '' );
                    if ( ! $url ) continue;
                    $tracks[] = [
                        'url'     => $url,
                        'artwork' => esc_url_raw( $t['artwork'] ?? '' ),
                        'title'   => sanitize_text_field( $t['title'] ?? '' ),
                    ];
                }
                update_post_meta( $post_id, 'ak_featured_tracks', $tracks );
            } else {
                update_post_meta( $post_id, 'ak_featured_tracks', [] );
            }

            // Press quotes (array)
            if ( isset( $_POST['ak_press_quotes'] ) && is_array( $_POST['ak_press_quotes'] ) ) {
                $quotes = [];
                foreach ( $_POST['ak_press_quotes'] as $q ) {
                    $quotes[] = [
                        'quote'  => sanitize_textarea_field( $q['quote'] ?? '' ),
                        'source' => sanitize_text_field( $q['source'] ?? '' ),
                        'url'    => esc_url_raw( $q['url'] ?? '' ),
                    ];
                }
                update_post_meta( $post_id, 'ak_press_quotes', $quotes );
            }
        }

        if ( $post_type === 'ak_release_epk' && ak_is_pro() ) {
            // Champs texte simples (une ligne)
            self::save_text_fields( $post_id, [
                'ak_release_type', 'ak_release_date', 'ak_release_label',
                'ak_release_genre',
                'ak_spotify_url', 'ak_apple_music_url', 'ak_youtube_url',
                'ak_soundcloud_url', 'ak_bandcamp_url', 'ak_deezer_url',
                'ak_embed_type', 'ak_embed_url', 'ak_embed_height',
                'ak_audio_mp3_url', 'ak_audio_mp3_label',
                'ak_clip_url',
                'ak_bpm', 'ak_key', 'ak_duration', 'ak_isrc',
                'ak_artwork_url', 'ak_promo_photos_zip', 'ak_password',
            ] );
            // Champs multilignes — sanitize_textarea_field préserve les \n
            self::save_textarea_fields( $post_id, [
                'ak_tracklist', 'ak_release_story',
                'ak_talking_points', 'ak_release_quotes',
            ] );

            update_post_meta( $post_id, 'ak_has_radio_edit', isset( $_POST['ak_has_radio_edit'] ) ? '1' : '0' );
            update_post_meta( $post_id, 'ak_password_protected', isset( $_POST['ak_password_protected'] ) ? '1' : '0' );
            update_post_meta( $post_id, 'ak_audio_downloadable', isset( $_POST['ak_audio_downloadable'] ) ? '1' : '0' );
        }
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private static function save_text_fields( $post_id, $fields ) {
        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
            }
        }
    }

    private static function save_textarea_fields( $post_id, $fields ) {
        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_textarea_field( $_POST[ $field ] ) );
            }
        }
    }

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

    public static function get_releases() {
        return get_posts( [ 'post_type' => 'ak_release_epk', 'posts_per_page' => -1, 'post_status' => 'any', 'orderby' => 'date', 'order' => 'DESC' ] );
    }

    // ── Admin notices ────────────────────────────────────────────────────────

    public static function admin_notices() {
        $screen = get_current_screen();
        if ( ! $screen || strpos( $screen->id, 'artistkit' ) === false ) return;

        if ( isset( $_GET['license_status'] ) ) {
            $status = isset( $_GET['license_status'] ) ? sanitize_key( $_GET['license_status'] ) : '';
            if ( $status === 'activated' ) {
                echo '<div class="notice notice-success is-dismissible"><p>🎉 ' . __( 'Licence Pro activée avec succès !', 'artistkit' ) . '</p></div>';
            } elseif ( $status === 'invalid' ) {
                echo '<div class="notice notice-error is-dismissible"><p>❌ ' . __( 'Clé de licence invalide ou expirée.', 'artistkit' ) . '</p></div>';
            } elseif ( $status === 'deactivated' ) {
                echo '<div class="notice notice-info is-dismissible"><p>' . __( 'Licence désactivée.', 'artistkit' ) . '</p></div>';
            }
        }

        if ( isset( $_GET['updated'] ) && $_GET['updated'] === '1' ) {
            echo '<div class="notice notice-success is-dismissible"><p>✅ ' . __( 'Réglages sauvegardés.', 'artistkit' ) . '</p></div>';
        }
    }
}
