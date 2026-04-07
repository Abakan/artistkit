<?php defined( 'ABSPATH' ) || exit; ?>
<div id="ak-press-quotes">
  <p class="description" style="margin-bottom:16px"><?php _e( 'Ajoute les citations presse, blogs et médias qui ont parlé de toi.', 'artistkit' ); ?></p>

  <div id="ak-quotes-list">
    <?php if ( $quotes ) :
      foreach ( $quotes as $i => $q ) : ?>
        <div class="ak-quote-row">
          <div class="ak-quote-fields">
            <textarea name="ak_press_quotes[<?php echo $i; ?>][quote]" rows="2" placeholder="&quot;Un son qui redéfinit les frontières de la house music&quot;" class="widefat"><?php echo esc_textarea( $q['quote'] ); ?></textarea>
            <div class="ak-quote-meta">
              <input type="text" name="ak_press_quotes[<?php echo $i; ?>][source]" value="<?php echo esc_attr( $q['source'] ); ?>" placeholder="Tsugi Magazine" />
              <input type="url" name="ak_press_quotes[<?php echo $i; ?>][url]" value="<?php echo esc_attr( $q['url'] ); ?>" placeholder="https://tsugi.fr/..." />
            </div>
          </div>
          <button type="button" class="ak-remove-quote button">✕</button>
        </div>
      <?php endforeach;
    endif; ?>
  </div>

  <button type="button" id="ak-add-quote" class="button">+ <?php _e( 'Ajouter une citation', 'artistkit' ); ?></button>
</div>

<script type="text/html" id="ak-quote-template">
  <div class="ak-quote-row">
    <div class="ak-quote-fields">
      <textarea name="ak_press_quotes[__INDEX__][quote]" rows="2" placeholder="&quot;...&quot;" class="widefat"></textarea>
      <div class="ak-quote-meta">
        <input type="text" name="ak_press_quotes[__INDEX__][source]" placeholder="Source / Média" />
        <input type="url" name="ak_press_quotes[__INDEX__][url]" placeholder="https://..." />
      </div>
    </div>
    <button type="button" class="ak-remove-quote button">✕</button>
  </div>
</script>
