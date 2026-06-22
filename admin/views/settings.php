<?php
defined( 'ABSPATH' ) || exit;

$ak_settings = get_option( 'artistkit_settings', [] );

/**
 * Filter — Pro extends the template list with its premium templates.
 * Free exposes only 'dark-minimal'.
 */
$ak_templates = apply_filters( 'artistkit_settings_templates', [
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
$ak_fonts = apply_filters( 'artistkit_settings_fonts', [
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
        <?php wp_nonce_field( 'artistkit_save_settings_action' ); ?>
        <input type="hidden" name="action" value="artistkit_save_settings" />

        <!-- Logo -->
        <div class="ak-field-group">
          <label><?php esc_html_e( 'Logo', 'artistkit' ); ?></label>
          <div class="ak-logo-upload">
            <?php $ak_logo_url = $ak_settings['logo_url'] ?? ''; ?>
            <div class="ak-logo-preview" id="ak-logo-preview"<?php echo $ak_logo_url ? '' : ' hidden'; ?>>
              <img src="<?php echo esc_url( $ak_logo_url ); ?>" alt="<?php esc_attr_e( 'Logo', 'artistkit' ); ?>" id="ak-logo-img" class="ak-logo-img" />
              <button type="button" class="button" id="ak-logo-remove">✕</button>
            </div>
            <input type="hidden" name="logo_url" id="ak-logo-url" value="<?php echo esc_attr( $ak_logo_url ); ?>" />
            <button type="button" class="button" id="ak-logo-upload-btn">
              <?php echo $ak_logo_url ? esc_html__( '🔄 Change', 'artistkit' ) : esc_html__( '⬆ Upload logo', 'artistkit' ); ?>
            </button>
          </div>
          <p class="description"><?php esc_html_e( 'PNG, SVG or JPG — transparent background recommended.', 'artistkit' ); ?></p>
        </div>

        <!-- Templates -->
        <div class="ak-field-group">
          <label><?php esc_html_e( 'Template', 'artistkit' ); ?></label>
          <div class="ak-templates-grid">
            <?php
            $ak_current = $ak_settings['template'] ?? 'dark-minimal';
            foreach ( $ak_templates as $ak_key => $ak_t ) :
              $ak_locked = ! empty( $ak_t['locked'] );
            ?>
            <label class="ak-template-option <?php echo $ak_current === $ak_key ? 'ak-selected' : ''; ?> <?php echo $ak_locked ? 'ak-locked' : ''; ?>">
              <input type="radio" name="template" value="<?php echo esc_attr( $ak_key ); ?>"
                <?php checked( $ak_current, $ak_key ); ?> <?php disabled( $ak_locked, true ); ?> />
              <span class="ak-template-preview" style="background:<?php echo esc_attr( $ak_t['bg'] ); ?>">
                <span class="ak-tpl-dot" style="background:<?php echo esc_attr( $ak_t['accent'] ); ?>"></span>
                <?php if ( $ak_locked ) : ?><span class="ak-tpl-lock">PRO</span><?php endif; ?>
              </span>
              <span class="ak-template-label">
                <?php echo esc_html( $ak_t['label'] ); ?>
                <small><?php echo esc_html( $ak_t['genres'] ); ?></small>
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
                value="<?php echo esc_attr( $ak_settings['accent_color'] ?? '#8b5cf6' ); ?>" />
              <input type="text" id="accent_color_text"
                value="<?php echo esc_attr( $ak_settings['accent_color'] ?? '#8b5cf6' ); ?>"
                maxlength="7" class="ak-color-text" />
            </div>
          </div>
          <div class="ak-field-group">
            <label for="font_pair"><?php esc_html_e( 'Font pair', 'artistkit' ); ?></label>
            <select id="font_pair" name="font_pair">
              <?php
              $ak_cf = $ak_settings['font_pair'] ?? 'inter';
              foreach ( $ak_fonts as $ak_fk => $ak_fl ) :
                $ak_flocked = is_array( $ak_fl ) && ! empty( $ak_fl['locked'] );
                $ak_flabel  = is_array( $ak_fl ) ? $ak_fl['label'] : $ak_fl;
              ?>
              <option value="<?php echo esc_attr( $ak_fk ); ?>"
                <?php selected( $ak_cf, $ak_fk ); ?> <?php disabled( $ak_flocked, true ); ?>>
                <?php echo esc_html( $ak_flabel ); ?><?php echo $ak_flocked ? ' (Pro)' : ''; ?>
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
    do_action( 'artistkit_settings_after', $ak_settings );
    ?>

  </div><!-- .ak-settings-grid -->
</div><!-- .wrap -->
