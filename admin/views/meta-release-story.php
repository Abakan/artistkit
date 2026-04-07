<?php defined( 'ABSPATH' ) || exit; ?>
<div class="ak-meta-grid">
  <div class="ak-field-group">
    <label><?php _e( 'Histoire du titre / Angle presse', 'artistkit' ); ?></label>
    <textarea name="ak_release_story" rows="5" class="widefat"
      placeholder="Le contexte, l'inspiration, ce que cette release représente... Ce texte sera lu par les journalistes et curators."><?php echo esc_textarea( $d['ak_release_story'] ); ?></textarea>
    <p class="description"><?php _e( 'Sois spécifique et personnel — c\'est ce qui donne envie d\'écrire un article.', 'artistkit' ); ?></p>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Talking points journalistes', 'artistkit' ); ?> <span class="ak-hint"><?php _e( '(angles d\'interview suggérés)', 'artistkit' ); ?></span></label>
    <textarea name="ak_talking_points" rows="5" class="widefat"
      placeholder="- Comment ce titre s'est formé en 3 semaines de studio à Berlin&#10;- L'influence de la scène clubbing parisienne des années 90&#10;- La collaboration avec le producteur X"><?php echo esc_textarea( $d['ak_talking_points'] ); ?></textarea>
  </div>

  <div class="ak-field-group">
    <label><?php _e( 'Citations presse reçues', 'artistkit' ); ?> <span class="ak-hint"><?php _e( '(à alimenter au fil de la campagne)', 'artistkit' ); ?></span></label>
    <textarea name="ak_release_quotes" rows="4" class="widefat"
      placeholder="&quot;Un retour explosif&quot; — Tsugi&#10;&quot;À écouter absolument&quot; — Traxmag"><?php echo esc_textarea( $d['ak_release_quotes'] ); ?></textarea>
  </div>
</div>
