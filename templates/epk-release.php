<?php defined( 'ABSPATH' ) || exit;
$s       = $settings;
$accent  = $s['accent_color'] ?? '#8b5cf6';
$theme   = $s['template']     ?? 'dark-minimal';
$font    = $s['font_pair']    ?? 'inter';

$font_urls = [
    'inter'    => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap',
    'poppins'  => 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap',
    'syne'     => 'https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&display=swap',
    'dmserif'  => 'https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap',
    'space'    => 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap',
    'playfair' => 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Source+Sans+3:wght@400;600&display=swap',
    'bebas'    => 'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600&display=swap',
    'outfit'   => 'https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap',
];
$font_family = [
    'inter'    => "'Inter', sans-serif",
    'poppins'  => "'Poppins', sans-serif",
    'syne'     => "'Syne', sans-serif",
    'dmserif'  => "'DM Serif Display', serif",
    'space'    => "'Space Grotesk', sans-serif",
    'playfair' => "'Playfair Display', serif",
    'bebas'    => "'Bebas Neue', sans-serif",
    'outfit'   => "'Outfit', sans-serif",
];
$ff = $font_family[ $font ] ?? $font_family['inter'];

// Build audio embed (Spotify / SoundCloud)
$embed_html = '';
if ( $embed_type && $embed_url ) {
    if ( $embed_type === 'spotify' ) {
        preg_match( '#open\.spotify\.com/(?:intl-[a-z]{2}/)?(track|album|playlist)/([a-zA-Z0-9]+)#', $embed_url, $sm );
        if ( ! empty( $sm[1] ) && ! empty( $sm[2] ) ) {
            $embed_url_clean = 'https://open.spotify.com/embed/' . $sm[1] . '/' . $sm[2];
        } else {
            $embed_url_clean = $embed_url;
        }
        $h = in_array( $embed_height, [ '152', '380' ] ) ? $embed_height : '152';
        $embed_html = '<iframe src="' . esc_url( $embed_url_clean ) . '" width="100%" height="' . $h . '" frameborder="0" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>';
    } elseif ( $embed_type === 'soundcloud' ) {
        $sc_url = 'https://w.soundcloud.com/player/?url=' . urlencode( $embed_url ) . '&color=%23' . ltrim( $accent, '#' ) . '&auto_play=false&show_artwork=true';
        $embed_html = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="' . esc_url( $sc_url ) . '"></iframe>';
    }
}

