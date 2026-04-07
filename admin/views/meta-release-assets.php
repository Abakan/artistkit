<?php defined( 'ABSPATH' ) || exit; ?>
<div class="ak-meta-grid">
  <div class="ak-field-group">
    <label><?php _e( 'Artwork haute résolution (URL)', 'artistkit' ); ?></label>
    <div class="ak-media-input">
      <input type="url" name="ak_artwork_url" id="ak_artwork_url"
        value="<?php echo esc_attr( $d['ak_artwork_url'] ); ?>"
        placeholder="https://monsite.com/artwork.jpg" class="widefat" />
      <button type="button" class="button ak-upload-btn" data-target="ak_artwork_url"><?php _e( 'Choisir', 'artistkit' ); ?></button>
    </div>
    <p class="description"><?php _e( 'Si vide, la photo mise en avant sera utilisée.', 'artistkit' ); ?></p>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Photos promo ZIP (URL)', 'artistkit' ); ?></label>
    <div class="ak-media-input">
      <input type="url" name="ak_promo_photos_zip" id="ak_promo_photos_zip"
        value="<?php echo esc_attr( $d['ak_promo_photos_zip'] ); ?>"
        placeholder="https://monsite.com/photos-promo-release.zip" class="widefat" />
      <button type="button" class="button ak-upload-btn" data-target="ak_promo_photos_zip"><?php _e( 'Choisir', 'artistkit' ); ?></button>
    </div>
  </div>

  <hr style="border-color:#3a3a4a;margin:8px 0"/>

  <div class="ak-field-group">
    <label>
      <input type="checkbox" name="ak_password_protected" value="1"
        <?php checked( $d['ak_password_protected'], '1' ); ?>
        id="ak_password_protected_cb" />
      <?php _e( 'Protéger cet EPK par un mot de passe', 'artistkit' ); ?>
    </label>
  </div>

  <div class="ak-field-group" id="ak_password_field" <?php echo $d['ak_password_protected'] !== '1' ? 'style="display:none"' : ''; ?>>
    <label><?php _e( 'Mot de passe', 'artistkit' ); ?></label>
    <input type="text" name="ak_password" value="<?php echo esc_attr( $d['ak_password'] ); ?>"
      placeholder="ex: promo2024" class="regular-text" />
    <p class="description"><?php _e( 'Le visiteur devra entrer ce mot de passe pour accéder à l\'EPK.', 'artistkit' ); ?></p>
  </div>
</div>
