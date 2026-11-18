/**
 * Suivi des parcours - Pertec.fr
 * Envoie au site (et à personne d'autre) : chargement de la page, clics sur les
 * liens et boutons, défilement maximal et temps passé. Aucun cookie.
 */
(function () {
  var script = document.currentScript;
  var pv = script && script.getAttribute('data-pv');
  if (!pv || navigator.doNotTrack === '1' || navigator.globalPrivacyControl) return;

  var url = '/suivi.php';
  function envoyer(e) {
    e.p = pv;
    var corps = JSON.stringify(e);
    if (!(navigator.sendBeacon && navigator.sendBeacon(url, corps))) {
      fetch(url, { method: 'POST', body: corps, keepalive: true }).catch(function () {});
    }
  }

  envoyer({ k: 'js', w: window.innerWidth, wd: navigator.webdriver ? 1 : 0 });

  // Clics sur les liens, boutons et choix de formulaire
  var dernierClic = 0;
  document.addEventListener('click', function (ev) {
    var el = ev.target.closest && ev.target.closest('a, button, label, summary, [data-track], input[type=radio], input[type=checkbox]');
    if (!el || Date.now() - dernierClic < 300) return;
    dernierClic = Date.now();

    var texte = el.getAttribute('data-track') || el.getAttribute('aria-label') || el.innerText || el.value || el.title || '';
    if (!texte && el.querySelector('img')) texte = el.querySelector('img').alt || 'image';
    var href = '';
    if (el.tagName === 'A') {
      var lien = el.getAttribute('href') || '';
      if (/^(tel|mailto):/i.test(lien)) {
        href = lien.split(':')[0].toLowerCase() + ':';
      } else if (el.host && el.host !== location.host) {
        href = el.host;
      } else {
        href = el.pathname + el.hash;
      }
    } else if (el.form && el.type === 'submit') {
      href = 'formulaire';
    }
    envoyer({ k: 'clic', x: texte.replace(/\s+/g, ' ').trim().slice(0, 80), h: href });
  }, true);

  // Début de saisie dans un formulaire (le contenu n'est jamais envoyé)
  var formsCommences = [];
  document.addEventListener('focusin', function (ev) {
    var champ = ev.target;
    if (!champ.form || !/^(INPUT|TEXTAREA|SELECT)$/.test(champ.tagName) || champ.type === 'hidden') return;
    var nom = champ.form.id || champ.form.getAttribute('action') || 'formulaire';
    if (formsCommences.indexOf(nom) !== -1) return;
    formsCommences.push(nom);
    envoyer({ k: 'saisie', x: nom.slice(0, 60) });
  });

  // Défilement maximal et temps passé page visible
  var maxScroll = 0;
  var actif = 0;
  var depuis = document.visibilityState === 'visible' ? Date.now() : 0;
  function mesurer() {
    var h = document.documentElement.scrollHeight - window.innerHeight;
    var pct = h > 0 ? Math.round(window.scrollY / h * 100) : 100;
    if (pct > maxScroll) maxScroll = Math.min(100, pct);
  }
  window.addEventListener('scroll', mesurer, { passive: true });
  mesurer();

  var envoye = false;
  function terminer() {
    if (depuis) { actif += Date.now() - depuis; depuis = 0; }
    if (envoye) return;
    envoye = true;
    // Temps écoulé depuis le dernier envoi : les envois s'additionnent côté admin
    envoyer({ k: 'fin', sc: maxScroll, du: Math.round(actif / 1000) });
    actif = 0;
  }
  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'hidden') {
      terminer();
    } else {
      depuis = Date.now();
      envoye = false;
    }
  });
  window.addEventListener('pagehide', terminer);
})();
