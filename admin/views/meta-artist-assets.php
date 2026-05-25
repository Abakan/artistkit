<?php defined( 'ABSPATH' ) || exit; ?>
<div class="ak-meta-grid">
  <div class="ak-field-group">
    <label><?php esc_html_e( 'Booking email', 'artistkit' ); ?></label>
    <input type="email" name="ak_contact_booking" value="<?php echo esc_attr( $d['ak_contact_booking'] ); ?>"
      placeholder="booking@youragency.com" class="widefat" />
  </div>
  <div class="ak-field-group">
    <label><?php esc_html_e( 'Management email', 'artistkit' ); ?></label>
    <input type="email" name="ak_contact_management" value="<?php echo esc_attr( $d['ak_contact_management'] ); ?>"
      placeholder="management@..." class="widefat" />
  </div>
  <div class="ak-field-group">
    <label><?php esc_html_e( 'Press / PR email', 'artistkit' ); ?></label>
    <input type="email" name="ak_contact_press" value="<?php echo esc_attr( $d['ak_contact_press'] ); ?>"
      placeholder="press@..." class="widefat" />
  </div>

  <hr style="border-color:#3a3a4a;margin:8px 0"/>

  <div class="ak-field-group">
    <label><?php esc_html_e( 'Technical rider (file URL)', 'artistkit' ); ?></label>
    <div class="ak-media-input">
      <input type="url" name="ak_rider_url" id="ak_rider_url"
        value="<?php echo esc_attr( $d['ak_rider_url'] ); ?>"
        placeholder="https://yoursite.com/rider.pdf" class="widefat" />
      <button type="button" class="button ak-upload-btn" data-target="ak_rider_url"><?php esc_html_e( 'Choose', 'artistkit' ); ?></button>
    </div>
  </div>

  <div class="ak-field-group">
    <label><?php esc_html_e( 'Press photos ZIP (URL)', 'artistkit' ); ?></label>
    <div class="ak-media-input">
      <input type="url" name="ak_photos_zip_url" id="ak_photos_zip_url"
        value="<?php echo esc_attr( $d['ak_photos_zip_url'] ); ?>"
        placeholder="https://yoursite.com/press-photos.zip" class="widefat" />
      <button type="button" class="button ak-upload-btn" data-target="ak_photos_zip_url"><?php esc_html_e( 'Choose', 'artistkit' ); ?></button>
    </div>
    <p class="description"><?php esc_html_e( 'Bundle your HD photos in a ZIP uploaded via the WordPress media library.', 'artistkit' ); ?></p>
  </div>
</div>
