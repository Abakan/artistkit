<?php
defined( 'ABSPATH' ) || exit;

class AK_Post_Types {

    public static function init() {
        add_action( 'init', [ __CLASS__, 'register' ] );
        add_action( 'init', [ __CLASS__, 'register_rewrite_rules' ] );
        add_filter( 'query_vars', [ __CLASS__, 'add_query_vars' ] );
    }

    public static function register() {
        register_post_type( 'ak_artist_epk', [
            'labels' => [
                'name'               => __( 'Artist EPK', 'artistkit' ),
                'singular_name'      => __( 'Artist EPK', 'artistkit' ),
                'add_new'            => __( 'Create my EPK', 'artistkit' ),
                'add_new_item'       => __( 'Create my Artist EPK', 'artistkit' ),
                'edit_item'          => __( 'Edit my Artist EPK', 'artistkit' ),
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

        /**
         * Extension hook — Pro registers ak_release_epk CPT here.
         */
        do_action( 'artistkit_register_post_types' );
    }

    public static function register_rewrite_rules() {
        // monsite.com/epk → Artist EPK
        add_rewrite_rule( '^epk/?$', 'index.php?ak_page=artist', 'top' );

        /**
         * Extension hook — Pro registers /epk/{slug} rewrite for Release EPKs.
         */
        do_action( 'artistkit_register_rewrite_rules' );
    }

    public static function add_query_vars( $vars ) {
        $vars[] = 'ak_page';
        $vars[] = 'ak_slug';
        return $vars;
    }
}
