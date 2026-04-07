<?php defined( 'ABSPATH' ) || exit;
$platforms = [
    'ak_spotify_url'     => [ 'label' => 'Spotify',      'icon' => '🎵', 'placeholder' => 'https://open.spotify.com/artist/...' ],
    'ak_apple_music_url' => [ 'label' => 'Apple Music',  'icon' => '🍎', 'placeholder' => 'https://music.apple.com/...' ],
    'ak_youtube_url'     => [ 'label' => 'YouTube',      'icon' => '▶️', 'placeholder' => 'https://youtube.com/...' ],
    'ak_deezer_url'      => [ 'label' => 'Deezer',       'icon' => '🎧', 'placeholder' => 'https://deezer.com/...' ],
    'ak_soundcloud_url'  => [ 'label' => 'SoundCloud',   'icon' => '☁️', 'placeholder' => 'https://soundcloud.com/...' ],
    'ak_bandcamp_url'    => [ 'label' => 'Bandcamp',     'icon' => '📻', 'placeholder' => 'https://yourname.bandcamp.com' ],
    'ak_instagram_url'   => [ 'label' => 'Instagram',    'icon' => '📸', 'placeholder' => 'https://instagram.com/...' ],
    'ak_tiktok_url'      => [ 'label' => 'TikTok',       'icon' => '🎬', 'placeholder' => 'https://tiktok.com/@...' ],
    'ak_facebook_url'    => [ 'label' => 'Facebook',     'icon' => '📘', 'placeholder' => 'https://facebook.com/...' ],
    'ak_website_url'     => [ 'label' => 'Site web',     'icon' => '🌐', 'placeholder' => 'https://monsite.com' ],
];
?>
<div class="ak-links-grid">
  <?php foreach ( $platforms as $key => $p ) : ?>
    <div class="ak-field-group ak-link-field">
      <label><?php echo $p['icon']; ?> <?php echo esc_html( $p['label'] ); ?></label>
      <input type="url" name="<?php echo esc_attr( $key ); ?>"
        value="<?php echo esc_attr( $d[ $key ] ?? '' ); ?>"
        placeholder="<?php echo esc_attr( $p['placeholder'] ); ?>"
        class="widefat" />
    </div>
  <?php endforeach; ?>
</div>
