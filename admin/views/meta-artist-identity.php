<?php defined( 'ABSPATH' ) || exit; ?>
<div class="ak-meta-grid">
  <div class="ak-field-group">
    <label><?php esc_html_e( 'Music genre', 'artistkit' ); ?></label>
    <input type="text" name="ak_genre" value="<?php echo esc_attr( $d['ak_genre'] ); ?>"
      placeholder="<?php esc_attr_e( 'Ex: Electronic · House · Afrobeats', 'artistkit' ); ?>" class="widefat" />
  </div>

  <div class="ak-field-group-row">
    <div class="ak-field-group">
      <label><?php esc_html_e( 'City / Country', 'artistkit' ); ?></label>
      <input type="text" name="ak_location" value="<?php echo esc_attr( $d['ak_location'] ); ?>"
        placeholder="Paris, France" class="widefat" />
    </div>
    <div class="ak-field-group">
      <label><?php esc_html_e( 'Active since', 'artistkit' ); ?></label>
      <input type="text" name="ak_founded_year" value="<?php echo esc_attr( $d['ak_founded_year'] ); ?>"
        placeholder="2019" class="widefat" style="max-width:120px" />
    </div>
  </div>

  <div class="ak-field-group">
    <label><?php esc_html_e( 'Short bio', 'artistkit' ); ?> <span class="ak-hint"><?php esc_html_e( '(2-3 lines — for bookers)', 'artistkit' ); ?></span></label>
    <textarea name="ak_bio_short" rows="3" class="widefat"
      placeholder="<?php esc_attr_e( 'Paris-based producer and DJ, X crafts an electronic universe...', 'artistkit' ); ?>"><?php echo esc_textarea( $d['ak_bio_short'] ); ?></textarea>
  </div>

  <div class="ak-field-group">
    <label><?php esc_html_e( 'Long bio', 'artistkit' ); ?> <span class="ak-hint"><?php esc_html_e( '(press & labels)', 'artistkit' ); ?></span></label>
    <textarea name="ak_bio_long" rows="6" class="widefat"
      placeholder="<?php esc_attr_e( 'Full bio for press kits...', 'artistkit' ); ?>"><?php echo esc_textarea( $d['ak_bio_long'] ); ?></textarea>
  </div>

  <div class="ak-field-group-row">
    <div class="ak-field-group">
      <label><?php esc_html_e( 'Monthly Spotify listeners', 'artistkit' ); ?></label>
      <input type="text" name="ak_monthly_listeners" value="<?php echo esc_attr( $d['ak_monthly_listeners'] ); ?>"
        placeholder="48 200" class="widefat" />
      <p class="description"><?php esc_html_e( 'Update manually', 'artistkit' ); ?></p>
    </div>
    <div class="ak-field-group">
      <label><?php esc_html_e( 'Total streams', 'artistkit' ); ?></label>
      <input type="text" name="ak_total_streams" value="<?php echo esc_attr( $d['ak_total_streams'] ); ?>"
        placeholder="2.1M" class="widefat" />
    </div>
  </div>
</div>
