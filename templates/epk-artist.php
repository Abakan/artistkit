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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo esc_html( $artist_name ); ?> — Press Kit</title>
  <meta name="description" content="<?php echo esc_attr( wp_trim_words( $bio_short, 25 ) ); ?>" />
  <meta property="og:title" content="<?php echo esc_attr( $artist_name ); ?> — Press Kit" />
  <meta property="og:image" content="<?php echo esc_attr( $cover_image ); ?>" />
  <meta name="robots" content="noindex" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="<?php echo esc_url( $font_urls[ $font ] ?? $font_urls['inter'] ); ?>" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo AK_URL; ?>assets/css/frontend.css?v=<?php echo AK_VERSION; ?>" />
  <link rel="stylesheet" href="<?php echo AK_URL; ?>assets/css/theme-<?php echo esc_attr( $theme ); ?>.css?v=<?php echo AK_VERSION; ?>" />
  <style>:root { --ak-accent: <?php echo esc_attr( $accent ); ?>; --ak-font: <?php echo $ff; ?>; }</style>
</head>
<body class="ak-epk ak-theme-<?php echo esc_attr( $theme ); ?>">

<!-- NAV -->
<nav class="ak-nav">
  <div class="ak-nav-inner">
    <?php if ( $site_logo ) : ?>
      <a href="<?php echo home_url(); ?>" class="ak-nav-logo">
        <img src="<?php echo esc_url( $site_logo ); ?>" alt="<?php echo esc_attr( $site_name ); ?>" />
      </a>
    <?php else : ?>
      <a href="<?php echo home_url(); ?>" class="ak-nav-site"><?php echo esc_html( $site_name ); ?></a>
    <?php endif; ?>
    <div class="ak-nav-right">
      <?php if ( ak_is_pro() ) : ?>
        <button class="ak-btn-pdf--nav" id="ak-pdf-btn" onclick="akPrintPDF(this)">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          <span class="ak-btn-pdf-label">Télécharger PDF</span>
        </button>
      <?php elseif ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) : ?>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=artistkit-settings' ) ); ?>" class="ak-btn-pdf--nav ak-btn-pdf--nav-locked" title="Téléchargement PDF — ArtistKit Pro">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <span class="ak-btn-pdf-label">PDF — Pro</span>
        </a>
      <?php endif; ?>
      <span class="ak-nav-epk-badge">Press Kit</span>
    </div>
  </div>
</nav>

