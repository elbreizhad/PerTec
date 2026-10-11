/*
 * Onglets de page : <div data-tabs> contenant <nav class="tabs"><a href="#id">…</a></nav>
 * et des <section class="tab-panel" id="id">. L'onglet suit l'ancre de l'URL (#id),
 * y compris une ancre placée à l'intérieur d'un onglet (ex. #inventaire) ; les liens
 * du menu de gauche (data-tab-link) sont mis en surbrillance. Sans JavaScript, tout s'affiche.
 */
(function () {
    // Plusieurs blocs d'onglets possibles sur une page (ex. un par bail dans l'espace locataire).
    var boxes = document.querySelectorAll('[data-tabs]');
    if (!boxes.length) return;

    function setup(box) {
        box.classList.add('js-tabs');
        var panels = box.querySelectorAll('.tab-panel');
        var links = box.querySelectorAll('.tabs a');
        if (!panels.length) return;

        function activate(id, scrollTarget) {
            var found = false;
            panels.forEach(function (p) { var on = p.id === id; p.classList.toggle('active', on); if (on) found = true; });
            if (!found) { panels[0].classList.add('active'); id = panels[0].id; }
            links.forEach(function (a) { a.classList.toggle('active', a.getAttribute('href') === '#' + id); });
            if (boxes.length === 1) {
                document.querySelectorAll('[data-tab-link]').forEach(function (a) { a.classList.toggle('active', a.dataset.tabLink === id); });
            }
            if (scrollTarget) scrollTarget.scrollIntoView({ block: 'start' });
        }
        function fromHash() {
            var h = decodeURIComponent(location.hash.slice(1));
            var el = h ? document.getElementById(h) : null;
            var panel = el && box.contains(el) ? (el.classList.contains('tab-panel') ? el : el.closest('.tab-panel')) : null;
            if (!panel) {
                // L'ancre ne concerne pas ce bloc : on garde l'onglet actif, sinon le premier.
                if (!box.querySelector('.tab-panel.active')) activate(panels[0].id);
                return;
            }
            activate(panel.id, el === panel ? null : el);
            if (el === panel && boxes.length === 1) window.scrollTo(0, 0);
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
    }
    boxes.forEach(setup);
})();