// Build clip embed (YouTube)
$clip_html = '';
if ( ! empty( $clip_url ) ) {
    preg_match( '#(?:v=|youtu\.be/)([a-zA-Z0-9_-]{11})#', $clip_url, $m );
    if ( ! empty( $m[1] ) ) {
        $clip_html = '<iframe src="https://www.youtube.com/embed/' . esc_attr( $m[1] ) . '" width="100%" height="420" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="border-radius:12px;display:block;"></iframe>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo esc_html( $release_title ); ?> — Press Kit</title>
  <meta name="description" content="<?php echo esc_attr( wp_trim_words( $release_story, 25 ) ); ?>" />
  <meta property="og:title" content="<?php echo esc_attr( $release_title ); ?> — Press Kit" />
  <?php if ( $artwork_url ) : ?>
    <meta property="og:image" content="<?php echo esc_attr( $artwork_url ); ?>" />
  <?php endif; ?>
  <meta name="robots" content="noindex" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="<?php echo esc_url( $font_urls[ $font ] ?? $font_urls['inter'] ); ?>" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo AK_URL; ?>assets/css/frontend.css?v=<?php echo AK_VERSION; ?>" />
  <link rel="stylesheet" href="<?php echo AK_URL; ?>assets/css/theme-<?php echo esc_attr( $theme ); ?>.css?v=<?php echo AK_VERSION; ?>" />
  <style>:root { --ak-accent: <?php echo esc_attr( $accent ); ?>; --ak-font: <?php echo $ff; ?>; }</style>
</head>
<body class="ak-epk ak-epk-release ak-theme-<?php echo esc_attr( $theme ); ?>">

<!-- NAV -->
<nav class="ak-nav">
  <div class="ak-nav-inner">
    <a href="<?php echo esc_url( $artist_epk_url ); ?>" class="ak-nav-back">← <?php _e( 'Retour au Press Kit artiste', 'artistkit' ); ?></a>
    <div class="ak-nav-right">
      <?php if ( ak_is_pro() ) : ?>
        <button class="ak-btn-pdf--nav" id="ak-pdf-btn" onclick="akPrintPDF(this)">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          <span class="ak-btn-pdf-label">Télécharger PDF</span>
        </button>
      <?php endif; ?>
      <span class="ak-nav-epk-badge">Release Press Kit</span>
    </div>
  </div>
</nav>

<!-- RELEASE HERO -->
<header class="ak-release-hero">
  <div class="ak-release-hero-inner">
    <?php if ( $artwork_url ) : ?>
      <div class="ak-release-artwork-wrap">
        <img src="<?php echo esc_url( $artwork_url ); ?>" alt="<?php echo esc_attr( $release_title ); ?>" class="ak-release-artwork-large" />
      </div>
    <?php endif; ?>

    <div class="ak-release-hero-content">

      <?php if ( $release_type ) : ?>
        <div class="ak-release-meta-top">
          <span class="ak-release-type-badge"><?php echo esc_html( $release_type ); ?></span>
        </div>
      <?php endif; ?>

      <h1 class="ak-release-title"><?php echo esc_html( $release_title ); ?></h1>

      <?php
      // Date en français
      $months_fr = [ 1=>'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre' ];
      $date_fr = '';
      if ( $release_date ) {
          $ts = strtotime( $release_date );
          $date_fr = date( 'j', $ts ) . ' ' . $months_fr[ (int) date( 'n', $ts ) ] . ' ' . date( 'Y', $ts );
      }
      $has_meta = $release_date || $release_label || $release_genre;
      ?>
      <?php if ( $has_meta ) : ?>
        <dl class="ak-release-info-grid">
          <?php if ( $release_date ) : ?>
            <div class="ak-info-item">
              <dt>Sortie</dt>
              <dd><?php echo esc_html( $date_fr ); ?></dd>
            </div>
          <?php endif; ?>
          <?php if ( $release_label ) : ?>
            <div class="ak-info-item">
              <dt>Label</dt>
              <dd><?php echo esc_html( $release_label ); ?></dd>
            </div>
          <?php endif; ?>
          <?php if ( $release_genre ) : ?>
            <div class="ak-info-item">
              <dt>Genre</dt>
              <dd><?php echo esc_html( $release_genre ); ?></dd>
            </div>
          <?php endif; ?>
        </dl>
      <?php endif; ?>

      <!-- Streaming links -->
      <div class="ak-streaming-links">
        <?php
        $links = [
          'spotify'    => [ 'url' => $spotify_url,     'label' => 'Spotify' ],
          'apple'      => [ 'url' => $apple_music_url, 'label' => 'Apple Music' ],
          'youtube'    => [ 'url' => $youtube_url,     'label' => 'YouTube' ],
          'deezer'     => [ 'url' => $deezer_url,      'label' => 'Deezer' ],
          'soundcloud' => [ 'url' => $soundcloud_url,  'label' => 'SoundCloud' ],
          'bandcamp'   => [ 'url' => $bandcamp_url,    'label' => 'Bandcamp' ],
        ];
        foreach ( $links as $key => $link ) :
          $url = esc_url( trim( $link['url'] ?? '' ) );
          if ( ! $url ) continue;
        ?>
          <a href="<?php echo $url; ?>" target="_blank" rel="noopener" class="ak-stream-btn ak-btn-<?php echo $key; ?>">
            <?php echo esc_html( $link['label'] ); ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Radio info chips -->
      <?php if ( $bpm || $key || $duration || $has_radio_edit === '1' ) : ?>
        <div class="ak-radio-chips">
          <?php if ( $bpm ) : ?><span class="ak-chip"><?php echo esc_html( $bpm ); ?> BPM</span><?php endif; ?>
          <?php if ( $key ) : ?><span class="ak-chip"><?php echo esc_html( $key ); ?></span><?php endif; ?>
          <?php if ( $duration ) : ?><span class="ak-chip"><?php echo esc_html( $duration ); ?></span><?php endif; ?>
          <?php if ( $has_radio_edit === '1' ) : ?><span class="ak-chip ak-chip-radio">Radio edit ✓</span><?php endif; ?>
          <?php if ( $isrc ) : ?><span class="ak-chip ak-chip-isrc">ISRC: <?php echo esc_html( $isrc ); ?></span><?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>

<main class="ak-main">

  <!-- ── 1. À propos de la release ── -->
  <?php if ( $release_story ) : ?>
    <section class="ak-section ak-section-story">
      <div class="ak-section-inner">
        <h2 class="ak-section-title">À propos de la release</h2>
        <div class="ak-release-story"><?php echo nl2br( esc_html( $release_story ) ); ?></div>
      </div>
    </section>
  <?php endif; ?>

  <!-- ── 2 & 3. Écouter + Clip (split ou solo) ── -->
  <?php $has_audio = $embed_html || $audio_mp3_url; ?>
  <?php if ( $has_audio || $clip_html ) : ?>
    <section class="ak-section ak-section-media<?php echo ( $has_audio && $clip_html ) ? ' ak-section-media--split' : ''; ?>">
      <div class="ak-section-inner">
        <div class="ak-media-split">

          <?php if ( $has_audio ) : ?>
          <!-- Colonne Écouter -->
          <div class="ak-media-col ak-media-col--listen">
            <h2 class="ak-section-title ak-col-title">Écouter</h2>

            <?php if ( $embed_html ) : ?>
              <div class="ak-embed-wrap"><?php echo $embed_html; ?></div>
            <?php endif; ?>

            <?php if ( $audio_mp3_url ) : ?>
              <div class="ak-audio-player<?php echo $embed_html ? ' ak-audio-player--below-embed' : ''; ?>">
                <?php if ( $audio_mp3_label ) : ?>
                  <p class="ak-audio-label"><?php echo esc_html( $audio_mp3_label ); ?></p>
                <?php endif; ?>
                <audio controls preload="metadata" class="ak-audio-native">
                  <source src="<?php echo esc_url( $audio_mp3_url ); ?>" type="audio/mpeg" />
                </audio>
                <?php if ( $audio_downloadable === '1' ) : ?>
                  <a href="<?php echo esc_url( $audio_mp3_url ); ?>" download class="ak-btn-download ak-btn-audio-dl">
                    ⬇ Télécharger le MP3
                  </a>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <?php if ( $clip_html ) : ?>
          <!-- Colonne Clip vidéo -->
          <div class="ak-media-col ak-media-col--clip">
            <h2 class="ak-section-title ak-col-title">Clip vidéo</h2>
            <div class="ak-clip-wrap"><?php echo $clip_html; ?></div>
          </div>
          <?php endif; ?>

        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- ── Angles d'interview + Tracklist (split 50/50) ── -->
  <?php $has_points = ! empty( $talking_points ); $has_tracklist = ! empty( $tracklist ); ?>
  <?php if ( $has_points || $has_tracklist ) : ?>
    <section class="ak-section ak-section-editorial<?php echo ( $has_points && $has_tracklist ) ? ' ak-section-editorial--split' : ''; ?>">
      <div class="ak-section-inner">
        <div class="ak-editorial-split">

          <?php if ( $has_tracklist ) : ?>
          <div class="ak-editorial-col">
            <h2 class="ak-section-title ak-col-title">Tracklist</h2>
            <div class="ak-tracklist">
              <?php
              $tracks = array_filter( array_map( 'trim', explode( "\n", $tracklist ) ) );
              foreach ( $tracks as $i => $track ) : ?>
                <div class="ak-track-row">
                  <span class="ak-track-num"><?php echo str_pad( $i + 1, 2, '0', STR_PAD_LEFT ); ?></span>
                  <span class="ak-track-title"><?php echo esc_html( preg_replace( '/^\d+[\.\)]\s*/', '', $track ) ); ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if ( $has_points ) : ?>
          <div class="ak-editorial-col">
            <h2 class="ak-section-title ak-col-title">Angles d'interview</h2>
            <div class="ak-talking-points">
              <?php
              $points = array_filter( array_map( 'trim', explode( "\n", $talking_points ) ) );
              foreach ( $points as $point ) : ?>
                <div class="ak-talking-point">
                  <span class="ak-tp-bullet">→</span>
                  <span><?php echo esc_html( ltrim( $point, '-•→ ' ) ); ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Presse quotes pour cette release -->
  <?php if ( $release_quotes ) : ?>
    <section class="ak-section ak-section-quotes">
      <div class="ak-section-inner">
        <h2 class="ak-section-title">Ils en parlent</h2>
        <div class="ak-quotes-grid">
          <?php
          $quote_lines = array_filter( array_map( 'trim', explode( "\n", $release_quotes ) ) );
          foreach ( $quote_lines as $q_line ) :
            // Format: "Citation" — Source
            preg_match( '/^["""](.*)["""]\s*[—–-]\s*(.+)$/', $q_line, $parts );
            if ( $parts ) : ?>
              <blockquote class="ak-quote">
                <p class="ak-quote-text">"<?php echo esc_html( $parts[1] ); ?>"</p>
                <footer class="ak-quote-source">— <?php echo esc_html( $parts[2] ); ?></footer>
              </blockquote>
            <?php else : ?>
              <blockquote class="ak-quote">
                <p class="ak-quote-text"><?php echo esc_html( $q_line ); ?></p>
              </blockquote>
            <?php endif;
          endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Assets téléchargeables -->
  <?php if ( $artwork_url || $promo_photos_zip ) : ?>
    <section class="ak-section ak-section-assets">
      <div class="ak-section-inner">
        <h2 class="ak-section-title">Assets presse</h2>
        <div class="ak-assets-row">
          <?php if ( $artwork_url ) : ?>
            <a href="<?php echo esc_url( $artwork_url ); ?>" class="ak-btn-download" download>
              ⬇ Artwork HD
            </a>
          <?php endif; ?>
          <?php if ( $promo_photos_zip ) : ?>
            <a href="<?php echo esc_url( $promo_photos_zip ); ?>" class="ak-btn-download" download>
              ⬇ Photos promo (.zip)
            </a>
          <?php endif; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

</main>

<footer class="ak-footer">
  <div class="ak-footer-inner">
    <span><?php echo esc_html( $release_title ); ?> · Press Kit</span>
    <a href="https://promotracker.fr/artistkit" target="_blank" class="ak-footer-brand">Powered by ArtistKit</a>
  </div>
</footer>

<script src="<?php echo AK_URL; ?>assets/js/frontend.js?v=<?php echo AK_VERSION; ?>"></script>
</body>
</html>
