/*
 * Onglets de page : <div data-tabs> contenant <nav class="tabs"><a href="#id">…</a></nav>
 * et des <section class="tab-panel" id="id">. L'onglet suit l'ancre de l'URL (#id),
 * y compris une ancre placée à l'intérieur d'un onglet (ex. #inventaire) ; les liens
 * du menu de gauche (data-tab-link) sont mis en surbrillance. Sans JavaScript, tout s'affiche.
 */
(function () {
    var box = document.querySelector('[data-tabs]');
    if (!box) return;
    box.classList.add('js-tabs');
    var panels = box.querySelectorAll('.tab-panel');
    var links = box.querySelectorAll('.tabs a');

    function activate(id, scrollTarget) {
        var found = false;
        panels.forEach(function (p) { var on = p.id === id; p.classList.toggle('active', on); if (on) found = true; });
        if (!found) { panels[0].classList.add('active'); id = panels[0].id; }
        links.forEach(function (a) { a.classList.toggle('active', a.getAttribute('href') === '#' + id); });
        document.querySelectorAll('[data-tab-link]').forEach(function (a) { a.classList.toggle('active', a.dataset.tabLink === id); });
        if (scrollTarget) scrollTarget.scrollIntoView({ block: 'start' });
    }
    function fromHash() {
        var h = decodeURIComponent(location.hash.slice(1));
        if (!h) return activate(panels[0].id);
        var el = document.getElementById(h);
        if (!el) return activate(panels[0].id);
        var panel = el.classList.contains('tab-panel') ? el : el.closest('.tab-panel');
        if (!panel) return activate(panels[0].id);
        activate(panel.id, el === panel ? null : el);
        if (el === panel) window.scrollTo(0, 0);
    }
    links.forEach(function (a) {
        a.addEventListener('click', function (e) {
            e.preventDefault();
            var id = a.getAttribute('href').slice(1);
            history.replaceState(null, '', '#' + id);
            activate(id);
        });
    });
    window.addEventListener('hashchange', fromHash);
    fromHash();
})();
