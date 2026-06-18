<?php defined( 'ABSPATH' ) || exit; ?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr( str_replace( '_', '-', get_locale() ) ); ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo esc_html( $ak_artist_name ); ?> — <?php esc_html_e( 'Press Kit', 'artistkit' ); ?></title>
  <meta name="description" content="<?php echo esc_attr( wp_trim_words( $ak_bio_short, 25 ) ); ?>" />
  <meta property="og:title" content="<?php echo esc_attr( $ak_artist_name ); ?> — Press Kit" />
  <meta property="og:image" content="<?php echo esc_attr( $ak_cover_image ); ?>" />
  <meta name="robots" content="noindex" />
  <?php
  // wp_head() prints the assets enqueued in AK_Frontend::enqueue_frontend_assets().
  // It also fires the standard wp_head action — keep ahead of plugin head hooks.
  wp_head();
  do_action( 'artistkit_head', $ak_post );
  ?>
</head>
<body class="ak-epk ak-theme-<?php echo esc_attr( $ak_theme ); ?>">

<!-- NAV -->
<nav class="ak-nav">
  <div class="ak-nav-inner">
    <?php if ( $ak_site_logo ) : ?>
      <a href="<?php echo esc_url( home_url() ); ?>" class="ak-nav-logo">
        <img src="<?php echo esc_url( $ak_site_logo ); ?>" alt="<?php echo esc_attr( $ak_site_name ); ?>" />
      </a>
    <?php else : ?>
      <a href="<?php echo esc_url( home_url() ); ?>" class="ak-nav-site"><?php echo esc_html( $ak_site_name ); ?></a>
    <?php endif; ?>
    <div class="ak-nav-right">
      <?php do_action( 'artistkit_nav_actions', $ak_post ); ?>
      <span class="ak-nav-epk-badge"><?php esc_html_e( 'Press Kit', 'artistkit' ); ?></span>
    </div>
  </div>
</nav>

<!-- HERO -->
<header class="ak-hero">
  <div class="ak-hero-inner">
    <div class="ak-hero-content">
      <?php if ( $ak_genre ) : ?>
        <div class="ak-genre"><?php echo esc_html( $ak_genre ); ?><?php echo $ak_location ? ' · ' . esc_html( $ak_location ) : ''; ?></div>
      <?php endif; ?>
      <h1 class="ak-artist-name"><?php echo esc_html( $ak_artist_name ); ?></h1>

      <?php if ( $ak_bio_short ) : ?>
        <p class="ak-bio-short"><?php echo nl2br( esc_html( $ak_bio_short ) ); ?></p>
      <?php endif; ?>

      <!-- Stats -->
      <?php $ak_has_stats = $ak_monthly_listeners || $ak_total_streams; ?>
      <?php if ( $ak_has_stats ) : ?>
        <div class="ak-stats">
          <?php if ( $ak_monthly_listeners ) : ?>
            <div class="ak-stat">
              <span class="ak-stat-val"><?php echo esc_html( $ak_monthly_listeners ); ?></span>
              <span class="ak-stat-label"><?php esc_html_e( 'Monthly Spotify listeners', 'artistkit' ); ?></span>
            </div>
          <?php endif; ?>
          <?php if ( $ak_total_streams ) : ?>
            <div class="ak-stat">
              <span class="ak-stat-val"><?php echo esc_html( $ak_total_streams ); ?></span>
              <span class="ak-stat-label"><?php esc_html_e( 'Total streams', 'artistkit' ); ?></span>
            </div>
          <?php endif; ?>
          <?php do_action( 'artistkit_hero_stats', $ak_post ); ?>
        </div>
      <?php endif; ?>

      <!-- Streaming links -->
      <div class="ak-streaming-links">
        <?php
        $ak_links = [
          'spotify'    => [ 'url' => $ak_spotify_url,     'label' => 'Spotify' ],
          'apple'      => [ 'url' => $ak_apple_music_url, 'label' => 'Apple Music' ],
          'youtube'    => [ 'url' => $ak_youtube_url,     'label' => 'YouTube' ],
          'deezer'     => [ 'url' => $ak_deezer_url,      'label' => 'Deezer' ],
          'soundcloud' => [ 'url' => $ak_soundcloud_url,  'label' => 'SoundCloud' ],
          'bandcamp'   => [ 'url' => $ak_bandcamp_url,    'label' => 'Bandcamp' ],
        ];
        foreach ( $ak_links as $ak_key => $ak_link ) :
          $ak_url = esc_url( trim( $ak_link['url'] ?? '' ) );
          if ( ! $ak_url ) continue;
        ?>
          <a href="<?php echo esc_url( $ak_url ); ?>" target="_blank" rel="noopener" class="ak-stream-btn ak-btn-<?php echo esc_attr( $ak_key ); ?>">
            <?php echo esc_html( $ak_link['label'] ); ?>
          </a>
        <?php endforeach; ?>
      </div>

      <?php $ak_has_socials = $ak_instagram_url || $ak_tiktok_url || $ak_facebook_url || $ak_website_url; ?>
      <?php if ( $ak_has_socials ) : ?>
        <div class="ak-hero-socials">
          <?php if ( $ak_instagram_url ) : ?><a href="<?php echo esc_url( $ak_instagram_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">Instagram</a><?php endif; ?>
          <?php if ( $ak_tiktok_url ) : ?><a href="<?php echo esc_url( $ak_tiktok_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">TikTok</a><?php endif; ?>
          <?php if ( $ak_facebook_url ) : ?><a href="<?php echo esc_url( $ak_facebook_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill">Facebook</a><?php endif; ?>
          <?php if ( $ak_website_url ) : ?><a href="<?php echo esc_url( $ak_website_url ); ?>" target="_blank" rel="noopener" class="ak-social-pill"><?php esc_html_e( 'Website', 'artistkit' ); ?> ↗</a><?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php if ( $ak_cover_image ) : ?>
      <div class="ak-hero-image">
        <img src="<?php echo esc_url( $ak_cover_image ); ?>" alt="<?php echo esc_attr( $ak_artist_name ); ?>" />
      </div>
    <?php endif; ?>
  </div>