<!-- HERO -->
<header class="ak-hero">
  <div class="ak-hero-inner">
    <div class="ak-hero-content">
      <?php if ( $genre ) : ?>
        <div class="ak-genre"><?php echo esc_html( $genre ); ?><?php echo $location ? ' · ' . esc_html( $location ) : ''; ?></div>
      <?php endif; ?>
      <h1 class="ak-artist-name"><?php echo esc_html( $artist_name ); ?></h1>

      <?php if ( $bio_short ) : ?>
        <p class="ak-bio-short"><?php echo nl2br( esc_html( $bio_short ) ); ?></p>
      <?php endif; ?>

      <!-- Stats -->
      <?php
      $release_count = ! empty( $releases ) ? count( $releases ) : 0;
      $has_stats = $monthly_listeners || $total_streams || $release_count;
      ?>
      <?php if ( $has_stats ) : ?>
        <div class="ak-stats">
          <?php if ( $monthly_listeners ) : ?>
            <div class="ak-stat">
              <span class="ak-stat-val"><?php echo esc_html( $monthly_listeners ); ?></span>
              <span class="ak-stat-label">Monthly listeners Spotify</span>
            </div>
          <?php endif; ?>
          <?php if ( $total_streams ) : ?>
            <div class="ak-stat">
              <span class="ak-stat-val"><?php echo esc_html( $total_streams ); ?></span>
              <span class="ak-stat-label">Streams totaux</span>
            </div>
          <?php endif; ?>
          <?php if ( $release_count ) : ?>
            <div class="ak-stat">
              <span class="ak-stat-val"><?php echo esc_html( $release_count ); ?></span>
              <span class="ak-stat-label"><?php echo $release_count > 1 ? 'Sorties' : 'Sortie'; ?></span>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Streaming links -->
      <div class="ak-streaming-links">
        <?php
        $links = [
          'spotify'     => [ 'url' => $spotify_url,     'label' => 'Spotify' ],
          'apple'       => [ 'url' => $apple_music_url, 'label' => 'Apple Music' ],
          'youtube'     => [ 'url' => $youtube_url,     'label' => 'YouTube' ],
          'deezer'      => [ 'url' => $deezer_url,      'label' => 'Deezer' ],
          'soundcloud'  => [ 'url' => $soundcloud_url,  'label' => 'SoundCloud' ],
          'bandcamp'    => [ 'url' => $bandcamp_url,    'label' => 'Bandcamp' ],
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

      <!-- Réseaux sociaux & site web -->
      <?php $has_socials = $instagram_url || $tiktok_url || $facebook_url || $website_url; ?>
      <?php if ( $has_socials ) : ?>
        <div class="ak-hero-socials">
          <?php if ( $instagram_url ) : ?><a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">Instagram</a><?php endif; ?>
          <?php if ( $tiktok_url ) : ?><a href="<?php echo esc_url( $tiktok_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">TikTok</a><?php endif; ?>
          <?php if ( $facebook_url ) : ?><a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">Facebook</a><?php endif; ?>
          <?php if ( $website_url ) : ?><a href="<?php echo esc_url( $website_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">Site web ↗</a><?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php if ( $cover_image ) : ?>
      <div class="ak-hero-image">
        <img src="<?php echo esc_url( $cover_image ); ?>" alt="<?php echo esc_attr( $artist_name ); ?>" />
      </div>
    <?php endif; ?>
  </div>
</header>

<main class="ak-main">

  <!-- Bio longue -->
  <?php if ( $bio_long ) : ?>
    <section class="ak-section">
      <div class="ak-section-inner">
        <h2 class="ak-section-title">Biographie</h2>
        <div class="ak-bio-long"><?php echo nl2br( esc_html( $bio_long ) ); ?></div>
        <?php if ( $bio_short ) : ?>
          <button class="ak-bio-toggle ak-btn-outline" id="ak-bio-toggle">
            <span class="ak-bio-toggle-more">Lire la bio complète ↓</span>
            <span class="ak-bio-toggle-less" style="display:none">Réduire ↑</span>
          </button>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- ── Écouter : embed + MP3 + patchwork ── -->
  <?php
  // Build embed HTML (Spotify / SoundCloud)
  $artist_embed_html = '';
  if ( $embed_type && $embed_url ) {
      $embed_url_clean = $embed_url;
      if ( $embed_type === 'spotify' ) {
          preg_match( '#open\.spotify\.com/(?:intl-[a-z]{2}/)?(track|album|playlist)/([a-zA-Z0-9]+)#', $embed_url, $sm );
          if ( ! empty( $sm[1] ) && ! empty( $sm[2] ) ) {
              $embed_url_clean = 'https://open.spotify.com/embed/' . $sm[1] . '/' . $sm[2];
          }
      } elseif ( $embed_type === 'soundcloud' ) {
          $embed_url_clean = 'https://w.soundcloud.com/player/?url=' . rawurlencode( $embed_url ) . '&color=%238b5cf6&auto_play=false&show_artwork=true';
      }
      $h = ( $embed_height === '380' && ak_is_pro() ) ? '380' : '152';
      $artist_embed_html = '<iframe src="' . esc_url( $embed_url_clean ) . '" width="100%" height="' . $h . '" frameborder="0" allowtransparency="true" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy" style="border-radius:12px;display:block;"></iframe>';
  }

  $has_listen = $artist_embed_html || $audio_mp3_url || ! empty( $featured_tracks );
  ?>
  <?php if ( $has_listen ) : ?>
    <section class="ak-section ak-section-audio">
      <div class="ak-section-inner">
        <h2 class="ak-section-title">Écouter</h2>

        <?php if ( $artist_embed_html ) : ?>
          <div class="ak-artist-embed">
            <?php echo $artist_embed_html; ?>
          </div>
        <?php endif; ?>

        <?php if ( $audio_mp3_url ) : ?>
          <div class="ak-audio-player<?php echo $artist_embed_html ? ' ak-audio-player--spaced' : ''; ?>">
            <?php if ( $audio_mp3_label ) : ?>
              <p class="ak-audio-label"><?php echo esc_html( $audio_mp3_label ); ?></p>
            <?php endif; ?>
            <audio controls preload="metadata" class="ak-audio-native">
              <source src="<?php echo esc_url( $audio_mp3_url ); ?>" type="audio/mpeg" />
            </audio>
            <?php if ( $audio_downloadable === '1' ) : ?>
              <a href="<?php echo esc_url( $audio_mp3_url ); ?>"
                 download
                 class="ak-btn-download ak-btn-audio-dl ak-track-download"
                 data-epk-id="<?php echo esc_attr( $post->ID ); ?>"
                 data-epk-type="artist"
                 data-event="download_mp3"
                 data-nonce="<?php echo esc_attr( wp_create_nonce( 'ak_log_event' ) ); ?>">
                ⬇ Télécharger le MP3
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if ( ! empty( $featured_tracks ) && ak_is_pro() ) : ?>
          <div class="ak-patchwork-grid">
            <?php foreach ( $featured_tracks as $i => $track ) :
              $track_id = 'ak-patch-' . $i;
            ?>
              <div class="ak-patch-card" data-src="<?php echo esc_attr( $track['url'] ); ?>">
                <?php if ( $track['artwork'] ) : ?>
                  <div class="ak-patch-bg" style="background-image:url('<?php echo esc_url( $track['artwork'] ); ?>')"></div>
                <?php else : ?>
                  <div class="ak-patch-bg ak-patch-bg--placeholder"></div>
                <?php endif; ?>
                <div class="ak-patch-overlay"></div>
                <button class="ak-patch-play" aria-label="Lire <?php echo esc_attr( $track['title'] ); ?>">
                  <svg class="ak-patch-play-icon" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,3 19,12 5,21"/></svg>
                  <svg class="ak-patch-pause-icon" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                </button>
                <?php if ( $track['title'] ) : ?>
                  <div class="ak-patch-title"><?php echo esc_html( $track['title'] ); ?></div>
                <?php endif; ?>
                <audio class="ak-patch-audio" preload="none">
                  <source src="<?php echo esc_url( $track['url'] ); ?>" type="audio/mpeg" />
                </audio>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </section>
  <?php endif; ?>

  <!-- Assets presse -->
  <?php if ( $photos_zip_url || $rider_url ) : ?>
    <section class="ak-section ak-section-assets">
      <div class="ak-section-inner">
        <h2 class="ak-section-title">Assets presse</h2>
        <div class="ak-assets-row">
          <?php if ( $photos_zip_url ) : ?>
            <a href="<?php echo esc_url( $photos_zip_url ); ?>" class="ak-btn-download" download>
              ⬇ Photos HD (.zip)
            </a>
          <?php endif; ?>
          <?php if ( $rider_url ) : ?>
            <a href="<?php echo esc_url( $rider_url ); ?>" class="ak-btn-download" target="_blank">
              ⬇ Rider technique
            </a>
          <?php endif; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Citations presse -->
  <?php if ( $press_quotes ) : ?>
    <section class="ak-section ak-section-quotes">
      <div class="ak-section-inner">
        <h2 class="ak-section-title">Ils en parlent</h2>
        <div class="ak-quotes-grid">
          <?php foreach ( $press_quotes as $q ) :
            if ( empty( $q['quote'] ) ) continue;
          ?>
            <blockquote class="ak-quote">
              <p class="ak-quote-text">"<?php echo esc_html( $q['quote'] ); ?>"</p>
              <?php if ( ! empty( $q['source'] ) ) : ?>
                <footer class="ak-quote-source">
                  <?php if ( ! empty( $q['url'] ) ) : ?>
                    <a href="<?php echo esc_url( $q['url'] ); ?>" target="_blank" rel="noopener">— <?php echo esc_html( $q['source'] ); ?></a>
                  <?php else : ?>
                    — <?php echo esc_html( $q['source'] ); ?>
                  <?php endif; ?>
                </footer>
              <?php endif; ?>
            </blockquote>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Releases (Pro) -->
  <?php if ( ak_is_pro() && $releases ) : ?>
    <section class="ak-section">
      <div class="ak-section-inner">
        <h2 class="ak-section-title">Releases</h2>
        <div class="ak-releases-grid">
          <?php foreach ( $releases as $release ) :
            $slug        = $release->post_name;
            $type        = get_post_meta( $release->ID, 'ak_release_type', true ) ?: 'Single';
            $date        = get_post_meta( $release->ID, 'ak_release_date', true );
            $artwork     = get_post_meta( $release->ID, 'ak_artwork_url', true ) ?: get_the_post_thumbnail_url( $release->ID, 'medium' );
            $spotify     = get_post_meta( $release->ID, 'ak_spotify_url', true );
          ?>
            <div class="ak-release-card">
              <a href="<?php echo esc_url( home_url( '/epk/' . $slug ) ); ?>" class="ak-release-card-overlay" aria-label="<?php echo esc_attr( $release->post_title ); ?> — Press Kit"></a>
              <?php if ( $artwork ) : ?>
                <img src="<?php echo esc_url( $artwork ); ?>" alt="<?php echo esc_attr( $release->post_title ); ?>" class="ak-release-artwork" />
              <?php endif; ?>
              <div class="ak-release-info">
                <span class="ak-release-type"><?php echo esc_html( $type ); ?><?php echo $date ? ' · ' . esc_html( date( 'Y', strtotime( $date ) ) ) : ''; ?></span>
                <h3 class="ak-release-title"><?php echo esc_html( $release->post_title ); ?></h3>
                <div class="ak-release-actions">
                  <span class="ak-btn-outline ak-btn-sm">Press Kit →</span>
                  <?php if ( $spotify ) : ?>
                    <a href="<?php echo esc_url( $spotify ); ?>" target="_blank" rel="noopener" class="ak-btn-outline ak-btn-sm ak-release-spotify-btn">Spotify ↗</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Contact & Assets -->
  <section class="ak-section ak-section-contact">
    <div class="ak-section-inner">
      <h2 class="ak-section-title">Contact</h2>
      <div class="ak-contact-grid">
        <?php if ( $contact_booking ) : ?>
          <div class="ak-contact-card">
            <span class="ak-contact-role">Booking</span>
            <a href="mailto:<?php echo antispambot( $contact_booking ); ?>" class="ak-contact-email">
              <?php echo antispambot( $contact_booking ); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ( $contact_management ) : ?>
          <div class="ak-contact-card">
            <span class="ak-contact-role">Management</span>
            <a href="mailto:<?php echo antispambot( $contact_management ); ?>" class="ak-contact-email">
              <?php echo antispambot( $contact_management ); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ( $contact_press ) : ?>
          <div class="ak-contact-card">
            <span class="ak-contact-role">Presse / RP</span>
            <a href="mailto:<?php echo antispambot( $contact_press ); ?>" class="ak-contact-email">
              <?php echo antispambot( $contact_press ); ?>
            </a>
          </div>
        <?php endif; ?>
      </div>

      <!-- (assets et réseaux sociaux déplacés) -->
    </div>
  </section>
</main>

<!-- FOOTER -->
<footer class="ak-footer">
  <div class="ak-footer-inner">
    <span><?php echo esc_html( $artist_name ); ?> · Press Kit</span>
    <a href="https://promotracker.fr/artistkit" target="_blank" class="ak-footer-brand">
      Powered by ArtistKit
    </a>
  </div>
</footer>

<script>window.ajaxurl = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;</script>
<script src="<?php echo esc_url( AK_URL ); ?>assets/js/frontend.js?v=<?php echo esc_attr( AK_VERSION ); ?>"></script>
</body>
</html>
