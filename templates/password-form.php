<?php defined( 'ABSPATH' ) || exit;
$settings = get_option( 'ak_settings', [] );
$accent   = $settings['accent_color'] ?? '#8b5cf6';
$theme    = $settings['template'] ?? 'dark-minimal';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Press Kit — Accès protégé</title>
  <link rel="stylesheet" href="<?php echo esc_url( AK_URL ); ?>assets/css/frontend.css?v=<?php echo esc_attr( AK_VERSION ); ?>" />
  <link rel="stylesheet" href="<?php echo esc_url( AK_URL ); ?>assets/css/theme-<?php echo esc_attr( $theme ); ?>.css?v=<?php echo esc_attr( AK_VERSION ); ?>" />
  <style>:root { --ak-accent: <?php echo esc_attr( $accent ); ?>; }</style>
</head>
<body class="ak-epk ak-theme-<?php echo esc_attr( $theme ); ?>">
  <div class="ak-password-screen">
    <div class="ak-password-card">
      <div class="ak-password-icon">🔒</div>
      <h1><?php esc_html_e( 'Press Kit protégé', 'artistkit' ); ?></h1>
      <p><?php esc_html_e( "Ce press kit est accessible sur invitation. Entre le mot de passe fourni par l'artiste.", 'artistkit' ); ?></p>
      <form method="post" class="ak-password-form">
        <?php wp_nonce_field( 'ak_epk_password_' . absint( $post_id ), 'ak_epk_nonce' ); ?>
        <?php if ( $wrong ) : ?>
          <p class="ak-password-error"><?php esc_html_e( 'Mot de passe incorrect. Réessaie.', 'artistkit' ); ?></p>
        <?php endif; ?>
        <input type="password" name="ak_epk_password" placeholder="<?php esc_attr_e( 'Mot de passe', 'artistkit' ); ?>" autofocus required />
        <button type="submit" class="ak-btn-primary"><?php esc_html_e( 'Accéder →', 'artistkit' ); ?></button>
      </form>
    </div>
  </div>
</body>
</html>
