<?php
defined( 'ABSPATH' ) || exit;

class AK_Post_Types {

    public static function init() {
        add_action( 'init', [ __CLASS__, 'register' ] );
        add_action( 'init', [ __CLASS__, 'register_rewrite_rules' ] );
        add_filter( 'query_vars', [ __CLASS__, 'add_query_vars' ] );
    }

    public static function register() {
        // ── Artist EPK ──────────────────────────────────────────────────────
        register_post_type( 'ak_artist_epk', [
            'labels' => [
                'name'               => __( 'EPK Artiste', 'artistkit' ),
                'singular_name'      => __( 'EPK Artiste', 'artistkit' ),
                'add_new'            => __( 'Créer mon EPK', 'artistkit' ),
                'add_new_item'       => __( 'Créer mon EPK Artiste', 'artistkit' ),
                'edit_item'          => __( 'Modifier mon EPK Artiste', 'artistkit' ),
                'menu_name'          => __( 'ArtistKit', 'artistkit' ),
            ],
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'supports'            => [ 'title', 'thumbnail' ],
            'has_archive'         => false,
            'rewrite'             => false,
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
        ] );

        // ── Release EPK ─────────────────────────────────────────────────────
        register_post_type( 'ak_release_epk', [
            'labels' => [
                'name'               => __( 'EPK Release', 'artistkit' ),
                'singular_name'      => __( 'EPK Release', 'artistkit' ),
                'add_new'            => __( 'Nouvelle Release', 'artistkit' ),
                'add_new_item'       => __( 'Nouvelle EPK Release', 'artistkit' ),
                'edit_item'          => __( 'Modifier EPK Release', 'artistkit' ),
                'menu_name'          => __( 'Releases', 'artistkit' ),
            ],
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'supports'            => [ 'title', 'thumbnail' ],
            'has_archive'         => false,
            'rewrite'             => false,
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
        ] );
    }

    public static function register_rewrite_rules() {
        // monsite.com/epk → Artist EPK
        add_rewrite_rule( '^epk/?$', 'index.php?ak_page=artist', 'top' );
        // monsite.com/epk/titre-du-single → Release EPK
        add_rewrite_rule( '^epk/([^/]+)/?$', 'index.php?ak_page=release&ak_slug=$matches[1]', 'top' );
    }

    public static function add_query_vars( $vars ) {
        $vars[] = 'ak_page';
        $vars[] = 'ak_slug';
        return $vars;
    }
}
