/* ArtistKit Frontend JS */

/**
 * PDF Print — déclenche l'impression du navigateur avec un titre de fichier propre.
 */
function akPrintPDF(btn) {
  if ( btn ) {
    btn.classList.add('ak-loading');
    btn.querySelector('.ak-btn-pdf-label') && (btn.querySelector('.ak-btn-pdf-label').textContent = 'Préparation…');
  }

  var prevTitle = document.title;
  document.title = prevTitle.replace(' — Press Kit', '') + ' — EPK';

  setTimeout(function() {
    window.print();
    setTimeout(function() {
      document.title = prevTitle;
      if ( btn ) {
        btn.classList.remove('ak-loading');
        var label = btn.querySelector('.ak-btn-pdf-label');
        if (label) label.textContent = 'Télécharger PDF';
      }
    }, 1000);
  }, 300);
}

(function() {
  'use strict';

  var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ── Hero entrance stagger ──────────────────────────────────────────────────
  function initHeroStagger() {
    if (reducedMotion) return;

    // Éléments hero artiste (ordre DOM = ordre d'apparition)
    var artistTargets = [
      '.ak-hero .ak-genre',
      '.ak-hero .ak-artist-name',
      '.ak-hero .ak-bio-short',
      '.ak-hero .ak-stats',
      '.ak-hero .ak-streaming-links',
      '.ak-hero .ak-hero-socials',
    ];

    // Image artiste : entrée parallèle mais avec scale
    var artistImage = document.querySelector('.ak-hero .ak-hero-image');

    // Éléments hero release
    var releaseTargets = [
      '.ak-release-hero .ak-release-meta-top',
      '.ak-release-hero .ak-release-title',
      '.ak-release-hero .ak-release-info-grid',
      '.ak-release-hero .ak-streaming-links',
      '.ak-release-hero .ak-radio-chips',
    ];
    var releaseArtwork = document.querySelector('.ak-release-hero .ak-release-artwork-wrap');

    // Collecte les éléments présents dans le DOM
    var targets = [];
    var isRelease = !!document.querySelector('.ak-release-hero');

    (isRelease ? releaseTargets : artistTargets).forEach(function(sel) {
      var el = document.querySelector(sel);
      if (el) targets.push({ el: el, type: 'slide' });
    });

    // Image / artwork : fade + scale depuis légèrement en dessous
    var imageEl = isRelease ? releaseArtwork : artistImage;
    if (imageEl) targets.unshift({ el: imageEl, type: 'scale' });

    if (!targets.length) return;

    // Pose les styles initiaux immédiatement
    targets.forEach(function(item) {
      item.el.style.opacity    = '0';
      item.el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      if (item.type === 'scale') {
        item.el.style.transform = 'scale(0.94) translateY(10px)';
      } else {
        item.el.style.transform = 'translateY(20px)';
      }
    });

    // Décale le lancement après le premier paint
    requestAnimationFrame(function() {
      requestAnimationFrame(function() {
        var baseDelay = isRelease ? 80 : 60; // release : image d'abord
        targets.forEach(function(item, i) {
          var delay = i === 0 && item.type === 'scale'
            ? 40                              // image/artwork : très tôt
            : baseDelay + (isRelease ? i : i) * 100;

          setTimeout(function() {
            item.el.style.opacity   = '1';
            item.el.style.transform = 'none';
          }, delay);
        });
      });
    });
  }

  // ── Stat counters animés ───────────────────────────────────────────────────
  function initStatCounters() {
    if (reducedMotion) return;
    if (!('IntersectionObserver' in window)) return;

    var statVals = document.querySelectorAll('.ak-stat-val');
    if (!statVals.length) return;

    function easeOutCubic(t) { return 1 - Math.pow(1 - t, 3); }

    function animateCounter(el) {
      var original = el.textContent.trim();
      // Match: optionnal prefix, digits/commas/dots, optional suffix (K M B +) and trailing
      var m = original.match(/^([^\d]*)([\d\s,.]+)([KMBkmb+%]?)(.*)$/);
      if (!m) return;

      var prefix  = m[1];
      var rawStr  = m[2].replace(/[\s,]/g, '').replace(',', '.');
      var numSuffix = m[3];
      var trailing  = m[4];
      var target  = parseFloat(rawStr);

      if (isNaN(target) || target === 0) return;

      var isFloat   = rawStr.indexOf('.') !== -1 && target < 100;
      var duration  = Math.min(1600, 800 + target * 0.05);
      var startTime = null;

      function fmt(val) {
        if (isFloat) return val.toFixed(1);
        var n = Math.round(val);
        // Keep original thousands formatting style
        if (original.indexOf(' ') !== -1) return n.toLocaleString('fr-FR');
        if (original.indexOf(',') !== -1) return n.toLocaleString('en-US');
        return n.toString();
      }

      function step(ts) {
        if (!startTime) startTime = ts;
        var progress = Math.min((ts - startTime) / duration, 1);
        var eased    = easeOutCubic(progress);
        el.textContent = prefix + fmt(target * eased) + numSuffix + trailing;
        if (progress < 1) requestAnimationFrame(step);
        else el.textContent = original; // restore exactly
      }

      requestAnimationFrame(step);
    }

    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          // Petit délai pour laisser le stagger hero se terminer
          setTimeout(function() { animateCounter(entry.target); }, 200);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    statVals.forEach(function(el) { observer.observe(el); });
  }

  // ── Bio toggle ─────────────────────────────────────────────────────────────
  var bioToggle = document.getElementById('ak-bio-toggle');
  var bioLong   = document.querySelector('.ak-bio-long');

  if (bioToggle && bioLong) {
    var fullHeight  = bioLong.scrollHeight;
    var shortHeight = parseInt(getComputedStyle(bioLong).lineHeight) * 3;

    if (fullHeight > shortHeight + 20) {
      bioLong.style.maxHeight  = shortHeight + 'px';
      bioLong.style.overflow   = 'hidden';
      bioLong.style.transition = 'max-height 0.4s ease';
      bioToggle.style.display  = 'inline-flex';

      bioToggle.addEventListener('click', function() {
        var isCollapsed = bioLong.style.maxHeight !== 'none' && bioLong.style.maxHeight !== '';
        if (isCollapsed) {
          bioLong.style.maxHeight = fullHeight + 'px';
          setTimeout(function() { bioLong.style.maxHeight = 'none'; }, 400);
          bioToggle.querySelector('.ak-bio-toggle-more').style.display = 'none';
          bioToggle.querySelector('.ak-bio-toggle-less').style.display = 'inline';
        } else {
          bioLong.style.maxHeight = fullHeight + 'px';
          setTimeout(function() { bioLong.style.maxHeight = shortHeight + 'px'; }, 10);
          bioToggle.querySelector('.ak-bio-toggle-more').style.display = 'inline';
          bioToggle.querySelector('.ak-bio-toggle-less').style.display = 'none';
        }
      });
    } else {
      bioToggle.style.display = 'none';
    }
  }

  // ── Smooth scroll ──────────────────────────────────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
      var target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // ── Patchwork players ──────────────────────────────────────────────────────
  var patchCards   = document.querySelectorAll('.ak-patch-card');
  var currentAudio = null;
  var currentCard  = null;

  patchCards.forEach(function(card) {
    var audio     = card.querySelector('.ak-patch-audio');
    var playBtn   = card.querySelector('.ak-patch-play');
    var playIcon  = card.querySelector('.ak-patch-play-icon');
    var pauseIcon = card.querySelector('.ak-patch-pause-icon');

    if (!audio || !playBtn) return;

    function setPlaying(playing) {
      card.classList.toggle('is-playing', playing);
      playIcon.style.display  = playing ? 'none' : '';
      pauseIcon.style.display = playing ? ''     : 'none';
    }

    playBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      if (currentAudio && currentAudio !== audio) {
        currentAudio.pause();
        currentAudio.currentTime = 0;
        if (currentCard) {
          var pp = currentCard.querySelector('.ak-patch-play-icon');
          var pa = currentCard.querySelector('.ak-patch-pause-icon');
          currentCard.classList.remove('is-playing');
          if (pp) pp.style.display = '';
          if (pa) pa.style.display = 'none';
        }
      }
      if (audio.paused) {
        audio.play();
        currentAudio = audio;
        currentCard  = card;
        setPlaying(true);
      } else {
        audio.pause();
        setPlaying(false);
        currentAudio = null;
        currentCard  = null;
      }
    });

    audio.addEventListener('ended', function() {
      setPlaying(false);
      currentAudio = null;
      currentCard  = null;
    });
  });

  // ── Sections fade-in au scroll ─────────────────────────────────────────────
  if ('IntersectionObserver' in window && !reducedMotion) {
    var sections = document.querySelectorAll('.ak-section');
    var style    = document.createElement('style');
    style.textContent = '.ak-section { opacity: 0; transform: translateY(20px); transition: opacity 0.55s ease, transform 0.55s ease; } .ak-section.ak-visible { opacity: 1; transform: none; }';
    document.head.appendChild(style);

    var sectionObserver = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('ak-visible');
          sectionObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.08 });

    sections.forEach(function(s) { sectionObserver.observe(s); });
  }

  // ── Download tracking ──────────────────────────────────────────────────────
  document.querySelectorAll('.ak-track-download').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var epkId     = btn.getAttribute('data-epk-id');
      var epkType   = btn.getAttribute('data-epk-type') || 'artist';
      var eventType = btn.getAttribute('data-event')    || 'download_mp3';
      var nonce     = btn.getAttribute('data-nonce');

      if (!epkId || !nonce || !window.ajaxurl) return;

      var fd = new FormData();
      fd.append('action',     'ak_log_event');
      fd.append('nonce',      nonce);
      fd.append('epk_id',     epkId);
      fd.append('epk_type',   epkType);
      fd.append('event_type', eventType);

      // Fire-and-forget : on ne bloque pas le téléchargement
      navigator.sendBeacon
        ? navigator.sendBeacon(window.ajaxurl, fd)
        : fetch(window.ajaxurl, { method: 'POST', body: fd, keepalive: true });
    });
  });

  // ── Init ───────────────────────────────────────────────────────────────────
  initHeroStagger();
  initStatCounters();

})();