</header>

<main class="ak-main">

  <!-- Long bio -->
  <?php if ( $ak_bio_long ) : ?>
    <section class="ak-section">
      <div class="ak-section-inner">
        <h2 class="ak-section-title"><?php esc_html_e( 'Biography', 'artistkit' ); ?></h2>
        <div class="ak-bio-long"><?php echo nl2br( esc_html( $ak_bio_long ) ); ?></div>
        <?php if ( $ak_bio_short ) : ?>
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
  $ak_artist_embed_html = '';
  if ( $ak_embed_type && $ak_embed_url ) {
      $ak_embed_url_clean = $ak_embed_url;
      if ( $ak_embed_type === 'spotify' ) {
          preg_match( '#open\.spotify\.com/(?:intl-[a-z]{2}/)?(track|album|playlist)/([a-zA-Z0-9]+)#', $ak_embed_url, $ak_sm );
          if ( ! empty( $ak_sm[1] ) && ! empty( $ak_sm[2] ) ) {
              $ak_embed_url_clean = 'https://open.spotify.com/embed/' . $ak_sm[1] . '/' . $ak_sm[2];
          }
      } elseif ( $ak_embed_type === 'soundcloud' ) {
          $ak_embed_url_clean = 'https://w.soundcloud.com/player/?url=' . rawurlencode( $ak_embed_url ) . '&color=%238b5cf6&auto_play=false&show_artwork=true';
      }
      $ak_h = (string) apply_filters( 'artistkit_embed_height', $ak_embed_height, $ak_post );
      $ak_artist_embed_html = '<iframe src="' . esc_url( $ak_embed_url_clean ) . '" width="100%" height="' . esc_attr( $ak_h ) . '" frameborder="0" allowtransparency="true" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy" style="border-radius:12px;display:block;"></iframe>';
  }

  $ak_has_listen = $ak_artist_embed_html || $ak_audio_mp3_url;
  ?>
  <?php if ( $ak_has_listen ) : ?>
    <section class="ak-section ak-section-audio">
      <div class="ak-section-inner">
        <h2 class="ak-section-title"><?php esc_html_e( 'Listen', 'artistkit' ); ?></h2>

        <?php if ( $ak_artist_embed_html ) : ?>
          <div class="ak-artist-embed">
            <?php echo $ak_artist_embed_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above with esc_url + esc_attr ?>
          </div>
        <?php endif; ?>

        <?php if ( $ak_audio_mp3_url ) : ?>
          <div class="ak-audio-player<?php echo $ak_artist_embed_html ? ' ak-audio-player--spaced' : ''; ?>">
            <?php if ( $ak_audio_mp3_label ) : ?>
              <p class="ak-audio-label"><?php echo esc_html( $ak_audio_mp3_label ); ?></p>
            <?php endif; ?>
            <audio controls preload="metadata" class="ak-audio-native">
              <source src="<?php echo esc_url( $ak_audio_mp3_url ); ?>" type="audio/mpeg" />
            </audio>
            <?php if ( $ak_audio_downloadable === '1' ) : ?>
              <a href="<?php echo esc_url( $ak_audio_mp3_url ); ?>" download class="ak-btn-download ak-btn-audio-dl">
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
  do_action( 'artistkit_after_audio_section', $ak_post );
  ?>

  <!-- Press assets -->
  <?php if ( $ak_photos_zip_url || $ak_rider_url ) : ?>
    <section class="ak-section ak-section-assets">
      <div class="ak-section-inner">
        <h2 class="ak-section-title"><?php esc_html_e( 'Press Assets', 'artistkit' ); ?></h2>
        <div class="ak-assets-row">
          <?php if ( $ak_photos_zip_url ) : ?>
            <a href="<?php echo esc_url( $ak_photos_zip_url ); ?>" class="ak-btn-download" download>
              ⬇ <?php esc_html_e( 'HD Photos (.zip)', 'artistkit' ); ?>
            </a>
          <?php endif; ?>
          <?php if ( $ak_rider_url ) : ?>
            <a href="<?php echo esc_url( $ak_rider_url ); ?>" class="ak-btn-download" target="_blank">
              ⬇ <?php esc_html_e( 'Technical rider', 'artistkit' ); ?>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Press quotes -->
  <?php if ( $ak_press_quotes ) : ?>
    <section class="ak-section ak-section-quotes">
      <div class="ak-section-inner">
        <h2 class="ak-section-title"><?php esc_html_e( 'They talk about us', 'artistkit' ); ?></h2>
        <div class="ak-quotes-grid">
          <?php foreach ( $ak_press_quotes as $ak_q ) :
            if ( empty( $ak_q['quote'] ) ) continue;
          ?>
            <blockquote class="ak-quote">
              <p class="ak-quote-text">"<?php echo esc_html( $ak_q['quote'] ); ?>"</p>
              <?php if ( ! empty( $ak_q['source'] ) ) : ?>
                <footer class="ak-quote-source">
                  <?php if ( ! empty( $ak_q['url'] ) ) : ?>
                    <a href="<?php echo esc_url( $ak_q['url'] ); ?>" target="_blank" rel="noopener">— <?php echo esc_html( $ak_q['source'] ); ?></a>
                  <?php else : ?>
                    — <?php echo esc_html( $ak_q['source'] ); ?>
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
  do_action( 'artistkit_after_press_quotes', $ak_post );
  ?>

  <!-- Contact -->
  <section class="ak-section ak-section-contact">
    <div class="ak-section-inner">
      <h2 class="ak-section-title"><?php esc_html_e( 'Contact', 'artistkit' ); ?></h2>
      <div class="ak-contact-grid">
        <?php if ( $ak_contact_booking ) : ?>
          <div class="ak-contact-card">
            <span class="ak-contact-role"><?php esc_html_e( 'Booking', 'artistkit' ); ?></span>
            <?php $ak_safe_booking = antispambot( $ak_contact_booking ); ?>
            <a href="<?php echo esc_url( 'mailto:' . $ak_safe_booking ); ?>" class="ak-contact-email">
              <?php echo esc_html( $ak_safe_booking ); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ( $ak_contact_management ) : ?>
          <div class="ak-contact-card">
            <span class="ak-contact-role"><?php esc_html_e( 'Management', 'artistkit' ); ?></span>
            <?php $ak_safe_mgmt = antispambot( $ak_contact_management ); ?>
            <a href="<?php echo esc_url( 'mailto:' . $ak_safe_mgmt ); ?>" class="ak-contact-email">
              <?php echo esc_html( $ak_safe_mgmt ); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ( $ak_contact_press ) : ?>
          <div class="ak-contact-card">
            <span class="ak-contact-role"><?php esc_html_e( 'Press / PR', 'artistkit' ); ?></span>
            <?php $ak_safe_press = antispambot( $ak_contact_press ); ?>
            <a href="<?php echo esc_url( 'mailto:' . $ak_safe_press ); ?>" class="ak-contact-email">
              <?php echo esc_html( $ak_safe_press ); ?>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <?php do_action( 'artistkit_main_end', $ak_post ); ?>
</main>

<!-- FOOTER -->
<footer class="ak-footer">
  <div class="ak-footer-inner">
    <span><?php echo esc_html( $ak_artist_name ); ?> · <?php esc_html_e( 'Press Kit', 'artistkit' ); ?></span>
  </div>
</footer>

<?php
// wp_footer() prints scripts enqueued for the footer (incl. ak-frontend.js).
wp_footer();
do_action( 'artistkit_footer_scripts', $ak_post );
?>
</body>
</html>
