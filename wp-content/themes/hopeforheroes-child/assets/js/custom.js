/**
 * Hope for Heroes Texas — Custom Interactions
 * Animated counters, scroll reveal, and micro-interactions
 */

(function () {
  'use strict';

  /* ==========================================================================
     ANIMATED COUNTERS
     ========================================================================== */

  function animateCounter(el) {
    const target = parseInt(el.getAttribute('data-target'), 10);
    if (isNaN(target) || target === 0) return;

    const duration = 2000;
    const startTime = performance.now();
    const suffix = el.textContent.includes('+') ? '+' : '';
    const prefix = el.textContent.match(/^[^0-9]*/)?.[0] || '';

    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);

      // Ease out cubic for smooth deceleration
      const eased = 1 - Math.pow(1 - progress, 3);
      const currentNum = Math.floor(eased * target);

      el.textContent = prefix + currentNum.toLocaleString() + suffix;

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        el.textContent = prefix + target.toLocaleString() + suffix;
      }
    }

    requestAnimationFrame(update);
  }

  /* ==========================================================================
     SCROLL REVEAL (Intersection Observer)
     ========================================================================== */

  function initScrollReveal() {
    const elements = document.querySelectorAll(
      '.hfh-fade-in, .hfh-slide-left, .hfh-slide-right'
    );

    if (!elements.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');

            // Trigger counter animation if this is a counter
            const counter = entry.target.querySelector('.hfh-counter-number');
            if (counter && !counter.dataset.animated) {
              counter.dataset.animated = 'true';
              animateCounter(counter);
            }

            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px',
      }
    );

    elements.forEach((el) => observer.observe(el));
  }

  /* ==========================================================================
     STANDALONE COUNTER OBSERVER
     ========================================================================== */

  function initCounters() {
    const counters = document.querySelectorAll('.hfh-counter-number');
    if (!counters.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && !entry.target.dataset.animated) {
            entry.target.dataset.animated = 'true';
            animateCounter(entry.target);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.5 }
    );

    counters.forEach((counter) => observer.observe(counter));
  }

  /* ==========================================================================
     SMOOTH SCROLL FOR ANCHOR LINKS
     ========================================================================== */

  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener('click', function (e) {
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;

        const targetEl = document.querySelector(targetId);
        if (targetEl) {
          e.preventDefault();
          targetEl.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
          });
        }
      });
    });
  }

  /* ==========================================================================
     NAVBAR SCROLL EFFECT
     ========================================================================== */

  function initNavbarScroll() {
    const header = document.querySelector('.elementor-location-header');
    if (!header) return;

    let lastScroll = 0;

    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;

      if (currentScroll > 100) {
        header.classList.add('hfh-header-scrolled');
      } else {
        header.classList.remove('hfh-header-scrolled');
      }

      lastScroll = currentScroll;
    }, { passive: true });
  }

  /* ==========================================================================
     DONATION CELEBRATION
     ========================================================================== */

  function initDonationCelebration() {
    // Listen for GiveWP successful donation (custom event)
    document.addEventListener('give_donation_complete', function () {
      createConfetti();
    });
  }

  function createConfetti() {
    const colors = ['#F5A623', '#4A9BD9', '#FFD54F', '#E8762D', '#1B2A5B'];
    const container = document.createElement('div');
    container.style.cssText =
      'position:fixed;inset:0;pointer-events:none;z-index:99999;overflow:hidden;';
    document.body.appendChild(container);

    for (let i = 0; i < 80; i++) {
      const confetti = document.createElement('div');
      const color = colors[Math.floor(Math.random() * colors.length)];
      const size = Math.random() * 10 + 5;
      const left = Math.random() * 100;
      const delay = Math.random() * 2;
      const duration = Math.random() * 2 + 2;

      confetti.style.cssText = `
        position: absolute;
        top: -10px;
        left: ${left}%;
        width: ${size}px;
        height: ${size}px;
        background: ${color};
        border-radius: ${Math.random() > 0.5 ? '50%' : '2px'};
        animation: confetti-fall ${duration}s ease-in ${delay}s forwards;
        transform: rotate(${Math.random() * 360}deg);
      `;
      container.appendChild(confetti);
    }

    // Add confetti animation
    if (!document.getElementById('hfh-confetti-style')) {
      const style = document.createElement('style');
      style.id = 'hfh-confetti-style';
      style.textContent = `
        @keyframes confetti-fall {
          0% { transform: translateY(0) rotate(0deg); opacity: 1; }
          100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }
      `;
      document.head.appendChild(style);
    }

    // Clean up after animation
    setTimeout(() => container.remove(), 5000);
  }

  /* ==========================================================================
     INIT
     ========================================================================== */

  document.addEventListener('DOMContentLoaded', function () {
    initScrollReveal();
    initCounters();
    initSmoothScroll();
    initNavbarScroll();
    initDonationCelebration();
  });
})();
