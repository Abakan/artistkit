<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap ak-wrap">
  <h1 class="ak-page-title">🎙 ArtistKit</h1>

  <div class="ak-dashboard-grid">

    <!-- Artist EPK card -->
    <div class="ak-card">
      <div class="ak-card-header">
        <span class="ak-card-icon">🎤</span>
        <div>
          <h2><?php esc_html_e( 'Artist EPK', 'artistkit' ); ?></h2>
          <p><?php esc_html_e( 'Your main press kit — always up to date.', 'artistkit' ); ?></p>
        </div>
      </div>
      <?php if ( $artist_epk ) : ?>
        <div class="ak-epk-status ak-status-published">
          <span class="ak-dot"></span>
          <?php esc_html_e( 'Published', 'artistkit' ); ?>
          &nbsp;·&nbsp;
          <a href="<?php echo esc_url( home_url( '/epk' ) ); ?>" target="_blank"><?php echo esc_html( home_url( '/epk' ) ); ?> ↗</a>
        </div>
        <div class="ak-card-actions">
          <a href="<?php echo esc_url( get_edit_post_link( $artist_epk->ID ) ); ?>" class="button button-primary"><?php esc_html_e( 'Edit', 'artistkit' ); ?></a>
          <a href="<?php echo esc_url( home_url( '/epk' ) ); ?>" target="_blank" class="button"><?php esc_html_e( 'View EPK', 'artistkit' ); ?></a>
        </div>
      <?php else : ?>
        <div class="ak-epk-status ak-status-empty">
          <span class="ak-dot ak-dot-empty"></span>
          <?php esc_html_e( 'Not created yet', 'artistkit' ); ?>
        </div>
        <div class="ak-card-actions">
          <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=ak_artist_epk' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Create my Artist EPK', 'artistkit' ); ?></a>
        </div>
      <?php endif; ?>
    </div>

    <?php
    /**
     * Extension hook — Pro injects its Releases card here.
     */
    do_action( 'artistkit_after_dashboard_cards' );
    ?>

  </div>

  <!-- Quick links -->
  <div class="ak-card ak-card-links">
    <a href="<?php echo esc_url( admin_url( 'admin.php?page=artistkit-settings' ) ); ?>">⚙️ <?php esc_html_e( 'Settings & design', 'artistkit' ); ?></a>
    <a href="https://promotracker.fr/artistkit" target="_blank">📖 <?php esc_html_e( 'Documentation', 'artistkit' ); ?></a>
    <a href="<?php echo esc_url( admin_url( 'admin.php?page=artistkit-upgrade' ) ); ?>">⭐ <?php esc_html_e( 'Upgrade to Pro', 'artistkit' ); ?></a>
  </div>
</div>
