<?php
defined( 'ABSPATH' ) || exit;

$is_pro   = ak_is_pro();
$settings = get_option( 'ak_settings', [] );

// ── Messages flash ────────────────────────────────────────────────────────────
$license_status = ! empty( $_GET['license_status'] ) ? sanitize_key( $_GET['license_status'] ) : '';
?>

<div class="wrap ak-wrap">
  <h1 class="ak-page-title">⚙️ Réglages ArtistKit</h1>

  <?php if ( $license_status === 'activated' ) : ?>
    <div class="notice notice-success is-dismissible"><p>✅ <?php esc_html_e( 'Licence activée avec succès. Bienvenue dans ArtistKit Pro !', 'artistkit' ); ?></p></div>
  <?php elseif ( $license_status === 'invalid' ) : ?>
    <div class="notice notice-error is-dismissible"><p>❌ <?php esc_html_e( 'Clé de licence invalide ou déjà utilisée sur un autre site.', 'artistkit' ); ?></p></div>
  <?php elseif ( $license_status === 'deactivated' ) : ?>
    <div class="notice notice-info is-dismissible"><p>ℹ️ <?php esc_html_e( 'Licence désactivée.', 'artistkit' ); ?></p></div>
  <?php endif; ?>

  <div class="ak-settings-grid">

    <!-- ── Design ─────────────────────────────────────────────────────────── -->
    <div class="ak-card">
      <h2 class="ak-section-title">🎨 <?php esc_html_e( 'Design de tes EPK', 'artistkit' ); ?></h2>
      <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field( 'ak_save_settings' ); ?>
        <input type="hidden" name="action" value="ak_save_settings" />

        <!-- Logo -->
        <div class="ak-field-group">
          <label><?php esc_html_e( 'Logo', 'artistkit' ); ?></label>
          <div class="ak-logo-upload">
            <?php $logo_url = $settings['logo_url'] ?? ''; ?>
            <div class="ak-logo-preview" id="ak-logo-preview" <?php echo $logo_url ? '' : 'style="display:none"'; ?>>
              <img src="<?php echo esc_url( $logo_url ); ?>" alt="Logo" id="ak-logo-img"
                   style="max-height:60px;border-radius:6px;border:1px solid #e0e0e0;padding:6px;background:#fff;" />
              <button type="button" class="button" id="ak-logo-remove">✕</button>
            </div>
            <input type="hidden" name="logo_url" id="ak-logo-url" value="<?php echo esc_attr( $logo_url ); ?>" />
            <button type="button" class="button" id="ak-logo-upload-btn">
              <?php echo $logo_url ? esc_html__( '🔄 Changer', 'artistkit' ) : esc_html__( '⬆ Uploader mon logo', 'artistkit' ); ?>
            </button>
          </div>
          <p class="description"><?php esc_html_e( 'PNG, SVG ou JPG — fond transparent recommandé.', 'artistkit' ); ?></p>
        </div>

        <!-- Templates -->
        <div class="ak-field-group">
          <label><?php esc_html_e( 'Template', 'artistkit' ); ?></label>
          <div class="ak-templates-grid">
            <?php
            $templates = [
              'dark-minimal'  => [ 'label' => 'Noir / Minimal',   'genres' => 'Rap · Électro · Ambient',  'bg' => '#0f0f17', 'accent' => '#8b5cf6' ],
              'light-clean'   => [ 'label' => 'Clair / Épuré',    'genres' => 'Pop · Folk · Indie',       'bg' => '#f8f9fa', 'accent' => '#e91e8c' ],
              'bold-contrast' => [ 'label' => 'Bold / Contrasté', 'genres' => 'Rock · Metal · Punk',      'bg' => '#1a0a00', 'accent' => '#ef4444' ],
              'warm-organic'  => [ 'label' => 'Chaleureux',       'genres' => 'Jazz · Soul · World',      'bg' => '#0d1b14', 'accent' => '#f59e0b' ],
              'neon-dark'     => [ 'label' => 'Néon / Sombre',    'genres' => 'Techno · Club · Hyperpop', 'bg' => '#0f0028', 'accent' => '#22d3ee' ],
            ];
            $current = $settings['template'] ?? 'dark-minimal';
            foreach ( $templates as $key => $t ) :
              $locked = in_array( $key, [ 'bold-contrast', 'warm-organic', 'neon-dark' ], true ) && ! $is_pro;
            ?>
            <label class="ak-template-option <?php echo $current === $key ? 'ak-selected' : ''; ?> <?php echo $locked ? 'ak-locked' : ''; ?>">
              <input type="radio" name="template" value="<?php echo esc_attr( $key ); ?>"
                <?php checked( $current, $key ); ?> <?php disabled( $locked, true ); ?> />
              <span class="ak-template-preview" style="background:<?php echo esc_attr( $t['bg'] ); ?>">
                <span class="ak-tpl-dot" style="background:<?php echo esc_attr( $t['accent'] ); ?>"></span>
                <?php if ( $locked ) : ?><span class="ak-tpl-lock">PRO</span><?php endif; ?>
              </span>
              <span class="ak-template-label">
                <?php echo esc_html( $t['label'] ); ?>
                <small><?php echo esc_html( $t['genres'] ); ?></small>
              </span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Couleur + Police -->
        <div class="ak-field-row">
          <div class="ak-field-group">
            <label for="accent_color"><?php esc_html_e( 'Couleur principale', 'artistkit' ); ?></label>
            <div class="ak-color-input">
              <input type="color" id="accent_color" name="accent_color"
                value="<?php echo esc_attr( $settings['accent_color'] ?? '#8b5cf6' ); ?>" />
              <input type="text" id="accent_color_text"
                value="<?php echo esc_attr( $settings['accent_color'] ?? '#8b5cf6' ); ?>"
                maxlength="7" class="ak-color-text" />
            </div>
          </div>
          <div class="ak-field-group">
            <label for="font_pair"><?php esc_html_e( 'Paire de polices', 'artistkit' ); ?></label>
            <select id="font_pair" name="font_pair">
              <?php
              $fonts = [
                'inter'    => 'Inter — Moderne & lisible',
                'poppins'  => 'Poppins — Rond & amical',
                'syne'     => 'Syne — Expérimental & bold',
                'dmserif'  => 'DM Serif — Élégant & classique',
                'space'    => 'Space Grotesk — Tech & précis',
                'playfair' => 'Playfair — Luxe & sophistiqué',
                'bebas'    => 'Bebas Neue — Impact maximal',
                'outfit'   => 'Outfit — Neutre & polyvalent',
              ];
              $cf = $settings['font_pair'] ?? 'inter';
              foreach ( $fonts as $fk => $fl ) :
                $flocked = in_array( $fk, [ 'syne', 'dmserif', 'space', 'playfair', 'bebas', 'outfit' ], true ) && ! $is_pro;
              ?>
              <option value="<?php echo esc_attr( $fk ); ?>"
                <?php selected( $cf, $fk ); ?> <?php disabled( $flocked, true ); ?>>
                <?php echo esc_html( $fl ); ?><?php echo $flocked ? ' (Pro)' : ''; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="ak-field-actions">
          <button type="submit" class="button button-primary"><?php esc_html_e( 'Enregistrer', 'artistkit' ); ?></button>
          <a href="<?php echo esc_url( home_url( '/epk' ) ); ?>" target="_blank" class="button">
            <?php esc_html_e( 'Prévisualiser →', 'artistkit' ); ?>
          </a>
        </div>
      </form>
    </div>

    <!-- ── Licence Pro ─────────────────────────────────────────────────────── -->
    <div class="ak-card">
      <h2 class="ak-section-title">⭐ <?php esc_html_e( 'Licence Pro', 'artistkit' ); ?></h2>

      <?php if ( $is_pro ) : ?>

        <!-- Licence active -->
        <div class="ak-license-active">
          <div class="ak-license-badge">✓ Pro</div>
          <div class="ak-license-details">
            <?php if ( ! empty( $settings['license_email'] ) ) : ?>
              <p><?php printf(
                esc_html__( 'Licence associée à %s', 'artistkit' ),
                '<strong>' . esc_html( $settings['license_email'] ) . '</strong>'
              ); ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $settings['license_key'] ) ) : ?>
              <p class="ak-license-key"><code><?php echo esc_html( $settings['license_key'] ); ?></code></p>
            <?php endif; ?>
            <?php if ( ! empty( $settings['license_expires'] ) ) : ?>
              <p class="ak-license-expires">
                <?php printf(
                  esc_html__( 'Valide jusqu\'au %s', 'artistkit' ),
                  '<strong>' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $settings['license_expires'] ) ) ) . '</strong>'
                ); ?>
              </p>
            <?php endif; ?>
          </div>
        </div>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:20px">
          <?php wp_nonce_field( 'ak_license_action' ); ?>
          <input type="hidden" name="action" value="ak_deactivate_license" />
          <button type="submit" class="button">
            <?php esc_html_e( 'Désactiver la licence', 'artistkit' ); ?>
          </button>
        </form>

      <?php else : ?>

        <!-- Pas de licence -->
        <p style="color:#555;margin-bottom:20px">
          <?php esc_html_e( 'Entre ta clé de licence pour débloquer les EPK Release, analytics, templates supplémentaires et export PDF.', 'artistkit' ); ?>
        </p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
          <?php wp_nonce_field( 'ak_license_action' ); ?>
          <input type="hidden" name="action" value="ak_activate_license" />
          <div class="ak-license-input-row">
            <input type="text" name="ak_license_key" class="regular-text"
              placeholder="XXXXX-XXXXX-XXXXX-XXXXX"
              value="<?php echo esc_attr( $settings['license_key'] ?? '' ); ?>"
              style="font-family:monospace;letter-spacing:1px" />
            <button type="submit" class="button button-primary">
              <?php esc_html_e( 'Activer', 'artistkit' ); ?>
            </button>
          </div>
        </form>

        <!-- CTA Achat -->
        <div class="ak-pro-cta">
          <p><?php esc_html_e( 'Pas encore de licence ?', 'artistkit' ); ?></p>
          <a href="https://promotracker.fr/artistkit#pricing" target="_blank" class="button ak-btn-pro">
            <?php esc_html_e( 'Obtenir ArtistKit Pro — 49 € →', 'artistkit' ); ?>
          </a>
          <p class="description" style="margin-top:8px">
            <?php esc_html_e( 'Paiement sécurisé · Clé envoyée par email · 19 €/an ensuite', 'artistkit' ); ?>
          </p>
        </div>

        <!-- Features Pro -->
        <div class="ak-pro-features">
          <p class="ak-features-title"><?php esc_html_e( 'Inclus en Pro :', 'artistkit' ); ?></p>
          <ul>
            <li>✓ <?php esc_html_e( 'EPK Release illimités (1 par sortie)', 'artistkit' ); ?></li>
            <li>✓ <?php esc_html_e( '5 templates · 8 paires de polices', 'artistkit' ); ?></li>
            <li>✓ <?php esc_html_e( 'Analytics en temps réel', 'artistkit' ); ?></li>
            <li>✓ <?php esc_html_e( 'Export PDF', 'artistkit' ); ?></li>
            <li>✓ <?php esc_html_e( 'Protection par mot de passe', 'artistkit' ); ?></li>
            <li>✓ <?php esc_html_e( 'Talking points journalistes', 'artistkit' ); ?></li>
            <li>✓ <?php esc_html_e( 'BPM, tonalité, ISRC', 'artistkit' ); ?></li>
            <li>✓ <?php esc_html_e( 'Mises à jour incluses', 'artistkit' ); ?></li>
          </ul>
        </div>

      <?php endif; ?>
    </div>

  </div><!-- .ak-settings-grid -->
