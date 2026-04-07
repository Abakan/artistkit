<?php defined( 'ABSPATH' ) || exit;
// Nonce ici car c'est la première meta box rendue pour les releases
wp_nonce_field( 'ak_save_meta', 'ak_meta_nonce' );
?>
<div class="ak-meta-grid">
  <div class="ak-field-group-row">
    <div class="ak-field-group">
      <label><?php _e( 'Type de release', 'artistkit' ); ?></label>
      <select name="ak_release_type" class="widefat">
        <?php foreach ( [ 'Single', 'EP', 'Album', 'Mixtape', 'Remix', 'Feat' ] as $type ) : ?>
          <option value="<?php echo $type; ?>" <?php selected( $d['ak_release_type'], $type ); ?>><?php echo $type; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="ak-field-group">
      <label><?php _e( 'Date de sortie', 'artistkit' ); ?></label>
      <input type="date" name="ak_release_date" value="<?php echo esc_attr( $d['ak_release_date'] ); ?>" class="widefat" />
    </div>
  </div>

  <div class="ak-field-group-row">
    <div class="ak-field-group">
      <label><?php _e( 'Label', 'artistkit' ); ?></label>
      <input type="text" name="ak_release_label" value="<?php echo esc_attr( $d['ak_release_label'] ); ?>"
        placeholder="Indépendant / Nom du label" class="widefat" />
    </div>
    <div class="ak-field-group">
      <label><?php _e( 'Genre', 'artistkit' ); ?></label>
      <input type="text" name="ak_release_genre" value="<?php echo esc_attr( $d['ak_release_genre'] ); ?>"
        placeholder="House · Electronic" class="widefat" />
    </div>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Tracklist', 'artistkit' ); ?> <span class="ak-hint"><?php _e( '(Pour EP/Album — une track par ligne)', 'artistkit' ); ?></span></label>
    <textarea name="ak_tracklist" rows="5" class="widefat" placeholder="01. Intro&#10;02. Titre A&#10;03. Titre B feat. Artiste C"><?php echo esc_textarea( $d['ak_tracklist'] ); ?></textarea>
  </div>

  <hr style="border-color:#3a3a4a;margin:8px 0"/>
  <p class="ak-section-subtitle">🔗 <?php _e( 'Liens streaming de cette release', 'artistkit' ); ?></p>

  <?php
  $streaming = [
    'ak_spotify_url'     => [ 'label' => 'Spotify',    'ph' => 'https://open.spotify.com/track/...' ],
    'ak_apple_music_url' => [ 'label' => 'Apple Music','ph' => 'https://music.apple.com/...' ],
    'ak_youtube_url'     => [ 'label' => 'YouTube',    'ph' => 'https://youtu.be/...' ],
    'ak_deezer_url'      => [ 'label' => 'Deezer',     'ph' => 'https://deezer.com/track/...' ],
    'ak_soundcloud_url'  => [ 'label' => 'SoundCloud', 'ph' => 'https://soundcloud.com/...' ],
    'ak_bandcamp_url'    => [ 'label' => 'Bandcamp',   'ph' => 'https://artist.bandcamp.com/track/...' ],
  ];
  foreach ( $streaming as $key => $p ) : ?>
    <div class="ak-field-group">
      <label><?php echo esc_html( $p['label'] ); ?></label>
      <input type="url" name="<?php echo esc_attr( $key ); ?>"
        value="<?php echo esc_attr( $d[ $key ] ?? '' ); ?>"
        placeholder="<?php echo esc_attr( $p['ph'] ); ?>" class="widefat" />
    </div>
  <?php endforeach; ?>
</div>
