/* Padel Sporting Club Évreux — module de réservation (démo)
   Vue grille + vue plan du club (isométrique), récapitulatif, paiement simulé.
   Les disponibilités sont générées pour la démonstration. */
(function () {
  'use strict';
  var root = document.getElementById('booking');
  if (!root) return;

  var SPORTS = {
    padel: { label: 'Padel', durs: [60, 90, 120], prices: { 60: 9, 90: 13, 120: 16 }, unit: 'joueur', players: 4 },
    single: { label: 'Padel single', durs: [90], prices: { 90: 16 }, unit: 'joueur', players: 2 },
    badminton: { label: 'Badminton', durs: [60, 90], prices: { 60: 16, 90: 24 }, unit: 'terrain', players: 4 }
  };
  // Pistes : position sur le plan en mètres (x, y, largeur, profondeur)
  var COURTS = [];
  for (var i = 0; i < 5; i++) COURTS.push({ id: 'p' + (i + 1), name: 'Piste ' + (i + 1), sport: 'padel', x: i * 12, y: 0, w: 10, d: 20 });
  for (var j = 0; j < 5; j++) COURTS.push({ id: 'p' + (j + 6), name: 'Piste ' + (j + 6), sport: 'padel', x: j * 12, y: 26, w: 10, d: 20 });
  COURTS.push({ id: 's1', name: 'Piste single', sport: 'single', x: 62, y: 0, w: 6, d: 20 });
  COURTS.push({ id: 'b1', name: 'Badminton 1', sport: 'badminton', x: 62, y: 26, w: 6.1, d: 13.4 });
  COURTS.push({ id: 'b2', name: 'Badminton 2', sport: 'badminton', x: 70, y: 26, w: 6.1, d: 13.4 });
  COURTS[2].tag = 'Piste centrale';
  COURTS[0].tag = '[Partenaire]';

  var PERIODS = { matin: { label: 'Matin', start: 540, end: 750 }, aprem: { label: 'Après-midi', start: 780, end: 990 }, soir: { label: 'Soir', start: 1020, end: 1350 } };
  var JOURS = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
  var JOURS_L = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
  var MOIS = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
  var MOIS_L = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

  var today = new Date(); today.setHours(0, 0, 0, 0);
  var DAYS = [];
  for (var k = 0; k < 14; k++) {
    var dt = new Date(today); dt.setDate(today.getDate() + k);
    DAYS.push({ date: dt, short: k === 0 ? 'Auj.' : JOURS[dt.getDay()], n: dt.getDate(), m: MOIS[dt.getMonth()], full: JOURS_L[dt.getDay()] + ' ' + dt.getDate() + ' ' + MOIS_L[dt.getMonth()], we: dt.getDay() === 0 || dt.getDay() === 6 });
  }

  var hourNow = new Date().getHours();
  var S = {
    sport: 'padel', day: 0, dur: 90,
    period: hourNow < 12 ? 'matin' : hourNow < 17 ? 'aprem' : 'soir',
    view: 'grid', time: null, sel: null,
    split: true, open: false, pay: 'credits', friends: 0, done: false
  };

  function fmt(m) { return Math.floor(m / 60) + 'h' + (m % 60 ? '30' : '00'); }
  function fmtDur(d) { return d === 60 ? '1 h' : d === 90 ? '1 h 30' : '2 h'; }
  function euro(v) { return (Number.isInteger(v) ? v : v.toFixed(2).replace('.', ',')) + ' €'; }
  function esc(s) { return String(s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }

  function courtsOf(sport) { return COURTS.filter(function (c) { return c.sport === sport; }); }
  function times() { var p = PERIODS[S.period], a = []; for (var t = p.start; t <= p.end; t += 30) a.push(t); return a; }
  function closeAt() { return DAYS[S.day].we ? 1290 : 1410; }
  function nowMin() { var n = new Date(); return n.getHours() * 60 + n.getMinutes(); }

  // Statut d'un créneau : free | taken | open | closed
  function status(ci, t) {
    if (t + S.dur > closeAt()) return 'closed';
    if (S.day === 0 && t < nowMin()) return 'closed';
    var h = ((ci + 1) * 37 + (t / 30) * 11 + S.day * 23 + S.dur) % 13;
    var busy = S.period === 'soir' ? 8 : 4;
    if (h === 0 && SPORTS[S.sport].unit === 'joueur') return 'open';
    return h < busy ? 'taken' : 'free';
  }

  function chip(label, pressed, attrs, cls) {
    return '<button type="button" class="' + (cls || 'chip') + '" aria-pressed="' + pressed + '" ' + attrs + '>' + label + '</button>';
  }

  /* ---------- Filtres ---------- */
  function renderFilters() {
    var sp = SPORTS[S.sport];
    var h = '<div class="filter" role="group" aria-label="Sport">';
    Object.keys(SPORTS).forEach(function (k) { h += chip(SPORTS[k].label, S.sport === k, 'data-sport="' + k + '"'); });
    h += '</div><div class="days" role="group" aria-label="Jour">';
    DAYS.forEach(function (d, i) {
      h += '<button type="button" class="day" aria-pressed="' + (S.day === i) + '" data-day="' + i + '" aria-label="' + d.full + '"><small>' + d.short + '</small><b>' + d.n + '</b><span>' + d.m + '</span></button>';
    });
    h += '</div><div class="filters"><div class="filter" role="group" aria-label="Durée"><span class="filter__label">Durée</span>';
    sp.durs.forEach(function (d) { h += chip(fmtDur(d), S.dur === d, 'data-dur="' + d + '"', 'chip chip--square'); });
    h += '</div><div class="filter" role="group" aria-label="Moment"><span class="filter__label">Moment</span>';
    Object.keys(PERIODS).forEach(function (k) { h += chip(PERIODS[k].label, S.period === k, 'data-period="' + k + '"', 'chip chip--square'); });
    h += '</div></div>';
    root.querySelector('[data-filters]').innerHTML = h;
  }

  /* ---------- Grille ---------- */
  function renderGrid() {
    var sp = SPORTS[S.sport], ts = times(), cs = courtsOf(S.sport), price = sp.prices[S.dur];
    var h = '<div class="slots"><div class="slots__row"><span class="slots__court"></span>';
    ts.forEach(function (t) { h += '<span class="slots__time">' + fmt(t) + '</span>'; });
    h += '</div>';
    cs.forEach(function (c) {
      var ci = COURTS.indexOf(c);
      h += '<div class="slots__row"><span class="slots__court">' + c.name + (c.tag ? '<small>' + c.tag + '</small>' : '') + '</span>';
      ts.forEach(function (t) {
        var st = status(ci, t);
        var sel = S.sel && S.sel.ci === ci && S.sel.t === t;
        var aria = c.name + ', ' + fmt(t);
        if (st === 'free') h += '<button type="button" class="slot" aria-pressed="' + !!sel + '" data-ci="' + ci + '" data-t="' + t + '" aria-label="' + aria + ', libre, ' + price + ' euros par ' + sp.unit + '">' + price + ' €</button>';
        else if (st === 'open') h += '<button type="button" class="slot slot--open" aria-pressed="' + !!sel + '" data-ci="' + ci + '" data-t="' + t + '" data-join="1" aria-label="' + aria + ', partie ouverte, 1 place">' + (sel ? 'Choisi' : '1 place') + '</button>';
        else h += '<span class="slot slot--' + st + '" aria-label="' + aria + ', ' + (st === 'taken' ? 'réservé' : 'indisponible') + '">' + (st === 'taken' ? 'Pris' : '—') + '</span>';
      });
      h += '</div>';
    });
    h += '</div>';
    root.querySelector('[data-grid]').innerHTML = h;
  }

  /* ---------- Plan du club (isométrique) ---------- */
  var SC = 7.2, H = 9;
  function iso(x, y) { return [(x - y) * 0.866 * SC, (x + y) * 0.5 * SC]; }
  function pts(arr) { return arr.map(function (p) { return p[0].toFixed(1) + ',' + p[1].toFixed(1); }).join(' '); }

  function renderPlan() {
    var ts = times();
    if (S.time === null || ts.indexOf(S.time) < 0) S.time = ts[0];
    var h = '<div class="filter" role="group" aria-label="Heure de début"><span class="filter__label">Début</span>';
    ts.forEach(function (t) { h += chip(fmt(t), S.time === t, 'data-time="' + t + '"', 'chip chip--square'); });
    h += '</div>';

    // Bornes du dessin
    var ox = 46 * 0.866 * SC + 30, oy = 30;
    var svg = '<svg viewBox="0 0 ' + Math.round(ox + 80 * 0.866 * SC + 30) + ' ' + Math.round(oy + (78 + 46) * 0.5 * SC + 40) + '" role="group" aria-label="Plan du club à ' + fmt(S.time) + '">';
    svg += '<g transform="translate(' + ox.toFixed(1) + ',' + oy + ')">';
    // Sol du hall
    svg += '<polygon points="' + pts([iso(-3, -3), iso(80, -3), iso(80, 70), iso(-3, 70)]) + '" fill="#F7F8FC" stroke="#CDD2E6" stroke-width="2"/>';
    // Club-house
    var cb = [iso(0, 52), iso(58, 52), iso(58, 66), iso(0, 66)];
    svg += '<polygon points="' + pts([iso(58, 52), iso(58, 66), [iso(58, 66)[0], iso(58, 66)[1] + 22], [iso(58, 52)[0], iso(58, 52)[1] + 22]]) + '" fill="#0F1328"/>';
    svg += '<polygon points="' + pts([iso(0, 66), iso(58, 66), [iso(58, 66)[0], iso(58, 66)[1] + 22], [iso(0, 66)[0], iso(0, 66)[1] + 22]]) + '" fill="#1F2645"/>';
    svg += '<polygon points="' + pts(cb) + '" fill="#171D36"/>';
    var cc = iso(29, 59);
    svg += '<text x="' + cc[0].toFixed(1) + '" y="' + (cc[1] + 6).toFixed(1) + '" text-anchor="middle" fill="#FAE789" font-size="17" font-weight="800" font-family="Roboto Condensed, sans-serif" letter-spacing="2">CLUB-HOUSE · BAR</text>';
    // Accueil
    var ac = iso(70, 58);
    svg += '<polygon points="' + pts([iso(64, 52), iso(76, 52), iso(76, 64), iso(64, 64)]) + '" fill="#E3CB2D"/>';
    svg += '<text x="' + ac[0].toFixed(1) + '" y="' + (ac[1] + 5).toFixed(1) + '" text-anchor="middle" fill="#171D36" font-size="13" font-weight="800" font-family="Roboto Condensed, sans-serif">ACCUEIL</text>';

    COURTS.forEach(function (c, ci) {
      var active = c.sport === S.sport;
      var st = active ? status(ci, S.time) : 'other';
      var sel = S.sel && S.sel.ci === ci && S.sel.t === S.time;
      var top = sel ? '#FAE789' : st === 'free' ? '#2F6FD6' : st === 'open' ? '#5B8FE3' : st === 'other' ? '#C7CCDD' : '#9AA1BB';
      var side = sel ? '#C9B23A' : st === 'free' ? '#1F4E9E' : st === 'open' ? '#3F6DB8' : st === 'other' ? '#A9AFC4' : '#7C839E';
      var side2 = sel ? '#B39E2E' : st === 'free' ? '#173E80' : st === 'open' ? '#33599A' : st === 'other' ? '#989EB5' : '#6B728C';
      var a = iso(c.x, c.y), b = iso(c.x + c.w, c.y), cc2 = iso(c.x + c.w, c.y + c.d), d = iso(c.x, c.y + c.d);
      var clickable = active && (st === 'free' || st === 'open');
      var label = c.name + ', ' + (st === 'free' ? 'libre' : st === 'open' ? 'partie ouverte, 1 place' : st === 'other' ? 'autre sport' : st === 'closed' ? 'indisponible' : 'réservée') + ' à ' + fmt(S.time);
      svg += '<g class="court' + (clickable ? '' : ' court--taken') + '"' + (st === 'other' ? ' opacity="0.45"' : '') + (clickable ? ' role="button" tabindex="0" data-ci="' + ci + '" data-t="' + S.time + '"' + (st === 'open' ? ' data-join="1"' : '') : ' role="img"') + ' aria-label="' + esc(label) + '"' + (clickable ? ' aria-pressed="' + !!sel + '"' : '') + '>';
      // faces latérales (vitres)
      svg += '<polygon points="' + pts([b, cc2, [cc2[0], cc2[1] + H], [b[0], b[1] + H]]) + '" fill="' + side + '"/>';
      svg += '<polygon points="' + pts([d, cc2, [cc2[0], cc2[1] + H], [d[0], d[1] + H]]) + '" fill="' + side2 + '"/>';
      svg += '<polygon class="court__top" points="' + pts([a, b, cc2, d]) + '" fill="' + top + '" stroke="#FFFFFF" stroke-width="2"/>';
      // filet + lignes
      var n1 = iso(c.x, c.y + c.d / 2), n2 = iso(c.x + c.w, c.y + c.d / 2);
      svg += '<line x1="' + n1[0].toFixed(1) + '" y1="' + n1[1].toFixed(1) + '" x2="' + n2[0].toFixed(1) + '" y2="' + n2[1].toFixed(1) + '" stroke="#FFFFFF" stroke-width="3"/>';
      if (c.sport !== 'badminton') {
        [0.3, 0.7].forEach(function (f) {
          var l1 = iso(c.x, c.y + c.d * f), l2 = iso(c.x + c.w, c.y + c.d * f);
          svg += '<line x1="' + l1[0].toFixed(1) + '" y1="' + l1[1].toFixed(1) + '" x2="' + l2[0].toFixed(1) + '" y2="' + l2[1].toFixed(1) + '" stroke="rgba(255,255,255,.6)" stroke-width="1.2"/>';
        });
        var m1 = iso(c.x + c.w / 2, c.y + c.d * 0.3), m2 = iso(c.x + c.w / 2, c.y + c.d * 0.7);
        svg += '<line x1="' + m1[0].toFixed(1) + '" y1="' + m1[1].toFixed(1) + '" x2="' + m2[0].toFixed(1) + '" y2="' + m2[1].toFixed(1) + '" stroke="rgba(255,255,255,.6)" stroke-width="1.2"/>';
      }
      var ce = iso(c.x + c.w / 2, c.y + c.d * 0.22);
      var txt = sel ? '#171D36' : '#FFFFFF';
      svg += '<text x="' + ce[0].toFixed(1) + '" y="' + ce[1].toFixed(1) + '" text-anchor="middle" fill="' + txt + '" font-size="' + (c.w < 8 ? 11 : 14) + '" font-weight="800" font-family="Roboto Condensed, sans-serif" pointer-events="none">' + esc(c.name.replace('Badminton', 'Bad.').toUpperCase()) + '</text>';
      if (st === 'open' && !sel) svg += '<text x="' + ce[0].toFixed(1) + '" y="' + (ce[1] + 15).toFixed(1) + '" text-anchor="middle" fill="#FAE789" font-size="12" font-weight="700" font-family="Roboto Condensed, sans-serif" pointer-events="none">1 PLACE</text>';
      svg += '</g>';
    });
    svg += '</g></svg>';
    h += '<div class="plan">' + svg + '</div><p class="plan-info">Cliquez sur une piste bleue pour la réserver. Bleu foncé : libre · bleu clair : partie ouverte · gris : réservée. Les pistes des autres sports sont estompées.</p>';
    root.querySelector('[data-plan]').innerHTML = h;
  }

  /* ---------- Récapitulatif ---------- */
  function renderSide() {
    var side = root.querySelector('[data-side]');
    var sp = SPORTS[S.sport];
    if (!S.sel) {
      side.innerHTML = '<h2>Votre réservation</h2><div class="empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg><strong>Choisissez un créneau</strong><span class="note">Dans la grille ou directement sur le plan du club.</span></div><ul class="note" style="display:flex;flex-direction:column;gap:6px"><li>✓ Sans compte : un e-mail suffit</li><li>✓ Paiement partagé entre joueurs</li><li>✓ Annulation gratuite jusqu’à 24 h avant</li></ul>';
      return;
    }
    var c = COURTS[S.sel.ci], price = sp.prices[S.dur], d = DAYS[S.day];
    var join = !!S.sel.join;
    var n = sp.players;
    var total = sp.unit === 'joueur' ? price * n : price;
    var share = sp.unit === 'joueur' ? price : Math.round(price / n * 100) / 100;
    var now = join ? price : (S.split ? share : total);
    var when = d.full + ' · ' + fmt(S.sel.t) + ' – ' + fmt(S.sel.t + S.dur);

    if (S.done) {
      var code = 'PSC-' + (1000 + ((S.sel.ci + 3) * 97 + S.sel.t) % 9000);
      side.innerHTML = '<div class="confirm"><div class="confirm__icon"><svg viewBox="0 0 24 24" fill="none" stroke="#171D36" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5L20 7"/></svg></div><h2>C’est réservé !</h2><p><strong>' + c.name + '</strong><br>' + when + '</p><p class="note">Réservation ' + code + ' · confirmation envoyée par e-mail et SMS (démo).</p>' +
        (join ? '' : '<p class="note">' + (S.split ? 'Vos partenaires reçoivent un lien pour payer leur part. Relance automatique si besoin.' : 'Vous avez réglé la totalité.') + '</p>') +
        '<button type="button" class="btn btn--navy btn--block" data-ics>Ajouter à mon agenda</button><button type="button" class="btn btn--outline btn--block" data-again>Nouvelle réservation</button></div>';
      return;
    }

    var h = '<h2>' + (join ? 'Rejoindre la partie' : 'Votre réservation') + '</h2>';
    h += '<div class="recap-head"><small>' + sp.label + ' · ' + fmtDur(S.dur) + '</small><strong>' + c.name + '</strong><span>' + when + '</span></div>';
    if (join) {
      h += '<div class="stack" style="gap:10px"><span class="filter__label">Joueurs inscrits · niveau 3 – 4</span><div class="players"><span class="player player--friend"><i>TL</i>Thibault</span><span class="player player--friend"><i>MB</i>Marine</span><span class="player player--friend"><i>SR</i>Samir</span><span class="player player--me"><i>V</i>Vous</span></div></div>';
    } else {
      h += '<div class="stack" style="gap:10px"><span class="filter__label">Joueurs</span><div class="players"><span class="player player--me"><i>V</i>Vous</span>';
      for (var p = 1; p < n; p++) {
        if (p <= S.friends) h += '<span class="player player--friend"><i>A' + p + '</i>Ami ' + p + '</span>';
        else h += '<button type="button" class="player player--invite" data-invite><i>+</i>Inviter</button>';
      }
      h += '</div><span class="note">Lien d’invitation par SMS ou WhatsApp : vos amis paient leur part, sans compte.</span></div>';
      if (sp.unit === 'joueur') h += '<button type="button" class="switch" role="switch" aria-checked="' + S.open + '" data-toggle="open"><span class="switch__track"></span><span><strong>Compléter avec des joueurs du club</strong><small>Places libres proposées aux joueurs de niveau proche</small></span></button>';
      h += '<button type="button" class="switch" role="switch" aria-checked="' + S.split + '" data-toggle="split"><span class="switch__track"></span><span><strong>Chacun paie sa part</strong><small>Sinon, vous réglez la totalité</small></span></button>';
    }
    h += '<div class="stack" style="gap:8px"><span class="filter__label">Paiement</span>';
    [['credits', 'Mes crédits club', 'Solde : 87 €'], ['card', 'Carte bancaire', 'Paiement sécurisé'], ['wallet', 'Apple Pay / Google Pay', '']].forEach(function (m) {
      h += '<button type="button" class="pay" aria-pressed="' + (S.pay === m[0]) + '" data-pay="' + m[0] + '"><span>' + m[1] + '</span><small>' + m[2] + '</small></button>';
    });
    h += '</div><div class="totals">';
    if (!join) h += '<div><span class="note" style="font-size:16px">' + (sp.unit === 'joueur' ? price + ' € × ' + n + ' joueurs' : 'Terrain ' + fmtDur(S.dur)) + '</span><strong>' + euro(total) + '</strong></div>';
    h += '<div class="big"><span>À payer maintenant</span><span>' + euro(now) + '</span></div></div>';
    h += '<button type="button" class="btn btn--yellow btn--lg btn--block" style="border:2px solid #171D36" data-pay-now>Payer ' + euro(now) + (join || !S.split ? '' : ' · ma part') + '</button>';
    h += '<span class="note" style="text-align:center">Démo : aucun paiement n’est réellement effectué.</span>';
    side.innerHTML = h;
  }

  function render() {
    renderFilters();
    root.querySelectorAll('[data-view]').forEach(function (b) { b.setAttribute('aria-pressed', b.getAttribute('data-view') === S.view ? 'true' : 'false'); });
    root.querySelector('[data-grid-wrap]').hidden = S.view !== 'grid';
    root.querySelector('[data-plan]').hidden = S.view !== 'plan';
    var d = DAYS[S.day];
    root.querySelector('[data-title]').textContent = d.full + ' · ' + PERIODS[S.period].label.toLowerCase();
    var sp = SPORTS[S.sport];
    root.querySelector('[data-unit]').textContent = 'Prix par ' + sp.unit + ' pour ' + fmtDur(S.dur) + '. Disponibilités simulées pour la démonstration.';
    if (S.view === 'grid') renderGrid(); else renderPlan();
    renderSide();
  }

  function pick(ci, t, join) {
    if (S.sel && S.sel.ci === ci && S.sel.t === t) S.sel = null;
    else S.sel = { ci: ci, t: t, join: !!join };
    S.done = false; S.friends = 0;
    render();
    if (S.sel && window.matchMedia('(max-width: 1000px)').matches) {
      root.querySelector('[data-side]').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  function downloadIcs() {
    var c = COURTS[S.sel.ci], d = DAYS[S.day].date;
    function stamp(min) {
      var x = new Date(d); x.setHours(0, min, 0, 0);
      var p = function (v) { return (v < 10 ? '0' : '') + v; };
      return x.getFullYear() + p(x.getMonth() + 1) + p(x.getDate()) + 'T' + p(x.getHours()) + p(x.getMinutes()) + '00';
    }
    var ics = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//Padel Sporting Club//Demo//FR', 'BEGIN:VEVENT',
      'UID:' + Date.now() + '@padelsportingclub-demo', 'DTSTART:' + stamp(S.sel.t), 'DTEND:' + stamp(S.sel.t + S.dur),
      'SUMMARY:' + SPORTS[S.sport].label + ' – ' + c.name, 'LOCATION:Padel Sporting Club\\, 281 Rue Vulcain\\, 27000 Évreux',
      'END:VEVENT', 'END:VCALENDAR'].join('\r\n');
    var a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([ics], { type: 'text/calendar' }));
    a.download = 'reservation-padel.ics';
    document.body.appendChild(a); a.click(); a.remove();
  }

  root.addEventListener('click', function (e) {
    var el = e.target.closest('button, [role="button"]');
    if (!el || !root.contains(el)) return;
    var ds = el.dataset;
    if (ds.sport) { S.sport = ds.sport; S.dur = SPORTS[S.sport].durs.indexOf(90) >= 0 ? 90 : SPORTS[S.sport].durs[0]; S.sel = null; S.done = false; render(); }
    else if (ds.day) { S.day = +ds.day; S.sel = null; S.done = false; render(); }
    else if (ds.dur) { S.dur = +ds.dur; S.sel = null; S.done = false; render(); }
    else if (ds.period) { S.period = ds.period; S.sel = null; S.time = null; S.done = false; render(); }
    else if (ds.time) { S.time = +ds.time; render(); }
    else if (ds.view) { S.view = ds.view; render(); }
    else if (ds.ci) { pick(+ds.ci, +ds.t, ds.join); }
    else if (ds.toggle) { S[ds.toggle] = !S[ds.toggle]; renderSide(); }
    else if (ds.pay) { S.pay = ds.pay; renderSide(); }
    else if (ds.invite !== undefined) { S.friends++; renderSide(); window.pscToast && pscToast('<b>Lien d’invitation copié.</b> Envoyez-le par SMS ou WhatsApp.'); }
    else if (ds.payNow !== undefined) { S.done = true; renderSide(); window.pscToast && pscToast('<b>Réservation confirmée</b> (démo).'); }
    else if (ds.ics !== undefined) { downloadIcs(); }
    else if (ds.again !== undefined) { S.sel = null; S.done = false; render(); }
  });
  root.addEventListener('keydown', function (e) {
    var el = e.target.closest('[role="button"]');
    if (el && el.tagName !== 'BUTTON' && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); el.click(); }
  });

  // Lien direct : reserver.html?vue=plan&sport=badminton
  var q = new URLSearchParams(location.search);
  if (q.get('vue') === 'plan') S.view = 'plan';
  if (SPORTS[q.get('sport')]) { S.sport = q.get('sport'); S.dur = SPORTS[S.sport].durs.indexOf(90) >= 0 ? 90 : SPORTS[S.sport].durs[0]; }
  render();
})();
