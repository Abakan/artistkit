<?php defined( 'ABSPATH' ) || exit; ?>
<div id="ak-press-quotes">
  <p class="description" style="margin-bottom:16px"><?php esc_html_e( 'Add the press quotes, blogs and media that have covered you.', 'artistkit' ); ?></p>

  <div id="ak-quotes-list">
    <?php if ( $quotes ) :
      foreach ( $quotes as $ak_i => $ak_q ) : ?>
        <div class="ak-quote-row">
          <div class="ak-quote-fields">
            <textarea name="ak_press_quotes[<?php echo (int) $ak_i; ?>][quote]" rows="2" placeholder="&quot;A sound that redefines the boundaries of house music&quot;" class="widefat"><?php echo esc_textarea( $ak_q['quote'] ); ?></textarea>
            <div class="ak-quote-meta">
              <input type="text" name="ak_press_quotes[<?php echo (int) $ak_i; ?>][source]" value="<?php echo esc_attr( $ak_q['source'] ); ?>" placeholder="<?php esc_attr_e( 'Source / Media', 'artistkit' ); ?>" />
              <input type="url" name="ak_press_quotes[<?php echo (int) $ak_i; ?>][url]" value="<?php echo esc_attr( $ak_q['url'] ); ?>" placeholder="https://..." />
            </div>
          </div>
          <button type="button" class="ak-remove-quote button">✕</button>
        </div>
      <?php endforeach;
    endif; ?>
  </div>

  <button type="button" id="ak-add-quote" class="button">+ <?php esc_html_e( 'Add a quote', 'artistkit' ); ?></button>
</div>

<script type="text/html" id="ak-quote-template">
  <div class="ak-quote-row">
    <div class="ak-quote-fields">
      <textarea name="ak_press_quotes[__INDEX__][quote]" rows="2" placeholder="&quot;...&quot;" class="widefat"></textarea>
      <div class="ak-quote-meta">
        <input type="text" name="ak_press_quotes[__INDEX__][source]" placeholder="<?php esc_attr_e( 'Source / Media', 'artistkit' ); ?>" />
        <input type="url" name="ak_press_quotes[__INDEX__][url]" placeholder="https://..." />
      </div>
    </div>
    <button type="button" class="ak-remove-quote button">✕</button>
  </div>
</script>
