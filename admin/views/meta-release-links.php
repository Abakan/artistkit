<?php defined( 'ABSPATH' ) || exit; ?>
<div class="ak-meta-grid">

  <!-- ── Section Écouter (audio) ── -->
  <p class="ak-section-subtitle">🔊 <?php _e( 'Écouter — Embed audio', 'artistkit' ); ?></p>

  <div class="ak-field-group">
    <label><?php _e( 'Plateforme audio', 'artistkit' ); ?></label>
    <select name="ak_embed_type" class="widefat" id="ak_embed_type">
      <option value=""><?php _e( '— Aucun embed audio —', 'artistkit' ); ?></option>
      <option value="spotify"    <?php selected( $d['ak_embed_type'] ?? '', 'spotify' ); ?>>Spotify (track / playlist)</option>
      <option value="soundcloud" <?php selected( $d['ak_embed_type'] ?? '', 'soundcloud' ); ?>>SoundCloud</option>
    </select>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'URL Spotify ou SoundCloud', 'artistkit' ); ?></label>
    <input type="url" name="ak_embed_url" value="<?php echo esc_attr( $d['ak_embed_url'] ?? '' ); ?>"
      placeholder="https://open.spotify.com/track/…"
      class="widefat" />
    <p class="description"><?php _e( 'ArtistKit génère l\'embed automatiquement depuis l\'URL.', 'artistkit' ); ?></p>
  </div>

  <div class="ak-field-group" id="ak-spotify-height-row">
    <label><?php _e( 'Vue Spotify', 'artistkit' ); ?> <span class="ak-hint"><?php _e( 'Pour les albums / playlists', 'artistkit' ); ?></span></label>
    <select name="ak_embed_height" class="widefat">
      <option value="152" <?php selected( $d['ak_embed_height'] ?? '152', '152' ); ?>><?php _e( 'Compact (1 titre)', 'artistkit' ); ?></option>
      <option value="380" <?php selected( $d['ak_embed_height'] ?? '152', '380' ); ?>><?php _e( 'Album complet (tous les titres)', 'artistkit' ); ?></option>
    </select>
  </div>

  <hr style="border-color:#e5e5e5;margin:12px 0"/>

  <p class="ak-section-subtitle">🎵 <?php _e( 'Écouter — Fichier MP3', 'artistkit' ); ?></p>

  <div class="ak-field-group">
    <label><?php _e( 'Fichier MP3 (URL)', 'artistkit' ); ?></label>
    <div class="ak-media-input">
      <input type="url" name="ak_audio_mp3_url" id="ak_audio_mp3_url"
        value="<?php echo esc_attr( $d['ak_audio_mp3_url'] ?? '' ); ?>"
        placeholder="https://monsite.com/audio/single.mp3" class="widefat" />
      <button type="button" class="button ak-upload-btn" data-target="ak_audio_mp3_url">
        <?php _e( 'Choisir', 'artistkit' ); ?>
      </button>
    </div>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Label du player', 'artistkit' ); ?> <span class="ak-hint"><?php _e( 'ex : Version Radio Edit', 'artistkit' ); ?></span></label>
    <input type="text" name="ak_audio_mp3_label"
      value="<?php echo esc_attr( $d['ak_audio_mp3_label'] ?? '' ); ?>"
      placeholder="<?php _e( 'Écouter le titre', 'artistkit' ); ?>" class="widefat" />
  </div>

  <div class="ak-field-group">
    <label>
      <input type="checkbox" name="ak_audio_downloadable" value="1"
        <?php checked( $d['ak_audio_downloadable'] ?? '', '1' ); ?> />
      <?php _e( 'Autoriser le téléchargement du MP3 (radios, journalistes)', 'artistkit' ); ?>
    </label>
  </div>

  <hr style="border-color:#e5e5e5;margin:12px 0"/>

  <!-- ── Section Clip vidéo ── -->
  <p class="ak-section-subtitle">🎬 <?php _e( 'Clip vidéo (YouTube)', 'artistkit' ); ?></p>

  <div class="ak-field-group">
    <label><?php _e( 'URL YouTube du clip', 'artistkit' ); ?></label>
    <input type="url" name="ak_clip_url"
      value="<?php echo esc_attr( $d['ak_clip_url'] ?? '' ); ?>"
      placeholder="https://www.youtube.com/watch?v=…"
      class="widefat" />
    <p class="description"><?php _e( 'Sera affiché dans une section dédiée "Clip vidéo" sur l\'EPK.', 'artistkit' ); ?></p>
  </div>

</div>
