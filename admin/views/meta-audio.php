<?php defined( 'ABSPATH' ) || exit;
$is_pro = ak_is_pro();
?>
<div class="ak-meta-grid">

  <!-- ── Embed Spotify / SoundCloud ── -->
  <p class="ak-section-subtitle">🎧 <?php _e( 'Embed streaming', 'artistkit' ); ?></p>

  <div class="ak-field-group">
    <label><?php _e( 'Plateforme', 'artistkit' ); ?></label>
    <select name="ak_embed_type" class="widefat">
      <option value=""><?php _e( '— Aucun embed —', 'artistkit' ); ?></option>
      <option value="spotify"    <?php selected( $d['ak_embed_type'] ?? '', 'spotify' ); ?>>Spotify (artiste / playlist / album)</option>
      <option value="soundcloud" <?php selected( $d['ak_embed_type'] ?? '', 'soundcloud' ); ?>>SoundCloud</option>
    </select>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'URL à embedder', 'artistkit' ); ?></label>
    <input type="url" name="ak_embed_url"
      value="<?php echo esc_attr( $d['ak_embed_url'] ?? '' ); ?>"
      placeholder="https://open.spotify.com/artist/… ou playlist/…"
      class="widefat" />
    <p class="description"><?php _e( 'ArtistKit génère l\'embed automatiquement depuis l\'URL.', 'artistkit' ); ?></p>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Vue', 'artistkit' ); ?></label>
    <select name="ak_embed_height" class="widefat" <?php echo ! $is_pro ? 'style="margin-bottom:8px"' : ''; ?>>
      <option value="152" <?php selected( $d['ak_embed_height'] ?? '152', '152' ); ?>><?php _e( 'Compact — 1 titre (gratuit)', 'artistkit' ); ?></option>
      <option value="380"
        <?php selected( $d['ak_embed_height'] ?? '152', '380' ); ?>
        <?php echo ! $is_pro ? 'disabled' : ''; ?>>
        <?php echo ! $is_pro ? '🔒 ' : ''; ?><?php _e( 'Album complet — tous les titres (Pro)', 'artistkit' ); ?>
      </option>
    </select>

    <?php if ( ! $is_pro ) : ?>
    <!-- Mockup visuel : vue album étendue -->
    <div style="position:relative;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;margin-top:2px">
      <div style="padding:10px 12px;filter:blur(1px);opacity:0.55;pointer-events:none;user-select:none;background:#fff">
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:10px">
          <div style="width:44px;height:44px;border-radius:4px;background:linear-gradient(135deg,#7c3aed,#4f46e5);flex-shrink:0"></div>
          <div>
            <div style="height:9px;background:#c4b5fd;border-radius:3px;width:110px;margin-bottom:5px"></div>
            <div style="height:7px;background:#e9d5ff;border-radius:3px;width:70px"></div>
          </div>
        </div>
        <?php for ( $t = 1; $t <= 5; $t++ ) : ?>
          <div style="display:flex;align-items:center;gap:8px;padding:5px 0;border-top:1px solid #f3f4f6">
            <span style="font-size:10px;color:#c4b5fd;width:12px;text-align:right"><?php echo $t; ?></span>
            <div style="height:7px;background:#ddd6fe;border-radius:3px;width:<?php echo rand(80,130); ?>px"></div>
            <div style="height:7px;background:#ede9fe;border-radius:3px;width:26px;margin-left:auto"></div>
          </div>
        <?php endfor; ?>
      </div>
      <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;background:rgba(255,255,255,0.82);text-align:center;padding:10px">
        <div style="font-size:20px;margin-bottom:4px">🔒</div>
        <strong style="font-size:12px;color:#4c1d95"><?php _e( 'Vue album complet', 'artistkit' ); ?></strong>
        <p style="font-size:11px;color:#6b21a8;margin:3px 0 10px;line-height:1.4"><?php _e( 'Affichez tous les titres de votre album<br>directement dans le player.', 'artistkit' ); ?></p>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=artistkit-settings' ) ); ?>" style="background:#7c3aed;color:#fff;font-size:11px;font-weight:600;padding:5px 14px;border-radius:4px;text-decoration:none">Passer en Pro →</a>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <hr style="border-color:#e5e5e5;margin:12px 0"/>

  <!-- ── Player MP3 ── -->
  <p class="ak-section-subtitle">🎵 <?php _e( 'Player MP3', 'artistkit' ); ?></p>

  <div class="ak-field-group">
    <label><?php _e( 'Fichier MP3 (URL)', 'artistkit' ); ?></label>
    <div class="ak-media-input">
      <input type="url" name="ak_audio_mp3_url" id="ak_audio_mp3_url"
        value="<?php echo esc_attr( $d['ak_audio_mp3_url'] ?? '' ); ?>"
        placeholder="https://monsite.com/audio/track.mp3" class="widefat" />
      <button type="button" class="button ak-upload-btn" data-target="ak_audio_mp3_url">
        <?php _e( 'Choisir', 'artistkit' ); ?>
      </button>
    </div>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Label du player', 'artistkit' ); ?> <span class="ak-hint"><?php _e( '(optionnel)', 'artistkit' ); ?></span></label>
    <input type="text" name="ak_audio_mp3_label"
      value="<?php echo esc_attr( $d['ak_audio_mp3_label'] ?? '' ); ?>"
      placeholder="Ex: Extrait · Demo · Maquette" class="widefat" />
  </div>

  <div class="ak-field-group">
    <label>
      <input type="checkbox" name="ak_audio_downloadable" value="1"
        <?php checked( $d['ak_audio_downloadable'] ?? '', '1' ); ?> />
      <?php _e( 'Autoriser le téléchargement du MP3', 'artistkit' ); ?>
    </label>
  </div>

  <hr style="border-color:#e5e5e5;margin:12px 0"/>

  <!-- ── Patchwork de titres ── -->
  <p class="ak-section-subtitle">🎨 <?php _e( 'Patchwork de titres', 'artistkit' ); ?></p>

  <?php if ( $is_pro ) : ?>

    <p class="description" style="margin-bottom:10px"><?php _e( 'Ajoutez des titres à mettre en avant sous forme de players carrés avec artwork. Ils s\'affichent en grille sur votre EPK.', 'artistkit' ); ?></p>

    <div id="ak-featured-tracks-list">
      <?php
      $featured_tracks = $featured_tracks ?? [];
      foreach ( $featured_tracks as $i => $track ) : ?>
        <div class="ak-featured-track-row" style="border:1px solid #e2e4e7;border-radius:6px;padding:12px;margin-bottom:10px;background:#fafafa;">
          <div style="display:flex;gap:8px;align-items:flex-start;">
            <div style="flex:1">
              <div class="ak-field-group" style="margin-bottom:8px">
                <label><?php _e( 'Titre du morceau', 'artistkit' ); ?></label>
                <input type="text" name="ak_featured_tracks[<?php echo $i; ?>][title]"
                  value="<?php echo esc_attr( $track['title'] ?? '' ); ?>"
                  placeholder="Ex: Mon Single" class="widefat" />
              </div>
              <div class="ak-field-group" style="margin-bottom:8px">
                <label><?php _e( 'Fichier MP3 (URL)', 'artistkit' ); ?></label>
                <div class="ak-media-input">
                  <input type="url" name="ak_featured_tracks[<?php echo $i; ?>][url]"
                    id="ak_featured_track_url_<?php echo $i; ?>"
                    value="<?php echo esc_attr( $track['url'] ?? '' ); ?>"
                    placeholder="https://…/track.mp3" class="widefat" />
                  <button type="button" class="button ak-upload-btn" data-target="ak_featured_track_url_<?php echo $i; ?>">
                    <?php _e( 'Choisir', 'artistkit' ); ?>
                  </button>
                </div>
              </div>
              <div class="ak-field-group">
                <label><?php _e( 'Artwork (URL)', 'artistkit' ); ?></label>
                <div class="ak-media-input">
                  <input type="url" name="ak_featured_tracks[<?php echo $i; ?>][artwork]"
                    id="ak_featured_track_art_<?php echo $i; ?>"
                    value="<?php echo esc_attr( $track['artwork'] ?? '' ); ?>"
                    placeholder="https://…/cover.jpg" class="widefat" />
                  <button type="button" class="button ak-upload-btn" data-target="ak_featured_track_art_<?php echo $i; ?>">
                    <?php _e( 'Choisir', 'artistkit' ); ?>
                  </button>
                </div>
              </div>
            </div>
            <button type="button" class="button ak-remove-row" style="margin-top:22px;color:#b32d2e;border-color:#b32d2e">✕</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <button type="button" class="button" id="ak-add-featured-track">
      + <?php _e( 'Ajouter un titre', 'artistkit' ); ?>
    </button>

    <script>
    (function() {
      var list  = document.getElementById('ak-featured-tracks-list');
      var btn   = document.getElementById('ak-add-featured-track');
      if ( ! btn ) return;
      var count = <?php echo count( $featured_tracks ); ?>;

      btn.addEventListener('click', function() {
        var i   = count++;
        var row = document.createElement('div');
        row.className = 'ak-featured-track-row';
        row.style.cssText = 'border:1px solid #e2e4e7;border-radius:6px;padding:12px;margin-bottom:10px;background:#fafafa;';
        row.innerHTML = '<div style="display:flex;gap:8px;align-items:flex-start;">'
          + '<div style="flex:1">'
          + '<div class="ak-field-group" style="margin-bottom:8px"><label><?php _e( "Titre du morceau", "artistkit" ); ?></label>'
          + '<input type="text" name="ak_featured_tracks[' + i + '][title]" placeholder="Ex: Mon Single" class="widefat" /></div>'
          + '<div class="ak-field-group" style="margin-bottom:8px"><label><?php _e( "Fichier MP3 (URL)", "artistkit" ); ?></label>'
          + '<div class="ak-media-input">'
          + '<input type="url" name="ak_featured_tracks[' + i + '][url]" id="ak_featured_track_url_' + i + '" placeholder="https://…/track.mp3" class="widefat" />'
          + '<button type="button" class="button ak-upload-btn" data-target="ak_featured_track_url_' + i + '"><?php _e( "Choisir", "artistkit" ); ?></button>'
          + '</div></div>'
          + '<div class="ak-field-group"><label><?php _e( "Artwork (URL)", "artistkit" ); ?></label>'
          + '<div class="ak-media-input">'
          + '<input type="url" name="ak_featured_tracks[' + i + '][artwork]" id="ak_featured_track_art_' + i + '" placeholder="https://…/cover.jpg" class="widefat" />'
          + '<button type="button" class="button ak-upload-btn" data-target="ak_featured_track_art_' + i + '"><?php _e( "Choisir", "artistkit" ); ?></button>'
          + '</div></div>'
          + '</div>'
          + '<button type="button" class="button ak-remove-row" style="margin-top:22px;color:#b32d2e;border-color:#b32d2e">✕</button>'
          + '</div>';
        list.appendChild(row);
      });

      list.addEventListener('click', function(e) {
        if ( e.target.classList.contains('ak-remove-row') ) {
          e.target.closest('.ak-featured-track-row').remove();
        }
      });
    })();
    </script>

  <?php else : ?>

    <!-- Mockup visuel : patchwork players -->
    <?php
    $mock_gradients = [
      'linear-gradient(135deg,#7c3aed,#4f46e5)',
      'linear-gradient(135deg,#db2777,#9333ea)',
      'linear-gradient(135deg,#0891b2,#6366f1)',
      'linear-gradient(135deg,#059669,#0ea5e9)',
      'linear-gradient(135deg,#d97706,#ea580c)',
    ];
    $mock_titles = [ 'Mon Single', 'Summer Vibes', 'Neon Nights', 'Horizon', 'Stay' ];
    ?>
    <div style="position:relative;border-radius:8px;overflow:hidden;margin-top:4px">
      <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:5px;filter:blur(1px);opacity:0.55;pointer-events:none;user-select:none">
        <?php foreach ( $mock_gradients as $j => $grad ) : ?>
          <div style="background:<?php echo $grad; ?>;border-radius:6px;aspect-ratio:1/1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;padding:6px">
            <div style="width:22px;height:22px;border:2px solid rgba(255,255,255,0.9);border-radius:50%;display:flex;align-items:center;justify-content:center">
              <svg width="9" height="9" viewBox="0 0 24 24" fill="white"><polygon points="6,3 20,12 6,21"/></svg>
            </div>
            <span style="font-size:7px;color:rgba(255,255,255,0.9);font-weight:600;text-align:center;line-height:1.2"><?php echo $mock_titles[ $j ]; ?></span>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;background:rgba(255,255,255,0.83);text-align:center;padding:14px;border-radius:8px">
        <div style="font-size:22px;margin-bottom:5px">🎨🔒</div>
        <strong style="font-size:12px;color:#4c1d95"><?php _e( 'Players carrés avec artwork', 'artistkit' ); ?></strong>
        <p style="font-size:11px;color:#6b21a8;margin:4px 0 10px;line-height:1.5"><?php _e( 'Mettez en avant vos titres en grille visuelle sur votre EPK.<br>Chaque card affiche l\'artwork et un player intégré.', 'artistkit' ); ?></p>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=artistkit-settings' ) ); ?>" style="background:#7c3aed;color:#fff;font-size:12px;font-weight:600;padding:6px 16px;border-radius:5px;text-decoration:none">Passer en Pro →</a>
      </div>
    </div>

  <?php endif; ?>

</div>
