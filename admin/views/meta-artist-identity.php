<?php defined( 'ABSPATH' ) || exit; ?>
<div class="ak-meta-grid">
  <div class="ak-field-group">
    <label><?php _e( 'Genre musical', 'artistkit' ); ?></label>
    <input type="text" name="ak_genre" value="<?php echo esc_attr( $d['ak_genre'] ); ?>"
      placeholder="Ex: Electronic · House · Afrobeats" class="widefat" />
  </div>

  <div class="ak-field-group-row">
    <div class="ak-field-group">
      <label><?php _e( 'Ville / Pays', 'artistkit' ); ?></label>
      <input type="text" name="ak_location" value="<?php echo esc_attr( $d['ak_location'] ); ?>"
        placeholder="Paris, France" class="widefat" />
    </div>
    <div class="ak-field-group">
      <label><?php _e( 'Actif depuis', 'artistkit' ); ?></label>
      <input type="text" name="ak_founded_year" value="<?php echo esc_attr( $d['ak_founded_year'] ); ?>"
        placeholder="2019" class="widefat" style="max-width:120px" />
    </div>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Bio courte', 'artistkit' ); ?> <span class="ak-hint"><?php _e( '(2-3 lignes — pour les bookers)', 'artistkit' ); ?></span></label>
    <textarea name="ak_bio_short" rows="3" class="widefat"
      placeholder="Producteur et DJ basé à Paris, X façonne un univers électronique..."><?php echo esc_textarea( $d['ak_bio_short'] ); ?></textarea>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Bio longue', 'artistkit' ); ?> <span class="ak-hint"><?php _e( '(presse & labels)', 'artistkit' ); ?></span></label>
    <textarea name="ak_bio_long" rows="6" class="widefat"
      placeholder="Bio complète pour les dossiers presse..."><?php echo esc_textarea( $d['ak_bio_long'] ); ?></textarea>
  </div>

  <div class="ak-field-group-row">
    <div class="ak-field-group">
      <label><?php _e( 'Monthly listeners Spotify', 'artistkit' ); ?></label>
      <input type="text" name="ak_monthly_listeners" value="<?php echo esc_attr( $d['ak_monthly_listeners'] ); ?>"
        placeholder="48 200" class="widefat" />
      <p class="description"><?php _e( 'À mettre à jour manuellement', 'artistkit' ); ?></p>
    </div>
    <div class="ak-field-group">
      <label><?php _e( 'Total streams', 'artistkit' ); ?></label>
      <input type="text" name="ak_total_streams" value="<?php echo esc_attr( $d['ak_total_streams'] ); ?>"
        placeholder="2,1M" class="widefat" />
    </div>
  </div>
</div>
