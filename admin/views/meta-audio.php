<?php defined( 'ABSPATH' ) || exit; ?>
<div class="ak-meta-grid">

  <!-- ── Embed Spotify / SoundCloud ── -->
  <p class="ak-section-subtitle">🎧 <?php esc_html_e( 'Streaming embed', 'artistkit' ); ?></p>

  <div class="ak-field-group">
    <label><?php esc_html_e( 'Platform', 'artistkit' ); ?></label>
    <select name="ak_embed_type" class="widefat">
      <option value=""><?php esc_html_e( '— No embed —', 'artistkit' ); ?></option>
      <option value="spotify"    <?php selected( $d['ak_embed_type'] ?? '', 'spotify' ); ?>>Spotify (artist / playlist / album)</option>
      <option value="soundcloud" <?php selected( $d['ak_embed_type'] ?? '', 'soundcloud' ); ?>>SoundCloud</option>
    </select>
  </div>

  <div class="ak-field-group">
    <label><?php esc_html_e( 'URL to embed', 'artistkit' ); ?></label>
    <input type="url" name="ak_embed_url"
      value="<?php echo esc_attr( $d['ak_embed_url'] ?? '' ); ?>"
      placeholder="https://open.spotify.com/artist/… or playlist/…"
      class="widefat" />
    <p class="description"><?php esc_html_e( 'ArtistKit generates the embed automatically from the URL.', 'artistkit' ); ?></p>
  </div>

  <hr style="border-color:#e5e5e5;margin:12px 0"/>

  <!-- ── MP3 Player ── -->
  <p class="ak-section-subtitle">🎵 <?php esc_html_e( 'MP3 Player', 'artistkit' ); ?></p>

  <div class="ak-field-group">
    <label><?php esc_html_e( 'MP3 file (URL)', 'artistkit' ); ?></label>
    <div class="ak-media-input">
      <input type="url" name="ak_audio_mp3_url" id="ak_audio_mp3_url"
        value="<?php echo esc_attr( $d['ak_audio_mp3_url'] ?? '' ); ?>"
        placeholder="https://yoursite.com/audio/track.mp3" class="widefat" />
      <button type="button" class="button ak-upload-btn" data-target="ak_audio_mp3_url">
        <?php esc_html_e( 'Choose', 'artistkit' ); ?>
      </button>
    </div>
  </div>

  <div class="ak-field-group">
    <label><?php esc_html_e( 'Player label', 'artistkit' ); ?> <span class="ak-hint"><?php esc_html_e( '(optional)', 'artistkit' ); ?></span></label>
    <input type="text" name="ak_audio_mp3_label"
      value="<?php echo esc_attr( $d['ak_audio_mp3_label'] ?? '' ); ?>"
      placeholder="<?php esc_attr_e( 'Ex: Excerpt · Demo · Sketch', 'artistkit' ); ?>" class="widefat" />
  </div>

  <div class="ak-field-group">
    <label>
      <input type="checkbox" name="ak_audio_downloadable" value="1"
        <?php checked( $d['ak_audio_downloadable'] ?? '', '1' ); ?> />
      <?php esc_html_e( 'Allow MP3 download', 'artistkit' ); ?>
    </label>
  </div>

  <?php
  /**
   * Extension hook — Pro renders its Track Patchwork section here.
   */
  do_action( 'artistkit_audio_meta_after', get_post() );
  ?>

</div>
