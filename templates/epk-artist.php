<?php defined( 'ABSPATH' ) || exit;
$s       = $settings;
$accent  = $s['accent_color'] ?? '#8b5cf6';
$theme   = $s['template']     ?? 'dark-minimal';
$font    = $s['font_pair']    ?? 'inter';

/**
 * Filter — Pro extends the font list with its premium pairs.
 * Default Free list contains only 'inter'.
 */
$font_urls = apply_filters( 'artistkit_font_urls', [
    'inter' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap',
] );
$font_family = apply_filters( 'artistkit_font_families', [
    'inter' => "'Inter', sans-serif",
] );
$ff = $font_family[ $font ] ?? $font_family['inter'];
?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr( str_replace( '_', '-', get_locale() ) ); ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo esc_html( $artist_name ); ?> — <?php esc_html_e( 'Press Kit', 'artistkit' ); ?></title>
  <meta name="description" content="<?php echo esc_attr( wp_trim_words( $bio_short, 25 ) ); ?>" />
  <meta property="og:title" content="<?php echo esc_attr( $artist_name ); ?> — Press Kit" />
  <meta property="og:image" content="<?php echo esc_attr( $cover_image ); ?>" />
  <meta name="robots" content="noindex" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="<?php echo esc_url( $font_urls[ $font ] ?? $font_urls['inter'] ); ?>" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo esc_url( AK_URL ); ?>assets/css/frontend.css?v=<?php echo esc_attr( AK_VERSION ); ?>" />
  <link rel="stylesheet" href="<?php echo esc_url( AK_URL ); ?>assets/css/theme-<?php echo esc_attr( $theme ); ?>.css?v=<?php echo esc_attr( AK_VERSION ); ?>" />
  <style>:root { --ak-accent: <?php echo esc_attr( $accent ); ?>; --ak-font: <?php echo $ff; ?>; }</style>
  <?php do_action( 'artistkit_head', $post ); ?>
</head>
<body class="ak-epk ak-theme-<?php echo esc_attr( $theme ); ?>">

