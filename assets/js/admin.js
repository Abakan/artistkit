/* ArtistKit Admin JS */
(function($) {
  'use strict';

  $(document).ready(function() {

    // ── Color picker sync ──────────────────────────────────────────────────
    $('#accent_color').on('input change', function() {
      $('#accent_color_text').val($(this).val());
    });
    $('#accent_color_text').on('input', function() {
      var val = $(this).val();
      if (/^#[0-9a-fA-F]{6}$/.test(val)) {
        $('#accent_color').val(val);
      }
    });

    // ── Template selection ─────────────────────────────────────────────────
    $('.ak-template-option input[type="radio"]').on('change', function() {
      $('.ak-template-option').removeClass('ak-selected');
      $(this).closest('.ak-template-option').addClass('ak-selected');
    });

    // ── Media uploader ─────────────────────────────────────────────────────
    // On partage un seul frame wp.media mais on met à jour currentTarget
    // à chaque clic pour que l'image atterrisse toujours dans le bon champ.
    var mediaFrame;
    var currentTarget;

    $(document).on('click', '.ak-upload-btn', function(e) {
      e.preventDefault();
      currentTarget = $(this).data('target');

      if ( ! mediaFrame ) {
        mediaFrame = wp.media({
          title: AK.strings.selectImage,
          button: { text: AK.strings.useImage },
          multiple: false,
        });

        mediaFrame.on('select', function() {
          var attachment = mediaFrame.state().get('selection').first().toJSON();
          $('#' + currentTarget).val(attachment.url);
        });
      }

      mediaFrame.open();
    });

    // ── Press quotes dynamic rows ──────────────────────────────────────────
    var quoteIndex = $('.ak-quote-row').length;

    $('#ak-add-quote').on('click', function() {
      var template = $('#ak-quote-template').html();
      template = template.replace(/__INDEX__/g, quoteIndex);
      $('#ak-quotes-list').append(template);
      quoteIndex++;
    });

    $(document).on('click', '.ak-remove-quote', function() {
      $(this).closest('.ak-quote-row').remove();
    });

    // ── Password field toggle ──────────────────────────────────────────────
    $('#ak_password_protected_cb').on('change', function() {
      if ($(this).is(':checked')) {
        $('#ak_password_field').show();
      } else {
        $('#ak_password_field').hide();
      }
    });

    // ── Logo uploader ──────────────────────────────────────────────────────
    var logoFrame;
    $('#ak-logo-upload-btn').on('click', function(e) {
      e.preventDefault();
      if (logoFrame) { logoFrame.open(); return; }
      logoFrame = wp.media({
        title: 'Choisir ton logo',
        button: { text: 'Utiliser ce logo' },
        multiple: false,
        library: { type: 'image' },
      });
      logoFrame.on('select', function() {
        var attachment = logoFrame.state().get('selection').first().toJSON();
        $('#ak-logo-url').val(attachment.url);
        $('#ak-logo-img, #ak-logo-preview img').attr('src', attachment.url);
        $('#ak-logo-preview').show();
        $('#ak-logo-upload-btn').text('🔄 Changer le logo');
      });
      logoFrame.open();
    });
    $('#ak-logo-remove').on('click', function() {
      $('#ak-logo-url').val('');
      $('#ak-logo-preview').hide();
      $('#ak-logo-upload-btn').text('⬆ Uploader mon logo');
    });

    // ── EPK link: copy to clipboard ────────────────────────────────────────
    $(document).on('click', '.ak-copy-link', function(e) {
      e.preventDefault();
      var link = $(this).data('url');
      navigator.clipboard.writeText(link).then(function() {
        var $btn = $(this);
        $btn.text('Copié !');
        setTimeout(function() { $btn.text('Copier'); }, 2000);
      }.bind(this));
    });

  });

})(jQuery);
