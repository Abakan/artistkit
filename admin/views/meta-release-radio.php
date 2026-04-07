<?php defined( 'ABSPATH' ) || exit; ?>
<div class="ak-meta-grid">
  <div class="ak-field-group">
    <label><?php _e( 'BPM', 'artistkit' ); ?></label>
    <input type="number" name="ak_bpm" value="<?php echo esc_attr( $d['ak_bpm'] ); ?>"
      placeholder="128" min="40" max="300" class="small-text" />
  </div>
  <div class="ak-field-group">
    <label><?php _e( 'Tonalité', 'artistkit' ); ?></label>
    <select name="ak_key">
      <option value=""><?php _e( 'Sélectionner', 'artistkit' ); ?></option>
      <?php foreach ( ['C', 'C#/Db', 'D', 'D#/Eb', 'E', 'F', 'F#/Gb', 'G', 'G#/Ab', 'A', 'A#/Bb', 'B',
                       'Cm', 'C#m/Dbm', 'Dm', 'D#m/Ebm', 'Em', 'Fm', 'F#m/Gbm', 'Gm', 'G#m/Abm', 'Am', 'A#m/Bbm', 'Bm'] as $k ) : ?>
        <option value="<?php echo $k; ?>" <?php selected( $d['ak_key'], $k ); ?>><?php echo $k; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="ak-field-group">
    <label><?php _e( 'Durée', 'artistkit' ); ?></label>
    <input type="text" name="ak_duration" value="<?php echo esc_attr( $d['ak_duration'] ); ?>"
      placeholder="3:42" class="small-text" />
  </div>
  <div class="ak-field-group">
    <label><?php _e( 'ISRC', 'artistkit' ); ?></label>
    <input type="text" name="ak_isrc" value="<?php echo esc_attr( $d['ak_isrc'] ); ?>"
      placeholder="FR-Z03-21-12345" class="widefat" />
  </div>
  <div class="ak-field-group">
    <label>
      <input type="checkbox" name="ak_has_radio_edit" value="1"
        <?php checked( $d['ak_has_radio_edit'], '1' ); ?> />
      <?php _e( 'Radio edit disponible', 'artistkit' ); ?>
    </label>
  </div>
</div>