<!-- NAV -->
<nav class="ak-nav">
  <div class="ak-nav-inner">
    <?php if ( $site_logo ) : ?>
      <a href="<?php echo esc_url( home_url() ); ?>" class="ak-nav-logo">
        <img src="<?php echo esc_url( $site_logo ); ?>" alt="<?php echo esc_attr( $site_name ); ?>" />
      </a>
    <?php else : ?>
      <a href="<?php echo esc_url( home_url() ); ?>" class="ak-nav-site"><?php echo esc_html( $site_name ); ?></a>
    <?php endif; ?>
    <div class="ak-nav-right">
      <?php do_action( 'artistkit_nav_actions', $post ); ?>
      <span class="ak-nav-epk-badge"><?php esc_html_e( 'Press Kit', 'artistkit' ); ?></span>
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
      $has_stats = $monthly_listeners || $total_streams;
      ?>
      <?php if ( $has_stats ) : ?>
        <div class="ak-stats">
          <?php if ( $monthly_listeners ) : ?>
            <div class="ak-stat">
              <span class="ak-stat-val"><?php echo esc_html( $monthly_listeners ); ?></span>
              <span class="ak-stat-label"><?php esc_html_e( 'Monthly Spotify listeners', 'artistkit' ); ?></span>
            </div>
          <?php endif; ?>
          <?php if ( $total_streams ) : ?>
            <div class="ak-stat">
              <span class="ak-stat-val"><?php echo esc_html( $total_streams ); ?></span>
              <span class="ak-stat-label"><?php esc_html_e( 'Total streams', 'artistkit' ); ?></span>
            </div>
          <?php endif; ?>
          <?php do_action( 'artistkit_hero_stats', $post ); ?>
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
          <a href="<?php echo $url; ?>" target="_blank" rel="noopener" class="ak-stream-btn ak-btn-<?php echo esc_attr( $key ); ?>">
            <?php echo esc_html( $link['label'] ); ?>
          </a>
        <?php endforeach; ?>
      </div>

      <?php $has_socials = $instagram_url || $tiktok_url || $facebook_url || $website_url; ?>
      <?php if ( $has_socials ) : ?>
        <div class="ak-hero-socials">
          <?php if ( $instagram_url ) : ?><a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">Instagram</a><?php endif; ?>
          <?php if ( $tiktok_url ) : ?><a href="<?php echo esc_url( $tiktok_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">TikTok</a><?php endif; ?>
          <?php if ( $facebook_url ) : ?><a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">Facebook</a><?php endif; ?>
          <?php if ( $website_url ) : ?><a href="<?php echo esc_url( $website_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill"><?php esc_html_e( 'Website', 'artistkit' ); ?> ↗</a><?php endif; ?>
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

  <!-- Long bio -->
  <?php if ( $bio_long ) : ?>
    <section class="ak-section">
      <div class="ak-section-inner">
        <h2 class="ak-section-title"><?php esc_html_e( 'Biography', 'artistkit' ); ?></h2>
        <div class="ak-bio-long"><?php echo nl2br( esc_html( $bio_long ) ); ?></div>
        <?php if ( $bio_short ) : ?>
          <button class="ak-bio-toggle ak-btn-outline" id="ak-bio-toggle">
            <span class="ak-bio-toggle-more"><?php esc_html_e( 'Read full bio ↓', 'artistkit' ); ?></span>
            <span class="ak-bio-toggle-less" style="display:none"><?php esc_html_e( 'Collapse ↑', 'artistkit' ); ?></span>
          </button>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- Listen: embed + MP3 -->
  <?php
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
      $h = (string) apply_filters( 'artistkit_embed_height', $embed_height, $post );
      $artist_embed_html = '<iframe src="' . esc_url( $embed_url_clean ) . '" width="100%" height="' . esc_attr( $h ) . '" frameborder="0" allowtransparency="true" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy" style="border-radius:12px;display:block;"></iframe>';
  }

  $has_listen = $artist_embed_html || $audio_mp3_url;
  ?>
  <?php if ( $has_listen ) : ?>
    <section class="ak-section ak-section-audio">
      <div class="ak-section-inner">
        <h2 class="ak-section-title"><?php esc_html_e( 'Listen', 'artistkit' ); ?></h2>

        <?php if ( $artist_embed_html ) : ?>
          <div class="ak-artist-embed">
            <?php echo $artist_embed_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above with esc_url + esc_attr ?>
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
              <a href="<?php echo esc_url( $audio_mp3_url ); ?>" download class="ak-btn-download ak-btn-audio-dl">
                ⬇ <?php esc_html_e( 'Download MP3', 'artistkit' ); ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>

      </div>
    </section>
  <?php endif; ?>

  <?php
  /**
   * Extension hook — Pro renders its track patchwork section here.
   */
  do_action( 'artistkit_after_audio_section', $post );
  ?>

  <!-- Press assets -->
  <?php if ( $photos_zip_url || $rider_url ) : ?>
    <section class="ak-section ak-section-assets">
      <div class="ak-section-inner">
        <h2 class="ak-section-title"><?php esc_html_e( 'Press Assets', 'artistkit' ); ?></h2>
        <div class="ak-assets-row">
          <?php if ( $photos_zip_url ) : ?>
            <a href="<?php echo esc_url( $photos_zip_url ); ?>" class="ak-btn-download" download>
              ⬇ <?php esc_html_e( 'HD Photos (.zip)', 'artistkit' ); ?>
            </a>
          <?php endif; ?>
          <?php if ( $rider_url ) : ?>
            <a href="<?php echo esc_url( $rider_url ); ?>" class="ak-btn-download" target="_blank">
              ⬇ <?php esc_html_e( 'Technical rider', 'artistkit' ); ?>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Press quotes -->
  <?php if ( $press_quotes ) : ?>
    <section class="ak-section ak-section-quotes">
      <div class="ak-section-inner">
        <h2 class="ak-section-title"><?php esc_html_e( 'They talk about us', 'artistkit' ); ?></h2>
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

  <?php
  /**
   * Extension hook — Pro renders its Releases grid section here.
   */
  do_action( 'artistkit_after_press_quotes', $post );
  ?>

  <!-- Contact -->
  <section class="ak-section ak-section-contact">
    <div class="ak-section-inner">
      <h2 class="ak-section-title"><?php esc_html_e( 'Contact', 'artistkit' ); ?></h2>
      <div class="ak-contact-grid">
        <?php if ( $contact_booking ) : ?>
          <div class="ak-contact-card">
            <span class="ak-contact-role"><?php esc_html_e( 'Booking', 'artistkit' ); ?></span>
            <a href="mailto:<?php echo antispambot( $contact_booking ); ?>" class="ak-contact-email">
              <?php echo antispambot( $contact_booking ); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ( $contact_management ) : ?>
          <div class="ak-contact-card">
            <span class="ak-contact-role"><?php esc_html_e( 'Management', 'artistkit' ); ?></span>
            <a href="mailto:<?php echo antispambot( $contact_management ); ?>" class="ak-contact-email">
              <?php echo antispambot( $contact_management ); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ( $contact_press ) : ?>
          <div class="ak-contact-card">
            <span class="ak-contact-role"><?php esc_html_e( 'Press / PR', 'artistkit' ); ?></span>
            <a href="mailto:<?php echo antispambot( $contact_press ); ?>" class="ak-contact-email">
              <?php echo antispambot( $contact_press ); ?>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <?php do_action( 'artistkit_main_end', $post ); ?>
</main>

<!-- FOOTER -->
<footer class="ak-footer">
  <div class="ak-footer-inner">
    <span><?php echo esc_html( $artist_name ); ?> · <?php esc_html_e( 'Press Kit', 'artistkit' ); ?></span>
    <a href="https://promotracker.fr/artistkit" target="_blank" class="ak-footer-brand">
      Powered by ArtistKit
    </a>
  </div>
</footer>

<script src="<?php echo esc_url( AK_URL ); ?>assets/js/frontend.js?v=<?php echo esc_attr( AK_VERSION ); ?>"></script>
<?php do_action( 'artistkit_footer_scripts', $post ); ?>
</body>
</html>
