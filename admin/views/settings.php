<?php
defined( 'ABSPATH' ) || exit;

$settings = get_option( 'ak_settings', [] );

/**
 * Filter — Pro extends the template list with its premium templates.
 * Free exposes only 'dark-minimal'.
 */
$templates = apply_filters( 'artistkit_settings_templates', [
    'dark-minimal' => [
        'label'  => __( 'Dark / Minimal', 'artistkit' ),
        'genres' => __( 'Rap · Electronic · Ambient', 'artistkit' ),
        'bg'     => '#0f0f17',
        'accent' => '#8b5cf6',
    ],
] );

/**
 * Filter — Pro extends the font list with its premium font pairs.
 * Free exposes only 'inter'.
 */
$fonts = apply_filters( 'artistkit_settings_fonts', [
    'inter' => __( 'Inter — Modern & readable', 'artistkit' ),
] );
?>

<div class="wrap ak-wrap">
  <h1 class="ak-page-title">⚙️ <?php esc_html_e( 'ArtistKit Settings', 'artistkit' ); ?></h1>

  <div class="ak-settings-grid">

    <!-- ── Design ─────────────────────────────────────────────────────────── -->
    <div class="ak-card">
      <h2 class="ak-section-title">🎨 <?php esc_html_e( 'EPK Design', 'artistkit' ); ?></h2>
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
              <?php echo $logo_url ? esc_html__( '🔄 Change', 'artistkit' ) : esc_html__( '⬆ Upload logo', 'artistkit' ); ?>
            </button>
          </div>
          <p class="description"><?php esc_html_e( 'PNG, SVG or JPG — transparent background recommended.', 'artistkit' ); ?></p>
        </div>

        <!-- Templates -->
        <div class="ak-field-group">
          <label><?php esc_html_e( 'Template', 'artistkit' ); ?></label>
          <div class="ak-templates-grid">
            <?php
            $current = $settings['template'] ?? 'dark-minimal';
            foreach ( $templates as $key => $t ) :
              $locked = ! empty( $t['locked'] );
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

        <!-- Accent color + Font -->
        <div class="ak-field-row">
          <div class="ak-field-group">
            <label for="accent_color"><?php esc_html_e( 'Accent color', 'artistkit' ); ?></label>
            <div class="ak-color-input">
              <input type="color" id="accent_color" name="accent_color"
                value="<?php echo esc_attr( $settings['accent_color'] ?? '#8b5cf6' ); ?>" />
              <input type="text" id="accent_color_text"
                value="<?php echo esc_attr( $settings['accent_color'] ?? '#8b5cf6' ); ?>"
                maxlength="7" class="ak-color-text" />
            </div>
          </div>
          <div class="ak-field-group">
            <label for="font_pair"><?php esc_html_e( 'Font pair', 'artistkit' ); ?></label>
            <select id="font_pair" name="font_pair">
              <?php
              $cf = $settings['font_pair'] ?? 'inter';
              foreach ( $fonts as $fk => $fl ) :
                $flocked = is_array( $fl ) && ! empty( $fl['locked'] );
                $flabel  = is_array( $fl ) ? $fl['label'] : $fl;
              ?>
              <option value="<?php echo esc_attr( $fk ); ?>"
                <?php selected( $cf, $fk ); ?> <?php disabled( $flocked, true ); ?>>
                <?php echo esc_html( $flabel ); ?><?php echo $flocked ? ' (Pro)' : ''; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="ak-field-actions">
          <button type="submit" class="button button-primary"><?php esc_html_e( 'Save', 'artistkit' ); ?></button>
          <a href="<?php echo esc_url( home_url( '/epk' ) ); ?>" target="_blank" class="button">
            <?php esc_html_e( 'Preview →', 'artistkit' ); ?>
          </a>
        </div>
      </form>
    </div>

    <?php
    /**
     * Extension hook — Pro renders its License card here.
     */
    do_action( 'artistkit_settings_after', $settings );
    ?>

  </div><!-- .ak-settings-grid -->
</div><!-- .wrap -->

<style>
.ak-settings-grid { display:grid; grid-template-columns:1fr; gap:24px; margin-top:24px; max-width:760px; }
.ak-settings-grid.ak-has-pro-card { grid-template-columns:1fr 1fr; max-width:none; }
@media(max-width:900px) { .ak-settings-grid.ak-has-pro-card { grid-template-columns:1fr; } }
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
.ak-templates-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:8px; }
.ak-template-option { cursor:pointer; }
.ak-template-option input { display:none; }
.ak-template-preview { display:flex; align-items:center; justify-content:center; height:48px; border-radius:6px; border:2px solid transparent; position:relative; transition:border-color .15s; }
.ak-template-option.ak-selected .ak-template-preview { border-color:#00dfd4; }
.ak-tpl-dot { width:16px; height:16px; border-radius:50%; }
.ak-tpl-lock { position:absolute; top:4px; right:4px; background:rgba(0,0,0,.6); color:#fff; font-size:9px; font-weight:700; padding:1px 4px; border-radius:3px; }
.ak-template-label { display:block; font-size:11px; text-align:center; margin-top:4px; color:#555; }
.ak-template-label small { display:block; font-size:10px; color:#999; }
.ak-template-option.ak-locked { opacity:.6; cursor:not-allowed; }
</style>
