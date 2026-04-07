<?php defined( 'ABSPATH' ) || exit; ?>
<div class="ak-meta-grid">
  <div class="ak-field-group">
    <label><?php _e( 'Email booking', 'artistkit' ); ?></label>
    <input type="email" name="ak_contact_booking" value="<?php echo esc_attr( $d['ak_contact_booking'] ); ?>"
      placeholder="booking@monagence.com" class="widefat" />
  </div>
  <div class="ak-field-group">
    <label><?php _e( 'Email management', 'artistkit' ); ?></label>
    <input type="email" name="ak_contact_management" value="<?php echo esc_attr( $d['ak_contact_management'] ); ?>"
      placeholder="management@..." class="widefat" />
  </div>
  <div class="ak-field-group">
    <label><?php _e( 'Email presse / RP', 'artistkit' ); ?></label>
    <input type="email" name="ak_contact_press" value="<?php echo esc_attr( $d['ak_contact_press'] ); ?>"
      placeholder="presse@..." class="widefat" />
  </div>

  <hr style="border-color:#3a3a4a;margin:8px 0"/>

  <div class="ak-field-group">
    <label><?php _e( 'Rider technique (URL du fichier)', 'artistkit' ); ?></label>
    <div class="ak-media-input">
      <input type="url" name="ak_rider_url" id="ak_rider_url"
        value="<?php echo esc_attr( $d['ak_rider_url'] ); ?>"
        placeholder="https://monsite.com/rider.pdf" class="widefat" />
      <button type="button" class="button ak-upload-btn" data-target="ak_rider_url"><?php _e( 'Choisir', 'artistkit' ); ?></button>
    </div>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Photos presse ZIP (URL)', 'artistkit' ); ?></label>
    <div class="ak-media-input">
      <input type="url" name="ak_photos_zip_url" id="ak_photos_zip_url"
        value="<?php echo esc_attr( $d['ak_photos_zip_url'] ); ?>"
        placeholder="https://monsite.com/photos-presse.zip" class="widefat" />
      <button type="button" class="button ak-upload-btn" data-target="ak_photos_zip_url"><?php _e( 'Choisir', 'artistkit' ); ?></button>
    </div>
    <p class="description"><?php _e( 'Regroupe tes photos HD dans un ZIP uploadé via la médiathèque WordPress.', 'artistkit' ); ?></p>
  </div>
</div>
