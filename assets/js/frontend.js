/* ArtistKit Frontend JS */

(function() {
  'use strict';

  var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ── Hero entrance stagger ──────────────────────────────────────────────────
  function initHeroStagger() {
    if (reducedMotion) return;

    var artistTargets = [
      '.ak-hero .ak-genre',
      '.ak-hero .ak-artist-name',
      '.ak-hero .ak-bio-short',
      '.ak-hero .ak-stats',
      '.ak-hero .ak-streaming-links',
      '.ak-hero .ak-hero-socials',
    ];

    var artistImage = document.querySelector('.ak-hero .ak-hero-image');

    var targets = [];
    artistTargets.forEach(function(sel) {
      var el = document.querySelector(sel);
      if (el) targets.push({ el: el, type: 'slide' });
    });

    if (artistImage) targets.unshift({ el: artistImage, type: 'scale' });

    if (!targets.length) return;

    targets.forEach(function(item) {
      item.el.style.opacity    = '0';
      item.el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      if (item.type === 'scale') {
        item.el.style.transform = 'scale(0.94) translateY(10px)';
      } else {
        item.el.style.transform = 'translateY(20px)';
      }
    });

    requestAnimationFrame(function() {
      requestAnimationFrame(function() {
        var baseDelay = 60;
        targets.forEach(function(item, i) {
          var delay = i === 0 && item.type === 'scale' ? 40 : baseDelay + i * 100;
          setTimeout(function() {
            item.el.style.opacity   = '1';
            item.el.style.transform = 'none';
          }, delay);
        });
      });
    });
  }

  // ── Stat counters ──────────────────────────────────────────────────────────
  function initStatCounters() {
    if (reducedMotion) return;
    if (!('IntersectionObserver' in window)) return;

    var statVals = document.querySelectorAll('.ak-stat-val');
    if (!statVals.length) return;

    function easeOutCubic(t) { return 1 - Math.pow(1 - t, 3); }

    function animateCounter(el) {
      var original = el.textContent.trim();
      var m = original.match(/^([^\d]*)([\d\s,.]+)([KMBkmb+%]?)(.*)$/);
      if (!m) return;

      var prefix    = m[1];
      var rawStr    = m[2].replace(/[\s,]/g, '').replace(',', '.');
      var numSuffix = m[3];
      var trailing  = m[4];
      var target    = parseFloat(rawStr);

      if (isNaN(target) || target === 0) return;

      var isFloat   = rawStr.indexOf('.') !== -1 && target < 100;
      var duration  = Math.min(1600, 800 + target * 0.05);
      var startTime = null;

      function fmt(val) {
        if (isFloat) return val.toFixed(1);
        var n = Math.round(val);
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
        else el.textContent = original;
      }

      requestAnimationFrame(step);
    }

    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
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

  // ── Sections fade-in on scroll ─────────────────────────────────────────────
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

  // ── Init ───────────────────────────────────────────────────────────────────
  initHeroStagger();
  initStatCounters();

})();
