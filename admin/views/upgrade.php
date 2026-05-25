<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap ak-upgrade-wrap">
  <h1 class="ak-page-title">⭐ <?php esc_html_e( 'Upgrade to ArtistKit Pro', 'artistkit' ); ?></h1>

  <p class="ak-upgrade-lead">
    <?php esc_html_e( 'Unlock advanced features designed for active musicians and labels.', 'artistkit' ); ?>
  </p>

  <div class="ak-upgrade-grid">

    <div class="ak-upgrade-feature">
      <span class="ak-upgrade-icon">🎵</span>
      <h3><?php esc_html_e( 'Release EPK per song', 'artistkit' ); ?></h3>
      <p><?php esc_html_e( 'A dedicated press kit page for each single, EP or album.', 'artistkit' ); ?></p>
    </div>

    <div class="ak-upgrade-feature">
      <span class="ak-upgrade-icon">📊</span>
      <h3><?php esc_html_e( 'Real-time Analytics', 'artistkit' ); ?></h3>
      <p><?php esc_html_e( 'Track views per EPK with bot-filtered insights and traffic sources.', 'artistkit' ); ?></p>
    </div>

    <div class="ak-upgrade-feature">
      <span class="ak-upgrade-icon">📄</span>
      <h3><?php esc_html_e( 'PDF Export', 'artistkit' ); ?></h3>
      <p><?php esc_html_e( 'Export your EPK as a polished PDF, ready to send to bookers.', 'artistkit' ); ?></p>
    </div>

    <div class="ak-upgrade-feature">
      <span class="ak-upgrade-icon">🔒</span>
      <h3><?php esc_html_e( 'Password Protection', 'artistkit' ); ?></h3>
      <p><?php esc_html_e( 'Send confidential EPKs before public release with password access.', 'artistkit' ); ?></p>
    </div>

    <div class="ak-upgrade-feature">
      <span class="ak-upgrade-icon">🎨</span>
      <h3><?php esc_html_e( '5 Templates + 8 Font Pairs', 'artistkit' ); ?></h3>
      <p><?php esc_html_e( 'Match your visual identity with curated design presets.', 'artistkit' ); ?></p>
    </div>

    <div class="ak-upgrade-feature">
      <span class="ak-upgrade-icon">🎼</span>
      <h3><?php esc_html_e( 'Track Patchwork', 'artistkit' ); ?></h3>
      <p><?php esc_html_e( '5 audio players in a grid layout — perfect for featuring multiple tracks.', 'artistkit' ); ?></p>
    </div>

    <div class="ak-upgrade-feature">
      <span class="ak-upgrade-icon">✍️</span>
      <h3><?php esc_html_e( 'Press Talking Points', 'artistkit' ); ?></h3>
      <p><?php esc_html_e( 'Formatted briefs for journalists with story angles.', 'artistkit' ); ?></p>
    </div>

    <div class="ak-upgrade-feature">
      <span class="ak-upgrade-icon">📻</span>
      <h3><?php esc_html_e( 'Radio Info', 'artistkit' ); ?></h3>
      <p><?php esc_html_e( 'BPM, key, duration, radio edit and ISRC fields for radio pitches.', 'artistkit' ); ?></p>
    </div>

  </div>

  <div class="ak-upgrade-cta">
    <a href="https://promotracker.fr/artistkit" target="_blank" class="button button-primary button-hero">
      <?php esc_html_e( 'Get ArtistKit Pro', 'artistkit' ); ?>
    </a>
    <p class="ak-upgrade-trust">
      <?php esc_html_e( 'Secure Stripe payment · License key delivered by email · Free plugin stays active without renewal', 'artistkit' ); ?>
    </p>
  </div>

</div>

<style>
.ak-upgrade-wrap { max-width:960px; }
.ak-upgrade-lead { font-size:1.05rem; color:#555; margin:0 0 28px; max-width:640px; }
.ak-upgrade-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:16px; margin-bottom:32px; }
.ak-upgrade-feature { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:20px; }
.ak-upgrade-icon { font-size:24px; display:block; margin-bottom:8px; }
.ak-upgrade-feature h3 { font-size:14px; margin:0 0 6px; color:#0a0a0f; }
.ak-upgrade-feature p { font-size:12px; color:#666; margin:0; line-height:1.5; }
.ak-upgrade-cta { background:linear-gradient(135deg,#7c3aed,#4f46e5); color:#fff; padding:32px; border-radius:12px; text-align:center; }
.ak-upgrade-cta .button-hero { background:#fff!important; color:#4f46e5!important; border:none!important; font-weight:700!important; }
.ak-upgrade-trust { color:rgba(255,255,255,0.85); margin:14px 0 0; font-size:12px; }
</style>
