<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap ak-wrap">
  <h1 class="ak-page-title">🎙 ArtistKit</h1>

  <div class="ak-dashboard-grid">

    <!-- Artist EPK card -->
    <div class="ak-card">
      <div class="ak-card-header">
        <span class="ak-card-icon">🎤</span>
        <div>
          <h2><?php _e( 'EPK Artiste', 'artistkit' ); ?></h2>
          <p><?php _e( 'Ton press kit général — toujours à jour.', 'artistkit' ); ?></p>
        </div>
      </div>
      <?php if ( $artist_epk ) : ?>
        <div class="ak-epk-status ak-status-published">
          <span class="ak-dot"></span>
          <?php _e( 'Publié', 'artistkit' ); ?>
          &nbsp;·&nbsp;
          <a href="<?php echo home_url( '/epk' ); ?>" target="_blank"><?php echo home_url( '/epk' ); ?> ↗</a>
        </div>
        <div class="ak-card-actions">
          <a href="<?php echo get_edit_post_link( $artist_epk->ID ); ?>" class="button button-primary"><?php _e( 'Modifier', 'artistkit' ); ?></a>
          <a href="<?php echo home_url( '/epk' ); ?>" target="_blank" class="button"><?php _e( 'Voir l\'EPK', 'artistkit' ); ?></a>
        </div>
      <?php else : ?>
        <div class="ak-epk-status ak-status-empty">
          <span class="ak-dot ak-dot-empty"></span>
          <?php _e( 'Pas encore créé', 'artistkit' ); ?>
        </div>
        <div class="ak-card-actions">
          <a href="<?php echo admin_url( 'post-new.php?post_type=ak_artist_epk' ); ?>" class="button button-primary"><?php _e( 'Créer mon EPK Artiste', 'artistkit' ); ?></a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Release EPKs -->
    <div class="ak-card">
      <div class="ak-card-header">
        <span class="ak-card-icon">🎵</span>
        <div>
          <h2><?php _e( 'EPK Releases', 'artistkit' ); ?> <?php if ( ! ak_is_pro() ) : ?><span class="ak-pro-badge">PRO</span><?php endif; ?></h2>
          <p><?php _e( 'Un press kit dédié à chaque sortie.', 'artistkit' ); ?></p>
        </div>
      </div>

      <?php if ( ak_is_pro() ) : ?>
        <?php if ( $releases ) : ?>
          <ul class="ak-releases-list">
            <?php foreach ( $releases as $release ) :
              $slug = $release->post_name;
              $type = get_post_meta( $release->ID, 'ak_release_type', true ) ?: 'Single';
              $date = get_post_meta( $release->ID, 'ak_release_date', true );
            ?>
              <li>
                <div class="ak-release-info">
                  <?php echo get_the_post_thumbnail( $release->ID, [40,40], ['class' => 'ak-release-thumb'] ); ?>
                  <div>
                    <strong><?php echo esc_html( $release->post_title ); ?></strong>
                    <span class="ak-release-meta"><?php echo esc_html( $type ); ?> <?php echo $date ? '· ' . esc_html( $date ) : ''; ?></span>
                  </div>
                </div>
                <div class="ak-release-actions">
                  <a href="<?php echo get_edit_post_link( $release->ID ); ?>" title="Modifier">✏️</a>
                  <a href="<?php echo home_url( '/epk/' . $slug ); ?>" target="_blank" title="Voir">↗</a>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else : ?>
          <p class="ak-empty"><?php _e( 'Aucune release pour l\'instant.', 'artistkit' ); ?></p>
        <?php endif; ?>
        <div class="ak-card-actions">
          <a href="<?php echo admin_url( 'post-new.php?post_type=ak_release_epk' ); ?>" class="button button-primary"><?php _e( '+ Nouvelle Release', 'artistkit' ); ?></a>
        </div>
      <?php else : ?>
        <div class="ak-pro-gate">
          <p><?php _e( 'Les EPK Release sont disponibles en plan Pro.', 'artistkit' ); ?></p>
          <a href="<?php echo admin_url( 'admin.php?page=artistkit-settings' ); ?>" class="button ak-btn-pro"><?php _e( 'Activer Pro · 49 €', 'artistkit' ); ?></a>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Quick links -->
  <div class="ak-card ak-card-links">
    <a href="<?php echo admin_url( 'admin.php?page=artistkit-settings' ); ?>">⚙️ <?php _e( 'Réglages & design', 'artistkit' ); ?></a>
    <a href="https://promotracker.fr/artistkit" target="_blank">📖 <?php _e( 'Documentation', 'artistkit' ); ?></a>
    <a href="https://promotracker.fr/artistkit#pricing" target="_blank" <?php echo ak_is_pro() ? 'style="display:none"' : ''; ?>>⭐ <?php _e( 'Passer en Pro', 'artistkit' ); ?></a>
  </div>
</div>
