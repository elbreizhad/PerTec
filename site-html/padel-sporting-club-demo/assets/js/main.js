/* Padel Sporting Club Évreux — site de démonstration
   Menu mobile, apparitions au scroll, formulaires de démo, parties ouvertes. */
(function () {
  'use strict';

  /* Menu mobile */
  var burger = document.querySelector('.burger');
  var nav = document.getElementById('nav');
  if (burger && nav) {
    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    nav.addEventListener('click', function (e) {
      if (e.target.tagName === 'A') { nav.classList.remove('is-open'); burger.setAttribute('aria-expanded', 'false'); }
    });
  }

  /* Apparitions au scroll */
  var items = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
      });
    }, { rootMargin: '0px 0px -60px 0px' });
    items.forEach(function (el) { io.observe(el); });
  } else {
    items.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* Toast */
  window.pscToast = function (html) {
    var t = document.querySelector('.toast');
    if (!t) { t = document.createElement('div'); t.className = 'toast'; t.setAttribute('role', 'status'); document.body.appendChild(t); }
    t.innerHTML = html;
    t.classList.add('is-on');
    clearTimeout(t._h);
    t._h = setTimeout(function () { t.classList.remove('is-on'); }, 3600);
  };

  /* Formulaires de démonstration (aucun envoi réel) */
  document.querySelectorAll('form[data-demo]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      e.preventDefault();
      f.classList.add('is-sent');
      var ok = f.querySelector('.form__ok');
      if (ok) ok.focus();
    });
  });

  /* Rejoindre une partie ouverte */
  document.querySelectorAll('[data-join]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var card = btn.closest('.match');
      if (!card || card.classList.contains('is-joined')) return;
      card.classList.add('is-joined');
      var empty = card.querySelector('.avatar--empty');
      if (empty) { empty.classList.remove('avatar--empty'); empty.textContent = 'VS'; empty.style.background = '#FAE789'; empty.style.color = '#171D36'; }
      btn.textContent = 'Vous êtes inscrit';
      pscToast('<b>Place réservée.</b> Les autres joueurs sont prévenus.');
    });
  });

  /* Filtres de la page tournois */
  var tabs = document.querySelectorAll('[data-filter]');
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      var v = tab.getAttribute('data-filter');
      tabs.forEach(function (t) { t.setAttribute('aria-pressed', t === tab ? 'true' : 'false'); });
      document.querySelectorAll('[data-cat]').forEach(function (ev) {
        ev.hidden = !(v === 'all' || ev.getAttribute('data-cat') === v);
      });
    });
  });

  /* Année du pied de page */
  var y = document.querySelector('[data-year]');
  if (y) y.textContent = new Date().getFullYear();
})();