</div><!-- .wrap -->

<style>
.ak-settings-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-top:24px; }
@media(max-width:900px) { .ak-settings-grid { grid-template-columns:1fr; } }
.ak-card { background:#fff; border:1px solid #e0e0e0; border-radius:10px; padding:28px; }
.ak-section-title { font-size:16px; font-weight:600; margin:0 0 20px; padding-bottom:12px; border-bottom:2px solid #00dfd4; color:#0a0a0f; }
.ak-field-group { margin-bottom:18px; }
.ak-field-group label { display:block; font-weight:600; margin-bottom:6px; color:#0a0a0f; }
.ak-field-group .description { margin-top:5px; font-size:12px; color:#888; }
.ak-field-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.ak-field-actions { margin-top:20px; display:flex; gap:8px; }
.ak-color-input { display:flex; gap:8px; align-items:center; }
.ak-color-text { width:90px!important; }
.ak-logo-upload { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
/* Templates */
.ak-templates-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:8px; }
.ak-template-option { cursor:pointer; }
.ak-template-option input { display:none; }
.ak-template-preview { display:flex; align-items:center; justify-content:center; height:48px; border-radius:6px; border:2px solid transparent; position:relative; transition:border-color .15s; }
.ak-template-option.ak-selected .ak-template-preview { border-color:#00dfd4; }
.ak-tpl-dot { width:16px; height:16px; border-radius:50%; }
.ak-tpl-lock { position:absolute; top:4px; right:4px; background:rgba(0,0,0,.6); color:#fff; font-size:9px; font-weight:700; padding:1px 4px; border-radius:3px; }
.ak-template-label { display:block; font-size:11px; text-align:center; margin-top:4px; color:#555; }
.ak-template-label small { display:block; font-size:10px; color:#999; }
.ak-template-option.ak-locked { opacity:.6; cursor:not-allowed; }
/* Licence active */
.ak-license-active { display:flex; gap:14px; align-items:flex-start; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:16px; }
.ak-license-badge { background:#16a34a; color:#fff; font-weight:700; font-size:13px; border-radius:6px; padding:4px 10px; white-space:nowrap; flex-shrink:0; }
.ak-license-details p { margin:0 0 4px; font-size:13px; color:#374151; }
.ak-license-key code { background:#f3f4f6; padding:3px 8px; border-radius:4px; font-size:12px; letter-spacing:1px; }
.ak-license-expires { color:#6b7280!important; font-size:12px!important; }
/* Licence inactive */
.ak-license-input-row { display:flex; gap:8px; margin-bottom:4px; }
.ak-pro-cta { margin-top:24px; padding-top:20px; border-top:1px solid #f0f0f0; }
.ak-pro-cta p { margin-bottom:10px; color:#555; font-size:13px; }
.ak-btn-pro { background:#00dfd4!important; border-color:#00b8b0!important; color:#0a0a0f!important; font-weight:700!important; }
.ak-btn-pro:hover { opacity:.9; }
.ak-pro-features { margin-top:20px; padding-top:16px; border-top:1px solid #f0f0f0; }
.ak-features-title { font-weight:600; color:#0a0a0f; margin-bottom:8px; font-size:13px; }
.ak-pro-features ul { margin:0; padding:0; list-style:none; display:grid; grid-template-columns:1fr 1fr; gap:5px; }
.ak-pro-features ul li { font-size:12px; color:#555; }
</style>
