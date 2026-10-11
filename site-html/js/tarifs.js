/**
 * Pertec - Configurateur de tarifs
 * Calculateur de prix interactif
 */

(function() {
    'use strict';

    // Configuration injectée par PHP
    const TARIFS = window.PERTEC_TARIFS || {};
    const CONFIG = TARIFS.config || {};

    // État
    let state = {
        typeSite: 'vitrine5',
        options: [],
        suivi: [],
        delai: 'normal',
        timestamp: Date.now()
    };

    // Éléments DOM
    let elements = {};

    // Initialisation
    function init() {
        cacheElements();
        bindEvents();
        loadDefaults();
        updateTotaux();
        initAnimations();
        initBarreMobile();
    }

    // Cache les éléments DOM
    function cacheElements() {
        elements.form = document.getElementById('tarifs-form');
        elements.totalCreation = document.getElementById('total-creation');
        elements.totalMensuel = document.getElementById('total-mensuel');
        elements.detailCreation = document.getElementById('detail-creation');
        elements.detailMensuel = document.getElementById('detail-mensuel');
        elements.barreMontantCreation = document.getElementById('barre-montant-creation');
        elements.barreMontantMensuel = document.getElementById('barre-montant-mensuel');
        elements.barreBtn = document.getElementById('barre-btn');
        elements.inputTimestamp = document.getElementById('tarifs-timestamp');
        elements.totauxRegion = document.querySelector('[aria-live]');
    }

    // Bind des événements
    function bindEvents() {
        // Options type de site
        document.querySelectorAll('input[name="type_site"]').forEach(input => {
            input.addEventListener('change', function() {
                state.typeSite = this.value;
                updateSelection(this);
                updateTotaux();
            });
        });

        // Options supplémentaires
        document.querySelectorAll('input[name="options[]"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    state.options.push(this.value);
                } else {
                    state.options = state.options.filter(o => o !== this.value);
                }
                updateSelection(this);
                updateTotaux();
            });
        });

        // Suivi
        document.querySelectorAll('input[name="suivi[]"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    state.suivi.push(this.value);
                } else {
                    state.suivi = state.suivi.filter(s => s !== this.value);
                }
                updateSelection(this);
                updateTotaux();
            });
        });

        // Délai
        document.querySelectorAll('input[name="delai"]').forEach(input => {
            input.addEventListener('change', function() {
                state.delai = this.value;
                updateSelection(this);
                updateTotaux();
            });
        });

        // Formulaire
        if (elements.form) {
            elements.form.addEventListener('submit', handleSubmit);
        }

        // Barre mobile
        if (elements.barreBtn) {
            elements.barreBtn.addEventListener('click', scrollToRecap);
        }
    }

    // Charge les valeurs par défaut
    function loadDefaults() {
        // Type de site par défaut
        const defaultType = document.querySelector('input[name="type_site"]:checked');
        if (defaultType) {
            state.typeSite = defaultType.value;
            updateSelection(defaultType);
        }

        // Délai par défaut
        const defaultDelai = document.querySelector('input[name="delai"]:checked');
        if (defaultDelai) {
            state.delai = defaultDelai.value;
            updateSelection(defaultDelai);
        }

        // Timestamp pour anti-spam
        if (elements.inputTimestamp) {
            elements.inputTimestamp.value = state.timestamp;
        }
    }

    // Met à jour la classe selected sur l'option
    function updateSelection(input) {
        const option = input.closest('.tarifs-option');
        if (!option) return;

        const groupe = option.closest('.tarifs-options');
        if (input.type === 'radio' && groupe) {
            groupe.querySelectorAll('.tarifs-option').forEach(o => {
                o.classList.remove('selected');
            });
        }

        if (input.checked) {
            option.classList.add('selected');
        } else {
            option.classList.remove('selected');
        }
    }

    // Calcul des totaux
    function calculerTotaux() {
        let creation = 0;
        let mensuel = 39; // Suivi de base toujours inclus
        let lignesCreation = [];
        let lignesMensuel = [];
        let surDevis = false;

        // Type de site
        const typeSiteData = TARIFS.type_site?.options?.[state.typeSite];
        if (typeSiteData) {
            if (typeSiteData.creation === null) {
                surDevis = true;
            } else {
                creation += typeSiteData.creation;
                lignesCreation.push({
                    nom: typeSiteData.label,
                    prix: typeSiteData.creation
                });
            }
        }

        // Options
        state.options.forEach(optId => {
            const opt = TARIFS.options?.options?.[optId];
            if (opt) {
                creation += opt.creation || 0;
                mensuel += opt.mensuel || 0;
                if (opt.creation > 0) {
                    lignesCreation.push({ nom: opt.label, prix: opt.creation });
                }
                if (opt.mensuel > 0) {
                    lignesMensuel.push({ nom: opt.label, prix: opt.mensuel });
                }
            }
        });

        // Suivi base (toujours inclus)
        lignesMensuel.unshift({
            nom: 'Hébergement, maintenance, modifications',
            prix: 39
        });

        // Options suivi
        state.suivi.forEach(suiviId => {
            const suivi = TARIFS.suivi?.options?.[suiviId];
            if (suivi) {
                mensuel += suivi.mensuel || 0;
                if (suivi.mensuel > 0) {
                    lignesMensuel.push({ nom: suivi.label, prix: suivi.mensuel });
                }
            }
        });

        // Majoration express
        if (state.delai === 'express' && !surDevis) {
            const majoration = TARIFS.delai?.options?.express?.majoration || 0.20;
            const supplement = Math.round(creation * majoration / 10) * 10;
            creation += supplement;
            if (supplement > 0) {
                lignesCreation.push({ nom: 'Livraison express', prix: supplement });
            }
        }

        return {
            creation,
            mensuel,
            lignesCreation,
            lignesMensuel,
            surDevis
        };
    }

    // Met à jour l'affichage des totaux
    function updateTotaux() {
        const { creation, mensuel, lignesCreation, lignesMensuel, surDevis } = calculerTotaux();

        // Animation des montants
        if (surDevis) {
            if (elements.totalCreation) {
                elements.totalCreation.textContent = 'Sur devis';
                elements.totalCreation.classList.add('tarifs-total-montant--devis');
                // Retirer "à partir de"
                const labelCreation = elements.totalCreation.previousElementSibling;
                if (labelCreation && labelCreation.textContent.includes('à partir de')) {
                    labelCreation.textContent = 'Création du site, une seule fois';
                }
            }
            if (elements.barreMontantCreation) {
                elements.barreMontantCreation.textContent = 'Sur devis';
            }
        } else {
            animerMontant(elements.totalCreation, creation, '€');
            if (elements.barreMontantCreation) {
                elements.barreMontantCreation.textContent = formatPrix(creation);
            }
            if (elements.totalCreation) {
                elements.totalCreation.classList.remove('tarifs-total-montant--devis');
                const labelCreation = elements.totalCreation.previousElementSibling;
                if (labelCreation && !labelCreation.textContent.includes('à partir de')) {
                    labelCreation.innerHTML = 'Création du site, une seule fois<br><small>à partir de</small>';
                }
            }
        }

        animerMontant(elements.totalMensuel, mensuel, '€/mois');
        if (elements.barreMontantMensuel) {
            elements.barreMontantMensuel.textContent = formatPrix(mensuel) + '/mois';
        }

        // Mise à jour des détails
        updateDetail(elements.detailCreation, lignesCreation, surDevis);
        updateDetail(elements.detailMensuel, lignesMensuel, false);

        // Champs cachés pour le formulaire
        updateHiddenFields();
    }

    // Animation du montant
    function animerMontant(element, targetValue, suffix) {
        if (!element) return;

        // Respect reduced motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            element.textContent = formatPrix(targetValue) + (suffix === '€/mois' ? '/mois' : ' €');
            return;
        }

        const currentText = element.textContent.replace(/[^\d]/g, '');
        const currentValue = parseInt(currentText) || 0;
        const duration = 350;
        const startTime = performance.now();

        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const value = Math.round(currentValue + (targetValue - currentValue) * eased);

            element.textContent = formatPrix(value) + (suffix === '€/mois' ? '/mois' : ' €');

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);
    }

    // Format prix français
    function formatPrix(prix) {
        return new Intl.NumberFormat('fr-FR').format(prix);
    }

    // Met à jour les lignes de détail
    function updateDetail(container, lignes, surDevis) {
        if (!container) return;

        container.innerHTML = '';

        if (surDevis && container.id === 'detail-creation') {
            const li = document.createElement('li');
            li.className = 'tarifs-detail-ligne';
            li.innerHTML = '<span class="tarifs-detail-ligne-nom">Tarif établi sur devis</span>';
            container.appendChild(li);
            return;
        }

        lignes.forEach((ligne, index) => {
            const li = document.createElement('li');
            li.className = 'tarifs-detail-ligne';
            li.style.animationDelay = (index * 50) + 'ms';
            li.innerHTML = `
                <span class="tarifs-detail-ligne-nom">${escapeHtml(ligne.nom)}</span>
                <span class="tarifs-detail-ligne-prix">${formatPrix(ligne.prix)} €</span>
            `;
            container.appendChild(li);
        });
    }

    // Échapper HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Met à jour les champs cachés du formulaire
    function updateHiddenFields() {
        const hiddenChoix = document.getElementById('tarifs-choix');
        if (hiddenChoix) {
            hiddenChoix.value = JSON.stringify({
                type_site: state.typeSite,
                options: state.options,
                suivi: state.suivi,
                delai: state.delai
            });
        }
    }

    // Gestion de la soumission du formulaire
    async function handleSubmit(e) {
        e.preventDefault();

        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.textContent;

        // Validation
        const email = form.querySelector('input[name="email"]').value;
        if (!email || !email.includes('@')) {
            alert('Veuillez entrer une adresse e-mail valide.');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Envoi...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showSuccess();
            } else {
                alert(result.message || 'Une erreur est survenue. Veuillez réessayer.');
                btn.disabled = false;
                btn.textContent = originalText;
            }
        } catch (error) {
            // Fallback: soumission classique
            form.submit();
        }
    }

    // Affiche le message de succès
    function showSuccess() {
        const formContainer = document.querySelector('.tarifs-form');
        if (formContainer) {
            formContainer.innerHTML = `
                <div class="tarifs-succes">
                    <p class="tarifs-succes-titre">C'est noté !</p>
                    <p class="tarifs-succes-texte">Votre estimation arrive dans votre boîte mail, et je vous recontacte sous 24 h pour en parler.</p>
                </div>
            `;
        }
    }

    // Animations au scroll
    function initAnimations() {
        // Respect reduced motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.querySelectorAll('.tarifs-section').forEach(section => {
                section.classList.add('visible');
            });
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 60);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        document.querySelectorAll('.tarifs-section').forEach(section => {
            observer.observe(section);
        });
    }

    // Barre mobile
    function initBarreMobile() {
        const recap = document.getElementById('tarifs-recap');
        if (!recap) return;

        const barre = document.querySelector('.tarifs-barre-mobile');
        if (!barre) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    barre.style.transform = 'translateY(100%)';
                } else {
                    barre.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        observer.observe(recap);
        barre.style.transition = 'transform 200ms ease';
    }

    // Scroll vers le récap
    function scrollToRecap() {
        const recap = document.getElementById('tarifs-recap');
        if (recap) {
            recap.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Démarrage
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
