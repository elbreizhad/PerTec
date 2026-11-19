/* Accueil : plan interactif du complexe, tableau des pistes, parties ouvertes, histoire au défilement (démo). */
(function () {
  'use strict';
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- Envie de jouer ? : pastilles horaires ---------- */
  var MOMENTS = { soir: { label: 'Ce soir', start: 1020, end: 1350, day: 0 }, midi: { label: 'Demain midi', start: 660, end: 810, day: 1 }, we: { label: 'Ce week-end', start: 540, end: 870, day: 2 } };
  var COURTS = 10;
  function fmt(m) { return Math.floor(m / 60) + 'h' + (m % 60 ? '30' : '00'); }
  function freeAt(day, t) {
    var n = new Date(); if (day === 0 && t < n.getHours() * 60 + n.getMinutes()) return -1;
    var h = (t / 30 * 11 + day * 23 + 7) % 13, base = t >= 1050 && t <= 1260 ? 2 : t <= 690 ? 7 : 5;
    return Math.max(0, Math.min(COURTS, Math.round(base + (h - 6) / 2)));
  }
  var av = document.getElementById('avail'), ast = { m: 'soir', t: null };
  function renderAvail() {
    var M = MOMENTS[ast.m], h = '<div class="avail__chips" role="group" aria-label="Quand voulez-vous jouer ?">';
    Object.keys(MOMENTS).forEach(function (k) { h += '<button type="button" class="avail__chip" data-am="' + k + '" aria-pressed="' + (k === ast.m) + '">' + MOMENTS[k].label + '</button>'; });
    h += '</div><div class="avail__pills" role="group" aria-label="Heures de début">';
    var any = false;
    for (var t = M.start; t <= M.end; t += 30) {
      var f = freeAt(M.day, t); if (f < 0) continue; any = true;
      h += '<button type="button" class="avail__pill' + (f === 1 ? ' is-last' : '') + '" data-at="' + t + '" aria-pressed="' + (ast.t === t) + '"' + (f ? '' : ' disabled') + ' aria-label="' + fmt(t) + ', ' + (f ? f + ' piste' + (f > 1 ? 's' : '') + ' libre' + (f > 1 ? 's' : '') : 'complet') + '"><b>' + fmt(t) + '</b><span>' + (f === 0 ? 'Complet' : f === 1 ? 'Dernière piste' : f + ' pistes') + '</span><i style="--p:' + (f * 10) + '%"></i></button>';
    }
    h += '</div>';
    if (!any) h += '<p class="avail__hint">Plus de créneau ce soir : essayez demain midi ou ce week-end.</p>';
    else if (ast.t !== null) {
      var n = freeAt(M.day, ast.t), cs = []; for (var i = 1; cs.length < n; i++) cs.push('Piste ' + i);
      h += '<div class="avail__detail"><div><strong>' + M.label + ' · ' + fmt(ast.t) + '</strong><div class="avail__courts">' + cs.map(function (c) { return '<span>' + c + '</span>'; }).join('') + '</div></div><a class="pill-cta" href="reserver.html">Réserver ' + fmt(ast.t) + '</a></div>';
    } else h += '<p class="avail__hint">Touchez une heure pour voir les pistes libres.</p>';
    av.innerHTML = h;
  }
  av.addEventListener('click', function (e) {
    var b = e.target.closest('button'); if (!b || b.disabled) return;
    if (b.dataset.am) { ast.m = b.dataset.am; ast.t = null; }
    else if (b.dataset.at) { ast.t = +b.dataset.at === ast.t ? null : +b.dataset.at; }
    renderAvail();
  });
  renderAvail();

  /* ---------- Bande défilante ---------- */
  var words = ['10 pistes couvertes', '1 piste single', '2 terrains de badminton', '180 m² de club-house', 'Ouvert 7j/7 dès 9h', 'Tournois P25 à P500', 'Café offert le jeudi'];
  var tk = document.getElementById('ticker');
  tk.innerHTML = words.concat(words).map(function (w) { return '<span>' + w + '</span>'; }).join('');

  /* ---------- Parties ouvertes ---------- */
  var M = [
    { t: 19 * 60 + 30, lvl: 'Niveau 3 – 4', who: ['TL', 'MB', 'SR'], price: 13 },
    { t: 21 * 60, lvl: 'Niveau 4 – 5', who: ['GR', 'HF'], price: 9 },
    { t: 24 * 60 + 12 * 60 + 30, lvl: 'Débutants', who: ['AK', 'JD'], price: 9 },
    { t: 24 * 60 + 18 * 60 + 30, lvl: 'Mixte · niveau 2 – 3', who: ['CL', 'BN', 'OM'], price: 13 },
    { t: 48 * 60 + 10 * 60, lvl: 'Niveau 5 +', who: ['PV', 'LC', 'ND'], price: 13 }
  ];
  var joined = {};
  var mEl = document.getElementById('matches');
  function startIn(t) {
    var n = new Date(), now = n.getHours() * 60 + n.getMinutes(), d = t - now;
    if (d <= 0) d += 24 * 60;
    var h = Math.floor(d / 60), m = d % 60;
    return h >= 24 ? 'Dans ' + Math.round(h / 24) + ' j' : 'Dans ' + h + ' h ' + (m < 10 ? '0' : '') + m;
  }
  function day(t) { return t >= 48 * 60 ? 'Après-demain' : t >= 24 * 60 ? 'Demain' : 'Aujourd’hui'; }
  function renderMatches() {
    mEl.innerHTML = M.map(function (m, i) {
      var j = !!joined[i], seats = m.who.map(function (w) { return '<span class="seat">' + w + '</span>'; }).join('');
      var left = 4 - m.who.length - (j ? 1 : 0);
      if (j) seats += '<span class="seat seat--me">VS</span>';
      for (var k = 0; k < left; k++) seats += '<span class="seat seat--free" aria-hidden="true">+</span>';
      var hm = m.t % (24 * 60);
      return '<article class="mcard"><span class="mcard__t">' + startIn(m.t) + '</span><h3>' + day(m.t) + ' · ' + Math.floor(hm / 60) + 'h' + (hm % 60 ? '30' : '00') + '</h3><span class="mcard__lvl">' + m.lvl + '</span><div class="seats">' + seats + '</div><div class="mcard__foot"><b>' + (j ? 'Inscrit' : left + ' place' + (left > 1 ? 's' : '') + ' · ' + m.price + ' €') + '</b><button class="join" type="button" data-m="' + i + '" aria-pressed="' + j + '">' + (j ? 'Annuler' : 'Rejoindre') + '</button></div></article>';
    }).join('');
  }
  mEl.addEventListener('click', function (e) { var b = e.target.closest('[data-m]'); if (!b) return; var i = +b.dataset.m; joined[i] = !joined[i]; renderMatches(); });
  renderMatches(); setInterval(renderMatches, 60000);

  /* ---------- Histoire au défilement ---------- */
  var chapters = document.querySelectorAll('.chapter'), imgs = document.querySelectorAll('.story__media img'), num = document.getElementById('num');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (en) {
        if (!en.isIntersecting) return;
        var i = en.target.dataset.i;
        chapters.forEach(function (c) { c.classList.toggle('on', c === en.target); });
        imgs.forEach(function (im) { im.classList.toggle('on', im.dataset.i === i); });
        num.textContent = '0' + (+i + 1);
      });
    }, { rootMargin: '-45% 0px -45% 0px' });
    chapters.forEach(function (c) { io.observe(c); });
  }

  /* ---------- Plan du complexe (vue isométrique) ---------- */
  var SC = 6.2, C = 0.866;
  var minX = 1e9, maxX = -1e9, minY = 1e9, maxY = -1e9;
  function P(x, y, z) { var px = (x - y) * C * SC, py = (x + y) * .5 * SC - (z || 0) * SC * .9; minX = Math.min(minX, px); maxX = Math.max(maxX, px); minY = Math.min(minY, py); maxY = Math.max(maxY, py); return [px, py]; }
  function pts(a) { return a.map(function (p) { return p[0].toFixed(1) + ',' + p[1].toFixed(1); }).join(' '); }
  function poly(a, fill, extra) { return '<polygon points="' + pts(a) + '" fill="' + fill + '"' + (extra || '') + '/>'; }
  function box(x, y, w, d, h, top, s1, s2, cls) {
    var o = '';
    o += poly([P(x + w, y, 0), P(x + w, y + d, 0), P(x + w, y + d, h), P(x + w, y, h)], s1);
    o += poly([P(x, y + d, 0), P(x + w, y + d, 0), P(x + w, y + d, h), P(x, y + d, h)], s2);
    o += poly([P(x, y, h), P(x + w, y, h), P(x + w, y + d, h), P(x, y + d, h)], top, ' class="top' + (cls ? ' ' + cls : '') + '" stroke="rgba(255,255,255,.5)" stroke-width="1"');
    o += '<polygon class="edge" points="' + pts([P(x, y, h), P(x + w, y, h), P(x + w, y + d, h), P(x, y + d, h)]) + '" fill="none" stroke="transparent"/>';
    return o;
  }
  function line(a, b, w, col) { return '<line x1="' + a[0].toFixed(1) + '" y1="' + a[1].toFixed(1) + '" x2="' + b[0].toFixed(1) + '" y2="' + b[1].toFixed(1) + '" stroke="' + (col || '#fff') + '" stroke-width="' + w + '"/>'; }
  function label(x, y, z, txt, col, size) { var p = P(x, y, z); return '<text x="' + p[0].toFixed(1) + '" y="' + (p[1] + 4).toFixed(1) + '" text-anchor="middle" font-family="Roboto Condensed, sans-serif" font-weight="800" font-size="' + (size || 12) + '" letter-spacing="1.5" fill="' + col + '" pointer-events="none">' + txt + '</text>'; }
  function court(x, y, w, d, kind) {
    var o = box(x, y, w, d, .5, kind === 'bad' ? '#3F8F6B' : '#2F6FD6', kind === 'bad' ? '#2C6B4F' : '#1F4E9E', kind === 'bad' ? '#245B43' : '#173E80');
    o += line(P(x, y + d / 2, .5), P(x + w, y + d / 2, .5), 2.2);
    if (kind !== 'bad') { [.3, .7].forEach(function (f) { o += line(P(x, y + d * f, .5), P(x + w, y + d * f, .5), .8, 'rgba(255,255,255,.65)'); }); o += line(P(x + w / 2, y + d * .3, .5), P(x + w / 2, y + d * .7, .5), .8, 'rgba(255,255,255,.65)'); }
    // vitres de fond
    if (kind !== 'bad') { o += poly([P(x, y, .5), P(x + w, y, .5), P(x + w, y, 3), P(x, y, 3)], 'rgba(180,215,255,.16)', ' stroke="rgba(180,215,255,.35)" stroke-width=".8"'); }
    return o;
  }
  var ZONES = [
    { t: '10 pistes de padel', d: 'Pistes couvertes 2 contre 2, vitrées, praticables par tous les temps. Dès 9 € par joueur.', s: 'Pistes', draw: function () { var o = ''; for (var i = 0; i < 5; i++) { o += court(2 + i * 12, 2, 10, 20); o += court(2 + i * 12, 26, 10, 20); } return o; } },
    { t: '1 piste single', d: 'Le padel en 1 contre 1, idéal pour travailler sa technique et son physique.', s: 'Single', draw: function () { return court(64, 2, 6, 20); } },
    { t: '2 terrains de badminton', d: 'Pour jouer en simple ou en double, avec location de raquettes sur place.', s: 'Badminton', draw: function () { return court(64, 26, 6.1, 13.4, 'bad') + court(71.5, 26, 6.1, 13.4, 'bad'); } },
    { t: 'Espace d’échauffement', d: 'Un espace dédié pour préparer ses matchs et éviter les blessures.', s: 'Échauffement', draw: function () { return box(64, 42, 13.6, 8, .6, '#C9CFE6', '#9AA1BB', '#868DA8') + label(70.8, 46, .6, 'ÉCHAUFFEMENT', '#171D36', 10); } },
    { t: 'Club-house de 180 m²', d: 'Bar, espace cosy, restauration rapide le midi, planches le soir et café offert le jeudi matin.', s: 'Club-house', draw: function () { return box(2, 50, 34, 12, 3.2, '#FAE789', '#C9B23A', '#B39E2E') + label(19, 56, 3.2, 'CLUB-HOUSE', '#171D36', 15); } },
    { t: 'Pro-shop', d: 'Balles, grips, accessoires, raquettes et modèles premium à tester avant d’acheter.', s: 'Pro-shop', draw: function () { return box(38, 50, 11, 12, 2.6, '#EAECF6', '#B8BED6', '#9AA1BB') + label(43.5, 56, 2.6, 'PRO-SHOP', '#171D36', 11); } },
    { t: 'Vestiaires', d: 'Des vestiaires modernes pour se changer avant et après le match.', s: 'Vestiaires', draw: function () { return box(51, 50, 11, 12, 2.6, '#EAECF6', '#B8BED6', '#9AA1BB') + label(56.5, 56, 2.6, 'VESTIAIRES', '#171D36', 11); } },
    { t: 'Parking privé', d: 'Plus de 40 places gratuites juste devant le complexe, au 281 rue Vulcain.', s: 'Parking', draw: function () { var o = box(84, 4, 14, 56, .15, '#3A4470', '#2A3358', '#232B4C'); for (var k = 0; k < 13; k++) o += line(P(84.5, 6 + k * 4.2, .15), P(90, 6 + k * 4.2, .15), 1, 'rgba(255,255,255,.55)'); for (var q = 0; q < 13; q++) o += line(P(92, 6 + q * 4.2, .15), P(97.5, 6 + q * 4.2, .15), 1, 'rgba(255,255,255,.55)'); return o + label(91, 32, .15, 'P', '#FAE789', 22); } }
  ];
  var svg = document.getElementById('plan');
  var floor = poly([P(0, 0, 0), P(80, 0, 0), P(80, 64, 0), P(0, 64, 0)], '#232C55', ' stroke="rgba(255,255,255,.12)" stroke-width="1.5"');
  var zonesSvg = ZONES.map(function (z, i) { return '<g class="zone" data-z="' + i + '" tabindex="0" role="button" aria-label="' + z.t + '">' + z.draw() + '</g>'; });
  // ordre de dessin : de l’arrière vers l’avant
  var order = [7, 0, 1, 2, 3, 4, 5, 6];
  var balls = '<g id="balls"><circle r="3.2" fill="#E8F55A"/><circle r="3.2" fill="#E8F55A"/><circle r="3.2" fill="#E8F55A"/></g>';
  var pad = 14;
  var inner = floor + order.map(function (i) { return zonesSvg[i]; }).join('') + balls;
  svg.setAttribute('viewBox', (minX - pad).toFixed(0) + ' ' + (minY - pad - 10).toFixed(0) + ' ' + (maxX - minX + pad * 2).toFixed(0) + ' ' + (maxY - minY + pad * 2 + 10).toFixed(0));
  svg.innerHTML = inner;

  var zcur = 0, auto = !reduce, timer = null;
  var dots = document.getElementById('z-dots');
  dots.innerHTML = ZONES.map(function (z, i) { return '<button type="button" data-z="' + i + '" aria-pressed="false">' + z.s + '</button>'; }).join('');
  function show(i) {
    zcur = i;
    svg.querySelectorAll('.zone').forEach(function (g) { g.classList.toggle('on', +g.dataset.z === i); });
    dots.querySelectorAll('button').forEach(function (b) { b.setAttribute('aria-pressed', +b.dataset.z === i ? 'true' : 'false'); });
    document.getElementById('z-k').textContent = '0' + (i + 1) + ' / 0' + ZONES.length;
    document.getElementById('z-t').textContent = ZONES[i].t;
    document.getElementById('z-d').textContent = ZONES[i].d;
  }
  function stopAuto() { auto = false; clearInterval(timer); }
  function pick(e) { var g = e.target.closest('[data-z]'); if (!g) return; stopAuto(); show(+g.dataset.z); }
  svg.addEventListener('mouseover', pick); svg.addEventListener('click', pick); svg.addEventListener('focusin', pick);
  dots.addEventListener('click', pick);
  svg.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); pick(e); } });
  show(0);
  if (auto) timer = setInterval(function () { show((zcur + 1) % ZONES.length); }, 3200);

  // balles qui rebondissent sur trois pistes
  var B = [{ c: [2, 2], x: 3, y: 4, vx: 7, vy: 3 }, { c: [26, 26], x: 5, y: 12, vx: -6, vy: 4 }, { c: [50, 2], x: 6, y: 9, vx: 5, vy: -5 }];
  var circles = svg.querySelectorAll('#balls circle'), last = performance.now();
  function bounce(now) {
    var dt = Math.min(.05, (now - last) / 1000); last = now;
    B.forEach(function (b, i) {
      b.x += b.vx * dt; b.y += b.vy * dt;
      if (b.x < .5 || b.x > 9.5) { b.vx *= -1; b.x = Math.max(.5, Math.min(9.5, b.x)); }
      if (b.y < .5 || b.y > 19.5) { b.vy *= -1; b.y = Math.max(.5, Math.min(19.5, b.y)); b.vx += (Math.random() - .5) * 3; }
      var h = Math.abs(Math.sin(now / 260 + i)) * 1.6, p = P(b.c[0] + b.x, b.c[1] + b.y, .5 + h);
      circles[i].setAttribute('cx', p[0].toFixed(1)); circles[i].setAttribute('cy', p[1].toFixed(1));
    });
    requestAnimationFrame(bounce);
  }
  if (!reduce) requestAnimationFrame(bounce); else circles.forEach(function (c) { c.setAttribute('r', 0); });
})();
