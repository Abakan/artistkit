<?php defined( 'ABSPATH' ) || exit; ?>
<div style="padding:4px 0">
  <p style="margin-bottom:8px;font-size:0.85em;color:#8b8a99"><?php _e( 'Lien public de ton EPK :', 'artistkit' ); ?></p>
  <div style="display:flex;gap:8px;align-items:center">
    <code style="background:#1a1a2a;padding:6px 10px;border-radius:6px;font-size:0.85em;flex:1;word-break:break-all">
      <?php echo esc_html( $url ); ?>
    </code>
    <a href="<?php echo esc_url( $url ); ?>" target="_blank" class="button button-small"><?php _e( 'Voir ↗', 'artistkit' ); ?></a>
  </div>
  <p style="margin-top:8px;font-size:0.8em;color:#5a5870">
    💡 <?php _e( 'N\'oublie pas de publier ce post pour que l\'EPK soit accessible.', 'artistkit' ); ?>
  </p>
</div>
